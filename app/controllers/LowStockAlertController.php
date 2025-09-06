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
                // Check if we should create an alert for this item
                if ($this->shouldCreateAlert($alertModel, $item['id'])) {
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
                // Check if we should create an alert for this item
                // Use a more robust check that considers recently sent alerts
                if ($this->shouldCreateAlert($alertModel, $item['id'])) {
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
            $result = $alertModel->getPendingAlertsWithLock();
            $allPendingAlerts = $result['alerts'];
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
                    
                    // Modern React-inspired email template
                    $body = '
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Low Stock Alert</title>
                        <style>
                            /* Reset styles */
                            body, html {
                                margin: 0;
                                padding: 0;
                                font-family: \'Inter\', \'Segoe UI\', Roboto, Helvetica, Arial, sans-serif;
                                line-height: 1.6;
                                color: #333;
                                background-color: #f8f9fa;
                            }
                            
                            /* Container */
                            .email-container {
                                max-width: 800px;
                                margin: 0 auto;
                                background-color: #ffffff;
                                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                                border-radius: 8px;
                                overflow: hidden;
                            }
                            
                            /* Header */
                            .email-header {
                                background: linear-gradient(135deg, #293145 0%, #1f2639 100%);
                                color: white;
                                padding: 30px 20px;
                                text-align: center;
                            }
                            
                            .logo {
                                font-size: 28px;
                                font-weight: 700;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                gap: 12px;
                            }
                            
                            .logo-icon {
                                width: 36px;
                                height: 36px;
                                background-color: #3498db;
                                border-radius: 8px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                            }
                            
                            .logo-text {
                                letter-spacing: 1px;
                            }
                            
                            
                            /* Content */
                            .email-content {
                                padding: 30px;
                            }
                            
                            .content-title {
                                color: #293145;
                                font-size: 20px;
                                margin-top: 0;
                                margin-bottom: 20px;
                                font-weight: 600;
                            }
                            
                            .content-text {
                                color: #555;
                                font-size: 16px;
                                margin-bottom: 25px;
                            }
                            
                            /* Card Styles */
                            .items-container {
                                margin: 25px 0;
                            }
                            
                            .item-card {
                                background-color: #ffffff;
                                border: 1px solid #e2e8f0;
                                border-radius: 8px;
                                margin-bottom: 15px;
                                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
                                overflow: hidden;
                            }
                            
                            .item-card:last-child {
                                margin-bottom: 0;
                            }
                            
                            .card-header {
                                background-color: #f1f5f9;
                                padding: 15px 20px;
                                border-bottom: 1px solid #e2e8f0;
                            }
                            
                            .item-name {
                                font-weight: 600;
                                color: #293145;
                                margin: 0;
                                font-size: 18px;
                            }
                            
                            .card-body {
                                padding: 20px;
                            }
                            
                            .data-row {
                                display: flex;
                                justify-content: space-between;
                                margin-bottom: 12px;
                                padding-bottom: 12px;
                                border-bottom: 1px solid #f1f5f9;
                            }
                            
                            .data-row:last-child {
                                margin-bottom: 0;
                                padding-bottom: 0;
                                border-bottom: none;
                            }
                            
                            .data-label {
                                font-weight: 500;
                                color: #64748b;
                                flex-grow: 1;
                            }
                            
                            .data-value {
                                font-weight: 600;
                                text-align: right;
                                margin-left: auto;
                                padding-left: 10px;
                                white-space: nowrap;
                            }
                            
                            .quantity-low {
                                color: #dc2626;
                            }
                            
                            .quantity-max {
                                color: #0891b2;
                            }
                            
                            .percentage {
                                font-weight: 600;
                            }
                            
                            .percentage.critical {
                                color: #dc2626;
                            }
                            
                            .percentage.warning {
                                color: #ea580c;
                            }
                            
                            .percentage.ok {
                                color: #16a34a;
                            }
                            
                            /* Call to Action */
                            .cta-section {
                                text-align: center;
                                margin: 30px 0;
                                padding: 20px;
                                background-color: #f1f5f9;
                                border-radius: 8px;
                            }
                            
                            .cta-button {
                                color: white;
                                display: inline-block;
                                background: linear-gradient(135deg, #ff7e5f 0%, #feb47b 100%);
                                text-decoration: none;
                                padding: 14px 32px;
                                border-radius: 6px;
                                font-weight: 600;
                                font-size: 16px;
                                transition: all 0.3s ease;
                                box-shadow: 0 4px 6px rgba(255, 126, 95, 0.2);
                            }

                            .cta-button:hover {
                                color: white;
                                background: linear-gradient(135deg, #feb47b 0%, #ff7e5f 100%);
                                transform: translateY(-2px);
                                box-shadow: 0 6px 8px rgba(255, 126, 95, 0.3);
                            }

                            
                            /* Footer */
                            .email-footer {
                                background-color: #f1f5f9;
                                padding: 25px 30px;
                                text-align: center;
                                color: #64748b;
                                font-size: 14px;
                            }
                            
                            .footer-text {
                                margin: 0 0 15px;
                            }
                            
                            .footer-links {
                                margin-top: 15px;
                            }
                            
                            .footer-link {
                                color: #3498db;
                                text-decoration: none;
                                margin: 0 10px;
                                font-weight: 500;
                            }
                            
                            .footer-link:hover {
                                text-decoration: underline;
                            }
                            
                            /* Responsive styles */
                            @media (max-width: 600px) {
                                .email-container {
                                    border-radius: 0;
                                }
                                
                                .email-content {
                                    padding: 20px 15px;
                                }
                                
                                .table-header th,
                                .table-cell {
                                    padding: 12px 15px;
                                    font-size: 14px;
                                }
                                
                                .logo {
                                    font-size: 24px;
                                }
                                
                                .header-title {
                                    font-size: 18px;
                                }
                                
                                .content-title {
                                    font-size: 18px;
                                }
                                
                                .cta-button {
                                    padding: 12px 24px;
                                    font-size: 15px;
                                    color: white;
                                }
                            }
                        </style>
                    </head>
                    <body>
                        <div class="email-container">
                            <!-- Email Header -->
                            <div class="email-header">
                                <div class="logo">
                                    <div class="logo-text">LUNA</div>
                                </div>
                            </div>
                            
                            <!-- Email Content -->
                            <div class="email-content">
                                <h2 class="content-title">Inventory Items Running Low</h2>
                                <p class="content-text">The following items in your inventory are running low and require restocking:</p>
                                
                                <!-- Items Container -->
                                <div class="items-container">';
                                    
                    foreach ($items as $item) {
                        $quantity = (float)($item['current_quantity'] ?? 0);
                        $max_quantity = (float)($item['max_quantity'] ?? 0);
                        $percentage = ($max_quantity > 0) ? round(($quantity / $max_quantity) * 100, 2) : 0;
                        
                        // Determine percentage color class
                        $percentageClass = 'ok';
                        if ($percentage <= 20) {
                            $percentageClass = 'critical';
                        } elseif ($percentage <= 50) {
                            $percentageClass = 'warning';
                        }
                        
                        $body .= '<div class="item-card">';
                        $body .= '<div class="card-header">';
                        $body .= '<h3 class="item-name">' . htmlspecialchars($item['item_name']) . '</h3>';
                        $body .= '</div>';
                        $body .= '<div class="card-body">';
                        $body .= '<div class="data-row">';
                        $body .= '<span class="data-label">Current Quantity:</span>';
                        $body .= '<span class="data-value quantity-low">' . htmlspecialchars($item['current_quantity']) . ' ' . htmlspecialchars($item['unit']) . '</span>';
                        $body .= '</div>';
                        $body .= '<div class="data-row">';
                        $body .= '<span class="data-label">Max Quantity:</span>';
                        $body .= '<span class="data-value quantity-max">' . htmlspecialchars($item['max_quantity']) . ' ' . htmlspecialchars($item['unit']) . '</span>';
                        $body .= '</div>';
                        $body .= '<div class="data-row">';
                        $body .= '<span class="data-label">Percentage:</span>';
                        $body .= '<span class="data-value percentage ' . $percentageClass . '">' . $percentage . '%</span>';
                        $body .= '</div>';
                        $body .= '</div>';
                        $body .= '</div>';
                    }
                    
                    $body .= '
                                </div>
                                
                                <!-- Call to Action -->
                                <div class="cta-section">
                                    <p>Please restock these items as soon as possible to maintain inventory levels.</p>
                                    <a href="http://localhost/inventory" class="cta-button">View Inventory</a>
                                </div>
                            </div>
                            
                            <!-- Email Footer -->
                            <div class="email-footer">
                                <p class="footer-text">This is an automated message from the LUNA Inventory System.</p>
                                <p class="footer-text">You are receiving this email because you are registered as an administrator.</p>
                            </div>
                        </div>
                    </body>
                    </html>';
                    
                    // Send email to each admin via SMTP
                    $emailHelper = new EmailHelper();
                    
                    foreach ($admins as $admin) {
                        $result = $emailHelper->send($admin['email'], $subject, $body);
                        if ($result['success']) {
                            $sentCount++;
                            error_log("Low stock alert email sent successfully via SMTP to: " . $admin['email'] .
                                     (isset($result['method']) ? " (Method: " . $result['method'] . ")" : ""));
                        } else {
                            error_log("Failed to send low stock alert email via SMTP to: " . $admin['email'] . ". Error: " . $result['message']);
                        }
                    }
                    
                    // Only mark alerts as sent if at least one email was successfully sent
                    if ($sentCount > 0) {
                        foreach ($pendingAlerts as $alert) {
                            $alertModel->markAsSent($alert['id']);
                        }
                    }
                    
                }
            
            // Commit the transaction
            $alertModel->commitTransaction();
            }
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'sent_count' => $sentCount,
                'new_alerts' => $newAlerts,
                'message' => "$sentCount alerts sent, $newAlerts new alerts created"
            ]);
        } catch (Exception $e) {
            // Rollback the transaction if there's an error
            $alertModel->rollbackTransaction();
            
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
    
    /**
     * Check if we should create an alert for an item
     * @param LowStockAlert $alertModel
     * @param int $itemId
     * @return bool True if we should create an alert (no recent alert exists), false if we should NOT create one
     */
    private function shouldCreateAlert($alertModel, $itemId) {
        // Check if there's a recent alert for this item that hasn't been resolved
        // We'll consider alerts from the last 24 hours to prevent duplicate alerts
        $stmt = $alertModel->db->prepare("
            SELECT COUNT(*) as count FROM low_stock_alerts
            WHERE item_id = :item_id
            AND (status = 'pending' OR status = 'sent')
            AND resolved = 0
            AND alert_date > DATE_SUB(NOW(), INTERVAL 24 HOUR)
        ");
        $stmt->execute(['item_id' => $itemId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // Return true if we should create an alert (no recent alert exists)
        return $result['count'] == 0;
    }
    
    /**
     * Test SMTP connection and configuration
     * This method helps debug email sending issues
     */
    public function testSMTPConnection()
    {
        try {
            $emailHelper = new EmailHelper();
            $result = $emailHelper->testConnection();
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => $result['success'],
                'message' => $result['message'],
                'smtp_host' => getenv('SMTP_HOST') ?: 'Not configured',
                'smtp_port' => getenv('SMTP_PORT') ?: 'Not configured',
                'smtp_security' => getenv('SMTP_SECURITY') ?: 'Not configured',
                'from_email' => getenv('FROM_EMAIL') ?: 'Not configured'
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'SMTP test failed: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Send a test email to verify SMTP configuration
     * This method sends a test email to the specified recipient
     */
    public function sendTestEmail()
    {
        try {
            $to = $_POST['email'] ?? '';
            
            if (empty($to)) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Email address is required'
                ]);
                return;
            }
            
            if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid email address format'
                ]);
                return;
            }
            
            $emailHelper = new EmailHelper();
            $subject = 'LUNA Inventory System - SMTP Test Email';
            $body = '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>SMTP Test Email</title>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background-color: #293145; color: white; padding: 20px; text-align: center; }
                    .content { padding: 20px; background-color: #f9f9f9; }
                    .success { color: #16a34a; font-weight: bold; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h2>LUNA Inventory System</h2>
                    </div>
                    <div class="content">
                        <h3>SMTP Configuration Test</h3>
                        <p class="success">✅ Congratulations! Your SMTP configuration is working correctly.</p>
                        <p>This test email was sent successfully via SMTP to verify your email settings.</p>
                        <hr>
                        <p><strong>Test Details:</strong></p>
                        <ul>
                            <li>Sent at: ' . date('Y-m-d H:i:s') . '</li>
                            <li>SMTP Host: ' . (getenv('SMTP_HOST') ?: 'Not configured') . '</li>
                            <li>SMTP Port: ' . (getenv('SMTP_PORT') ?: 'Not configured') . '</li>
                            <li>Security: ' . (getenv('SMTP_SECURITY') ?: 'Not configured') . '</li>
                        </ul>
                    </div>
                </div>
            </body>
            </html>';
            
            $result = $emailHelper->send($to, $subject, $body);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => $result['success'],
                'message' => $result['message'],
                'recipient' => $to
            ]);
            
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Test email failed: ' . $e->getMessage()
            ]);
        }
    }
}