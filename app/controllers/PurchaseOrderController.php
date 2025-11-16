<?php
require BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/app/config.php';

class PurchaseOrderController extends Controller {
    private $conn;

    public function __construct() {
        parent::__construct();
        $this->checkAuth();
        $this->conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);
        if ($this->conn->connect_error) {
            die('Database connection failed: ' . $this->conn->connect_error);
        }
    }

    public function index() {
        $orders = [];
        // This query creates a row for each item in a purchase order,
        // which is what the multi-item view expects.
        $sql = "
            SELECT
                po.id, po.po_number, po.supplier, po.order_date, po.expected_delivery, po.status, po.created_at,
                poi.id as item_id, poi.quantity, poi.unit_price, poi.received_quantity,
                i.name as item_name, i.id as inventory_id, i.unit
            FROM purchase_orders po
            JOIN purchase_order_items poi ON po.id = poi.purchase_order_id
            JOIN inventory i ON poi.inventory_id = i.id
            ORDER BY po.created_at DESC, po.id DESC
        ";
        $result = $this->conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }

        $inventory = [];
        $invResult = $this->conn->query("SELECT id, name, unit, price, max_quantity, quantity FROM inventory ORDER BY name ASC");
        while ($row = $invResult->fetch_assoc()) {
            $inventory[] = $row;
        }

        $this->view('purchase_orders', [
            'orders' => $orders,
            'inventory' => $inventory
        ]);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->conn->begin_transaction();
            try {
                $po_number = $_POST['po_number'];
                $supplier = $_POST['supplier'];
                $order_date = $_POST['order_date'];
                $expected_delivery = !empty($_POST['expected_delivery']) ? $_POST['expected_delivery'] : null;
                $status = trim($_POST['status']);

                $stmt = $this->conn->prepare("
                    INSERT INTO purchase_orders (po_number, supplier, order_date, expected_delivery, status)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->bind_param("sssss", $po_number, $supplier, $order_date, $expected_delivery, $status);
                $stmt->execute();
                $po_id = $this->conn->insert_id;
                $stmt->close();

                $items = $_POST['items'] ?? [];
                $item_stmt = $this->conn->prepare("
                    INSERT INTO purchase_order_items (purchase_order_id, inventory_id, quantity, unit_price)
                    VALUES (?, ?, ?, ?)
                ");
                foreach ($items as $item) {
                    if (empty($item['inventory_id'])) continue;
                    $inventory_id = intval($item['inventory_id']);
                    $quantity = intval($item['quantity']);
                    // If unit_price not provided, get it from inventory
                    $unit_price = isset($item['unit_price']) && $item['unit_price'] !== '' ? floatval($item['unit_price']) : null;
                    if ($unit_price === null || $unit_price <= 0) {
                        $price_stmt = $this->conn->prepare("SELECT price FROM inventory WHERE id = ?");
                        $price_stmt->bind_param("i", $inventory_id);
                        $price_stmt->execute();
                        $price_result = $price_stmt->get_result();
                        $price_row = $price_result->fetch_assoc();
                        $unit_price = floatval($price_row['price'] ?? 0.00);
                        $price_stmt->close();
                    }
                    $item_stmt->bind_param("iiid", $po_id, $inventory_id, $quantity, $unit_price);
                    $item_stmt->execute();
                }
                $item_stmt->close();

                $this->conn->commit();
            } catch (Exception $e) {
                $this->conn->rollback();
                die('Failed to add purchase order: ' . $e->getMessage());
            }

            header('Location: /purchase_order');
            exit;
        }
    }

    public function edit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->conn->begin_transaction();
            try {
                $po_id = intval($_POST['id']);

                // Fetch the original PO to get the old status before making changes
                $old_status_stmt = $this->conn->prepare("SELECT status FROM purchase_orders WHERE id = ?");
                $old_status_stmt->bind_param("i", $po_id);
                $old_status_stmt->execute();
                $old_order = $old_status_stmt->get_result()->fetch_assoc();
                $old_status_stmt->close();
                if (!$old_order) {
                    throw new Exception("Purchase Order not found.");
                }
                $old_status = $old_order['status'];

                $po_number = $_POST['po_number'];
                $supplier = $_POST['supplier'];
                $order_date = $_POST['order_date'];
                $expected_delivery = !empty($_POST['expected_delivery']) ? $_POST['expected_delivery'] : null;
                $status = trim($_POST['status']);
                $is_received = ($status === 'Received');

                $stmt = $this->conn->prepare("
                    UPDATE purchase_orders
                    SET po_number = ?, supplier = ?, order_date = ?, expected_delivery = ?, status = ?
                    WHERE id = ?
                ");
                $stmt->bind_param("sssssi", $po_number, $supplier, $order_date, $expected_delivery, $status, $po_id);
                $stmt->execute();
                $stmt->close();

                if (isset($_POST['deleted_items']) && is_array($_POST['deleted_items'])) {
                    $delete_ids_safe = array_map('intval', $_POST['deleted_items']);
                    if (!empty($delete_ids_safe)) {
                        $delete_placeholders = implode(',', array_fill(0, count($delete_ids_safe), '?'));
                        $delete_stmt = $this->conn->prepare("DELETE FROM purchase_order_items WHERE id IN ($delete_placeholders)");
                        $delete_stmt->bind_param(str_repeat('i', count($delete_ids_safe)), ...$delete_ids_safe);
                        $delete_stmt->execute();
                        $delete_stmt->close();
                    }
                }

                $items = $_POST['items'] ?? [];
                $update_stmt = $this->conn->prepare("
                    UPDATE purchase_order_items 
                    SET inventory_id = ?, quantity = ?, unit_price = ?, received_quantity = ?
                    WHERE id = ?
                ");
                $insert_stmt = $this->conn->prepare("
                    INSERT INTO purchase_order_items (purchase_order_id, inventory_id, quantity, unit_price, received_quantity)
                    VALUES (?, ?, ?, ?, ?)
                ");

                foreach ($items as $key => $item) {
                    if (empty($item['inventory_id'])) continue;
                    $inventory_id = intval($item['inventory_id']);
                    $quantity = intval($item['quantity']);
                    $unit_price = floatval($item['unit_price']);
                    $received_quantity = ($is_received && isset($item['received_quantity']) && $item['received_quantity'] !== '') ? floatval($item['received_quantity']) : null;

                    if (is_numeric($key)) {
                        $item_id = intval($key);
                        $update_stmt->bind_param("iiddi", $inventory_id, $quantity, $unit_price, $received_quantity, $item_id);
                        $update_stmt->execute();
                    } elseif (strpos($key, 'new_') === 0) {
                        $insert_stmt->bind_param("iiidd", $po_id, $inventory_id, $quantity, $unit_price, $received_quantity);
                        $insert_stmt->execute();
                    }
                }
                $update_stmt->close();
                $insert_stmt->close();

                // If the status has just been changed to 'Received', update inventory stock, purchase_date and price
                if ($is_received && $old_status !== 'Received') {
                    // Include Inventory and LowStockAlert models
                    require_once BASE_PATH . '/app/models/Inventory.php';
                    require_once BASE_PATH . '/app/models/LowStockAlert.php';
                    
                    $update_inventory_stmt = $this->conn->prepare(
                        "UPDATE inventory SET quantity = quantity + ?, purchase_date = ?, price = ? WHERE id = ?"
                    );
                    $current_date = date('Y-m-d');
                    foreach ($items as $item) {
                        if (empty($item['inventory_id']) || !isset($item['received_quantity']) || $item['received_quantity'] === '') {
                            continue;
                        }
                        $received_qty = floatval($item['received_quantity']);
                        $inv_id = intval($item['inventory_id']);
                        $unit_price = floatval($item['unit_price']);

                        if ($received_qty > 0) {
                            // Get current inventory item before updating
                            $inventoryModel = new Inventory();
                            $currentItem = $inventoryModel->getItemById($inv_id);
                            
                            $update_inventory_stmt->bind_param("dssi", $received_qty, $current_date, $unit_price, $inv_id);
                            $update_inventory_stmt->execute();
                            
                            // Update alert resolution status based on current inventory status
                            if ($currentItem) {
                                $alertModel = new LowStockAlert();
                                
                                // Calculate new quantity
                                $newQuantity = $currentItem['quantity'] + $received_qty;
                                $maxQuantity = $currentItem['max_quantity'];
                                
                                // Check if item is currently low stock
                                $isLowStock = ($newQuantity / $maxQuantity) <= 0.2;
                                
                                // Get all pending alerts for this item and update their resolution status
                                $pendingAlerts = $alertModel->getPendingAlerts();
                                foreach ($pendingAlerts as $alert) {
                                    if ($alert['item_id'] == $inv_id) {
                                        // Update the resolved status based on current inventory status
                                        $alertModel->updateAlertResolutionStatus($alert['id'], !$isLowStock);
                                    }
                                }
                            }
                        }
                    }
                    $update_inventory_stmt->close();
                }

                $this->conn->commit();
            } catch (Exception $e) {
                $this->conn->rollback();
                die('Failed to edit purchase order: ' . $e->getMessage());
            }

            header('Location: /purchase_order');
            exit;
        }
    }

    public function get() {
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing ID']);
            exit;
        }

        $id = intval($_GET['id']);

        $stmt = $this->conn->prepare("SELECT *, updated_at FROM purchase_orders WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();
        $stmt->close();

        if (!$order) {
            http_response_code(404);
            echo json_encode(['error' => 'Purchase Order not found']);
            exit;
        }

        $items = [];
        $item_stmt = $this->conn->prepare("
            SELECT poi.*, i.name AS item_name, i.unit
            FROM purchase_order_items poi
            JOIN inventory i ON poi.inventory_id = i.id
            WHERE poi.purchase_order_id = ?
        ");
        $item_stmt->bind_param("i", $id);
        $item_stmt->execute();
        $item_result = $item_stmt->get_result();
        while ($item = $item_result->fetch_assoc()) {
            $item['unit_price'] = number_format($item['unit_price'], 2, '.', '');
            $item['total_amount'] = number_format($item['quantity'] * $item['unit_price'], 2, '.', '');
            $item['received_quantity'] = $item['received_quantity'];
            $items[] = $item;
        }
        $item_stmt->close();

        $order['order_date'] = date('Y-m-d', strtotime($order['order_date']));
        $order['expected_delivery'] = $order['expected_delivery'] ? date('Y-m-d', strtotime($order['expected_delivery'])) : '';
        $order['items'] = $items;

        header('Content-Type: application/json');
        echo json_encode($order);
    }

    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input || !isset($input['id']) || !isset($input['status'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            exit;
        }

        $id = intval($input['id']);
        $newStatus = trim($input['status']);

        // Validate status
        $validStatuses = ['Pending', 'Ordered', 'Received', 'Cancelled'];
        if (!in_array($newStatus, $validStatuses)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid status']);
            exit;
        }

        $this->conn->begin_transaction();

        try {
            // Fetch the current order to get the old status
            $stmt = $this->conn->prepare("SELECT status FROM purchase_orders WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $order = $result->fetch_assoc();
            $stmt->close();

            if (!$order) {
                throw new Exception("Purchase Order not found");
            }

            $oldStatus = $order['status'];

            // If status is changing to 'Received' and wasn't previously 'Received',
            // we need to update inventory quantities. For simplicity, we'll assume
            // all quantities are received for this status update.
            if ($newStatus === 'Received' && $oldStatus !== 'Received') {
                // Include Inventory and LowStockAlert models
                require_once BASE_PATH . '/app/models/Inventory.php';
                require_once BASE_PATH . '/app/models/LowStockAlert.php';

                // Fetch all items for this PO
                $items_stmt = $this->conn->prepare("
                    SELECT poi.inventory_id, poi.quantity, poi.unit_price
                    FROM purchase_order_items poi
                    WHERE poi.purchase_order_id = ?
                ");
                $items_stmt->bind_param("i", $id);
                $items_stmt->execute();
                $items_result = $items_stmt->get_result();

                $update_inventory_stmt = $this->conn->prepare(
                    "UPDATE inventory SET quantity = quantity + ?, purchase_date = ?, price = ? WHERE id = ?"
                );
                $current_date = date('Y-m-d');

                while ($item = $items_result->fetch_assoc()) {
                    $received_qty = floatval($item['quantity']); // Assume full quantity received
                    $inv_id = intval($item['inventory_id']);
                    $unit_price = floatval($item['unit_price']);

                    if ($received_qty > 0) {
                        // Get current inventory item before updating
                        $inventoryModel = new Inventory();
                        $currentItem = $inventoryModel->getItemById($inv_id);

                        $update_inventory_stmt->bind_param("dssi", $received_qty, $current_date, $unit_price, $inv_id);
                        $update_inventory_stmt->execute();

                        // Update alert resolution status based on current inventory status
                        if ($currentItem) {
                            $alertModel = new LowStockAlert();

                            // Calculate new quantity
                            $newQuantity = $currentItem['quantity'] + $received_qty;
                            $maxQuantity = $currentItem['max_quantity'];

                            // Check if item is currently low stock
                            $isLowStock = ($newQuantity / $maxQuantity) <= 0.2;

                            // Get all pending alerts for this item and update their resolution status
                            $pendingAlerts = $alertModel->getPendingAlerts();
                            foreach ($pendingAlerts as $alert) {
                                if ($alert['item_id'] == $inv_id) {
                                    // Update the resolved status based on current inventory status
                                    $alertModel->updateAlertResolutionStatus($alert['id'], !$isLowStock);
                                }
                            }
                        }
                    }
                }
                $update_inventory_stmt->close();
                $items_stmt->close();

                // Update the purchase_order_items to set received_quantity
                $update_items_stmt = $this->conn->prepare("
                    UPDATE purchase_order_items
                    SET received_quantity = quantity
                    WHERE purchase_order_id = ?
                ");
                $update_items_stmt->bind_param("i", $id);
                $update_items_stmt->execute();
                $update_items_stmt->close();
            }

            // Update the purchase order status
            $update_stmt = $this->conn->prepare("UPDATE purchase_orders SET status = ? WHERE id = ?");
            $update_stmt->bind_param("si", $newStatus, $id);
            $update_stmt->execute();
            $update_stmt->close();

            $this->conn->commit();

            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'status' => $newStatus]);
            exit;

        } catch (Exception $e) {
            $this->conn->rollback();
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }

        if (!isset($_POST['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing ID']);
            exit;
        }

        $id = intval($_POST['id']);
        $this->conn->begin_transaction();

        try {
            // Fetch the order to check status
            $stmt = $this->conn->prepare("SELECT status FROM purchase_orders WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $order = $result->fetch_assoc();
            $stmt->close();

            if (!$order) {
                throw new Exception("Purchase Order not found");
            }

            // Delete items
            $delete_items_stmt = $this->conn->prepare("DELETE FROM purchase_order_items WHERE purchase_order_id = ?");
            $delete_items_stmt->bind_param("i", $id);
            $delete_items_stmt->execute();
            $delete_items_stmt->close();

            // Delete order
            $delete_order_stmt = $this->conn->prepare("DELETE FROM purchase_orders WHERE id = ?");
            $delete_order_stmt->bind_param("i", $id);
            $delete_order_stmt->execute();
            $delete_order_stmt->close();

            $this->conn->commit();

            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;

        } catch (Exception $e) {
            $this->conn->rollback();
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }
}
