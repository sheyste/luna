<?php

require_once BASE_PATH . '/core/Model.php';

#[AllowDynamicProperties]
class Inventory extends Model
{
    public function __construct()
    {
        $this->db = $this->connectDB();
    }

    public function getAllItems()
    {
        $stmt = $this->db->query("SELECT * FROM inventory ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getItemById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM inventory WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO inventory (name, barcode, quantity, max_quantity, unit, price, purchase_date, category) 
            VALUES (:name, :barcode, :quantity, :max_quantity, :unit, :price, :purchase_date, :category)
        ");

        return $stmt->execute([
            'name'          => $data['name'],
            'barcode'       => $data['barcode'],
            'quantity'      => $data['quantity'],
            'max_quantity'  => $data['max_quantity'],
            'unit'          => $data['unit'],
            'price'         => $data['price'],
            'purchase_date' => $data['purchase_date'],
            'category'      => $data['category']
        ]);
    }

    public function update($data)
    {
        // Get the current item data before updating
        $currentItem = $this->getItemById($data['id']);
        
        $stmt = $this->db->prepare("
            UPDATE inventory
            SET name = :name, barcode = :barcode, quantity = :quantity, max_quantity = :max_quantity, unit = :unit, price = :price, purchase_date = :purchase_date, category = :category
            WHERE id = :id
        ");

        $result = $stmt->execute([
            'id'            => $data['id'],
            'name'          => $data['name'],
            'barcode'       => $data['barcode'],
            'quantity'      => $data['quantity'],
            'max_quantity'  => $data['max_quantity'],
            'unit'          => $data['unit'],
            'price'         => $data['price'],
            'purchase_date' => $data['purchase_date'],
            'category'      => $data['category']
        ]);
        
        // If the update was successful, update alert resolution status based on current inventory status
        if ($result && $currentItem) {
            // Include LowStockAlert model to update alert resolution status
            require_once BASE_PATH . '/app/models/LowStockAlert.php';
            $alertModel = new LowStockAlert();
            
            // Check if item is currently low stock
            $isLowStock = ($data['quantity'] / $data['max_quantity']) <= 0.2;
            
            // Get all pending alerts for this item and update their resolution status
            $pendingAlerts = $alertModel->getPendingAlerts();
            foreach ($pendingAlerts as $alert) {
                if ($alert['item_id'] == $data['id']) {
                    // Update the resolved status based on current inventory status
                    $alertModel->updateAlertResolutionStatus($alert['id'], !$isLowStock);
                }
            }
        }
        
        return $result;
    }

    public function delete($id)
    {
        // Check if the item is referenced in purchase_order_items
        $poStmt = $this->db->prepare("SELECT COUNT(*) FROM purchase_order_items WHERE inventory_id = :id");
        $poStmt->execute(['id' => $id]);
        $poCount = $poStmt->fetchColumn();

        if ($poCount > 0) {
            throw new Exception("Cannot delete this inventory item because it is referenced in purchase orders.");
        }

        // Check if the item is referenced in menu_ingredients
        $menuStmt = $this->db->prepare("SELECT COUNT(*) FROM menu_ingredients WHERE inventory_id = :id");
        $menuStmt->execute(['id' => $id]);
        $menuCount = $menuStmt->fetchColumn();

        if ($menuCount > 0) {
            throw new Exception("Cannot delete this inventory item because it is used in menu ingredients.");
        }

        $stmt = $this->db->prepare("DELETE FROM inventory WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function getLowStockItems()
    {
        $stmt = $this->db->query("SELECT * FROM inventory WHERE quantity / max_quantity <= 0.2");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function updateQuantity($id, $quantity)
    {
        $stmt = $this->db->prepare("UPDATE inventory SET quantity = :quantity WHERE id = :id");
        return $stmt->execute(['id' => $id, 'quantity' => $quantity]);
    }
}
