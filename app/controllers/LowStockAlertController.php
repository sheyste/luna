<?php

require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/app/models/LowStockAlert.php';
require_once BASE_PATH . '/app/models/Inventory.php';
require_once BASE_PATH . '/app/models/UserModel.php';
require_once BASE_PATH . '/app/helpers/EmailHelper.php';

class LowStockAlertController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->checkAuth();
    }

    public function index()
    {
        // Include the LowStockAlert model
        require_once BASE_PATH . '/app/models/LowStockAlert.php';
        
        // Initialize the alert model
        $alertModel = new LowStockAlert();
        
        // Get all alerts
        $alerts = $alertModel->getAllAlerts();
        
        // Pass alerts to the view
        $this->view('low_stock_alerts', ['alerts' => $alerts]);
    }
    
    public function checkLowStockDb()
    {
        try {
            // Include the LowStockAlert model
            require_once BASE_PATH . '/app/models/LowStockAlert.php';
            
            // Initialize models
            $inventoryModel = new Inventory();
            $alertModel = new LowStockAlert();
            
            // Get current low stock items
            $lowStockItems = $inventoryModel->getLowStockItems();
            
            $newAlerts = 0;
            foreach ($lowStockItems as $item) {
                // Check if an alert already exists for this item that hasn't been resolved
                if (!$alertModel->alertExistsForItem($item['id'])) {
                    // Create a new alert
                    $alertData = [
                        'item_id' => $item['id'],
                        'item_name' => $item['name'],
                        'current_quantity' => $item['quantity'],
                        'max_quantity' => $item['max_quantity'],
                        'unit' => $item['unit']
                    ];
                    
                    if ($alertModel->createAlert($alertData)) {
                        $newAlerts++;
                    }
                }
            }
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => "Checked low stock items. $newAlerts new alerts created.",
                'new_alerts' => $newAlerts
            ]);
        } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Error checking low stock items: ' . $e->getMessage()
        ]);
        }
    }
    
    public function checkAndSendAlerts()
    {
        try {
            // Include the LowStockAlert model and UserModel
            require_once BASE_PATH . '/app/models/LowStockAlert.php';
            require_once BASE_PATH . '/app/models/UserModel.php';
            require_once BASE_PATH . '/app/models/Inventory.php';
            
            // Initialize models
            $alertModel = new LowStockAlert();
            $userModel = new UserModel();
            $inventoryModel = new Inventory();
            
            // First, check for new low stock items and create alerts
            $lowStockItems = $inventoryModel->getLowStockItems();
            $newAlerts = 0;
            foreach ($lowStockItems as $item) {
                // Check if an alert already exists for this item that hasn't been resolved
                if (!$alertModel->alertExistsForItem($item['id'])) {
                    // Create a new alert
                    $alertData = [
                        'item_id' => $item['id'],
                        'item_name' => $item['name'],
                        'current_quantity' => $item['quantity'],
                        'max_quantity' => $item['max_quantity'],
                        'unit' => $item['unit']
                    ];
                    
                    if ($alertModel->createAlert($alertData)) {
                        $newAlerts++;
                    }
                }
            }
            
            // Then, get pending alerts (including any new ones we just created)
            // Filter out alerts that have been resolved
            $allPendingAlerts = $alertModel->getPendingAlerts();
            $pendingAlerts = [];
            foreach ($allPendingAlerts as $alert) {
                // Only include alerts that haven't been resolved
                if ($alert['resolved'] == 0) {
                    $pendingAlerts[] = $alert;
                }
            }
            
            $sentCount = 0;
            if (!empty($pendingAlerts)) {
                // Get admin users
                $admins = $userModel->getUsersByType('Admin');
                
                if (!empty($admins)) {
                    // Group alerts by item for better email formatting
                    $items = [];
                    foreach ($pendingAlerts as $alert) {
                        $items[] = $alert;
                    }
                    
                    // Prepare email content
                    $subject = 'Low Stock Alert - LUNA Inventory System';
                    $body = '<h2>Low Stock Alert</h2>';
                    $body .= '<p>The following items in your inventory are running low:</p>';
                    $body .= '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">';
                    $body .= '<tr><th>Name</th><th>Current Quantity</th><th>Max Quantity</th><th>Percentage</th></tr>';
                    
                    foreach ($items as $item) {
                        $percentage = ($item['max_quantity'] > 0) ? round(($item['current_quantity'] / $item['max_quantity']) * 100, 2) : 0;
                        $body .= '<tr>';
                        $body .= '<td>' . htmlspecialchars($item['item_name']) . '</td>';
                        $body .= '<td>' . htmlspecialchars($item['current_quantity']) . ' ' . htmlspecialchars($item['unit']) . '</td>';
                        $body .= '<td>' . htmlspecialchars($item['max_quantity']) . ' ' . htmlspecialchars($item['unit']) . '</td>';
                        $body .= '<td>' . $percentage . '%</td>';
                        $body .= '</tr>';
                    }
                    
                    $body .= '</table>';
                    $body .= '<p>Please restock these items as soon as possible.</p>';
                    $body .= '<p><a href="http://localhost/inventory">View Inventory</a></p>';
                    
                    // Send email to each admin
                    $emailHelper = new EmailHelper();
                    
                    foreach ($admins as $admin) {
                        $result = $emailHelper->send($admin['email'], $subject, $body);
                        if ($result['success']) {
                            $sentCount++;
                            error_log("Low stock alert email sent successfully to: " . $admin['email']);
                        } else {
                            error_log("Failed to send low stock alert email to: " . $admin['email'] . ". Error: " . $result['message']);
                        }
                    }
                    
                    // Only mark alerts as sent if at least one email was successfully sent
                    if ($sentCount > 0) {
                        foreach ($pendingAlerts as $alert) {
                            $alertModel->markAsSent($alert['id']);
                        }
                    }
                }
            }
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'sent_count' => $sentCount,
                'new_alerts' => $newAlerts,
                'message' => "$sentCount alerts sent, $newAlerts new alerts created"
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Error checking and sending alerts: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Auto-update alert resolution status
     * Called via AJAX every 3 seconds to check inventory status and mark alerts as resolved
     */
    public function autoUpdateAlerts()
    {
        try {
            // Include the LowStockAlert model
            require_once BASE_PATH . '/app/models/LowStockAlert.php';
            
            // Initialize the alert model
            $alertModel = new LowStockAlert();
            
            // Automatically update alert resolution status
            $updatedCount = $alertModel->autoUpdateAlertResolutionStatus();
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'updated_count' => $updatedCount,
                'message' => "$updatedCount alerts updated"
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Error updating alerts: ' . $e->getMessage()
            ]);
        }
    }
}