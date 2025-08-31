<?php
/**
 * Model for Low Stock Alerts
 */

require_once BASE_PATH . '/core/Model.php';

class LowStockAlert extends Model
{
    public function __construct()
    {
        $this->db = $this->connectDB();
    }
    
    /**
     * Get all pending alerts
     */
    public function getPendingAlerts()
    {
        $stmt = $this->db->prepare("
            SELECT * FROM low_stock_alerts 
            WHERE status = 'pending' 
            ORDER BY alert_date ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Atomically get pending alerts and mark them as sent
     * This prevents concurrent requests from processing the same alerts
     */
    public function getAndMarkPendingAlertsAsSent()
    {
        try {
            // Start a transaction to ensure atomicity
            $this->db->beginTransaction();
            
            // Get pending alerts
            $stmt = $this->db->prepare("
                SELECT * FROM low_stock_alerts 
                WHERE status = 'pending' 
                ORDER BY alert_date ASC
            ");
            $stmt->execute();
            $alerts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // If we have alerts, mark them as sent
            if (!empty($alerts)) {
                $ids = array_column($alerts, 'id');
                $placeholders = str_repeat('?,', count($ids) - 1) . '?';
                
                $stmt = $this->db->prepare("
                    UPDATE low_stock_alerts 
                    SET status = 'sent', sent_date = NOW() 
                    WHERE id IN ($placeholders)
                ");
                $stmt->execute($ids);
            }
            
            // Commit the transaction
            $this->db->commit();
            
            return $alerts;
        } catch (Exception $e) {
            // Rollback the transaction on error
            $this->db->rollback();
            throw $e;
        }
    }
    
    /**
     * Get pending alerts with row-level locking to prevent concurrent processing
     * This ensures only one request can process the same alerts at a time
     */
    public function getPendingAlertsWithLock()
    {
        try {
            // Start a transaction
            $this->db->beginTransaction();
            
            // Get pending alerts with row-level locking
            $stmt = $this->db->prepare("
                SELECT * FROM low_stock_alerts 
                WHERE status = 'pending' 
                ORDER BY alert_date ASC
                FOR UPDATE
            ");
            $stmt->execute();
            $alerts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Return both the alerts and a flag to indicate we're in a transaction
            return [
                'alerts' => $alerts,
                'transaction' => true
            ];
        } catch (Exception $e) {
            // Rollback the transaction on error
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            throw $e;
        }
    }
    
    /**
     * Commit the transaction after processing alerts
     */
    public function commitTransaction()
    {
        if ($this->db->inTransaction()) {
            $this->db->commit();
        }
    }
    
    /**
     * Rollback the transaction if there's an error
     */
    public function rollbackTransaction()
    {
        if ($this->db->inTransaction()) {
            $this->db->rollback();
        }
    }
    
    /**
     * Get recent alerts for an item (within last hour)
     * Only returns pending or sent alerts that haven't been resolved
     */
    public function getRecentAlertsByItem($itemId)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM low_stock_alerts
            WHERE item_id = :item_id
            AND (status = 'pending' OR status = 'sent')
            AND resolved = 0
            ORDER BY alert_date DESC
            LIMIT 1
        ");
        $stmt->execute(['item_id' => $itemId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Create a new low stock alert
     */
    public function createAlert($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO low_stock_alerts (item_id, item_name, current_quantity, max_quantity, unit, alert_date, resolved)
            VALUES (:item_id, :item_name, :current_quantity, :max_quantity, :unit, NOW(), 0)
        ");
        
        return $stmt->execute([
            'item_id' => $data['item_id'],
            'item_name' => $data['item_name'],
            'current_quantity' => $data['current_quantity'],
            'max_quantity' => $data['max_quantity'],
            'unit' => $data['unit']
        ]);
    }
    
    /**
     * Mark an alert as sent
     */
    public function markAsSent($id)
    {
        $stmt = $this->db->prepare("
            UPDATE low_stock_alerts 
            SET status = 'sent', sent_date = NOW() 
            WHERE id = :id
        ");
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Mark an alert as resolved (when stock is replenished)
     */
    public function markAsResolved($id)
    {
        $stmt = $this->db->prepare("
            UPDATE low_stock_alerts
            SET status = 'resolved'
            WHERE id = :id
        ");
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * Mark all pending alerts for an item as resolved (when stock is replenished)
     */
    public function markItemAlertsAsResolved($itemId)
    {
        $stmt = $this->db->prepare("
            UPDATE low_stock_alerts
            SET status = 'resolved'
            WHERE item_id = :item_id AND status = 'pending'
        ");
        return $stmt->execute(['item_id' => $itemId]);
    }
    
    
    /**
     * Check if an alert already exists for this item that is still relevant
     * Returns true if there's a pending alert or a sent alert that hasn't been resolved
     */
    public function alertExistsForItem($itemId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count FROM low_stock_alerts
            WHERE item_id = :item_id
            AND (status = 'pending' OR status = 'sent')
            AND resolved = 0
        ");
        $stmt->execute(['item_id' => $itemId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
    
    /**
     * Get all alerts
     */
    public function getAllAlerts()
    {
        $stmt = $this->db->prepare("
            SELECT * FROM low_stock_alerts
            ORDER BY alert_date DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Automatically check and update alert resolution status
     * Marks alerts as resolved if the item is no longer low on stock
     */
    public function autoUpdateAlertResolutionStatus()
    {
        // Include Inventory model
        require_once BASE_PATH . '/app/models/Inventory.php';
        $inventoryModel = new Inventory();
        
        // Get all pending and sent alerts that haven't been resolved
        $stmt = $this->db->prepare("
            SELECT * FROM low_stock_alerts
            WHERE (status = 'pending' OR status = 'sent')
            AND resolved = 0
        ");
        $stmt->execute();
        $alerts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $updatedCount = 0;
        
        foreach ($alerts as $alert) {
            // Check if item is currently low stock
            $item = $inventoryModel->getItemById($alert['item_id']);
            if ($item) {
                $isLowStock = ($item['quantity'] / $item['max_quantity']) <= 0.2;
                
                // If item is not low stock, mark alert as resolved
                if (!$isLowStock) {
                    $this->updateAlertResolutionStatus($alert['id'], true);
                    $updatedCount++;
                }
            }
        }
        
        return $updatedCount;
    }
    /**
     * Mark an alert as resolved or not based on actual inventory status
     */
    public function updateAlertResolutionStatus($id, $isResolved)
    {
        $stmt = $this->db->prepare("
            UPDATE low_stock_alerts
            SET resolved = :resolved
            WHERE id = :id
        ");
        return $stmt->execute(['id' => $id, 'resolved' => $isResolved ? 1 : 0]);
    }
    
    /**
     * Check if an item is currently low on stock
     */
    public function isItemLowStock($itemId)
    {
        // Include Inventory model
        require_once BASE_PATH . '/app/models/Inventory.php';
        $inventoryModel = new Inventory();
        $item = $inventoryModel->getItemById($itemId);
        
        if (!$item) {
            return false;
        }
        
        // Check if item is low stock (quantity is 20% or less of max_quantity)
        return ($item['quantity'] / $item['max_quantity']) <= 0.2;
    }
}