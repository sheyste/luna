<?php
require BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/app/models/Production.php';

class ProductionController extends Controller {
    private $conn;
    private $productionModel;

    public function __construct() {
        parent::__construct();
        $this->checkAuth();
        $this->conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);
        if ($this->conn->connect_error) {
            die('Database connection failed: ' . $this->conn->connect_error);
        }
        $this->productionModel = new Production();
    }

public function index() {
    // Use the new model to get items with calculated details
    $items = $this->productionModel->getAllWithDetails();
    
    $menuResult = $this->conn->query("SELECT id, name FROM menus ORDER BY name ASC");
    $menus = [];
    while ($row = $menuResult->fetch_assoc()) {
        $menus[] = $row;
    }

    $this->view('production', [
        'items' => $items,
        'menus' => $menus
    ]);
}

public function add() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $menu_id = intval($_POST['menu_id']);
        $quantity_produced = intval($_POST['quantity_produced']);
        $barcode = $_POST['barcode'] ?? '';

        // Insert new production entry
        $stmt = $this->conn->prepare("
            INSERT INTO production (menu_id, barcode, quantity_produced, quantity_available, quantity_sold, wastage)
            VALUES (?, ?, ?, ?, 0, 0)
        ");
        $stmt->bind_param("isii", $menu_id, $barcode, $quantity_produced, $quantity_produced);
        $stmt->execute();
        $stmt->close();


        // Deduct ingredients from inventory
        // Include Inventory and LowStockAlert models
        require_once BASE_PATH . '/app/models/Inventory.php';
        require_once BASE_PATH . '/app/models/LowStockAlert.php';
        
        $sql = "
            SELECT mi.inventory_id, mi.quantity
            FROM menu_ingredients mi
            WHERE mi.menu_id = ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $menu_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $inventory_id = $row['inventory_id'];
            $ingredient_qty = $row['quantity'];
            $deduct_qty = $ingredient_qty * $quantity_produced;

            // Get current inventory item before updating
            $inventoryModel = new Inventory();
            $currentItem = $inventoryModel->getItemById($inventory_id);
            
            $updateStmt = $this->conn->prepare("
                UPDATE inventory
                SET quantity = GREATEST(quantity - ?, 0)
                WHERE id = ?
            ");
            $updateStmt->bind_param("di", $deduct_qty, $inventory_id);
            $updateStmt->execute();
            $updateStmt->close();
            
            // Update alert resolution status based on current inventory status
            if ($currentItem) {
                $alertModel = new LowStockAlert();
                
                // Calculate new quantity
                $newQuantity = max($currentItem['quantity'] - $deduct_qty, 0);
                $maxQuantity = $currentItem['max_quantity'];
                
                // Check if item is currently low stock
                $isLowStock = ($newQuantity / $maxQuantity) <= 0.2;
                
                // Get all pending alerts for this item and update their resolution status
                $pendingAlerts = $alertModel->getPendingAlerts();
                foreach ($pendingAlerts as $alert) {
                    if ($alert['item_id'] == $inventory_id) {
                        // Update the resolved status based on current inventory status
                        $alertModel->updateAlertResolutionStatus($alert['id'], !$isLowStock);
                    }
                }
                
                // Check if we need to create a new alert
                if ($isLowStock && !$alertModel->alertExistsForItem($inventory_id)) {
                    // Create a new alert
                    $alertData = [
                        'item_id' => $inventory_id,
                        'item_name' => $currentItem['name'],
                        'current_quantity' => $newQuantity,
                        'max_quantity' => $maxQuantity,
                        'unit' => $currentItem['unit']
                    ];
                    $alertModel->createAlert($alertData);
                }
            }
        }
        $stmt->close();

        header('Location: /production');
        exit;
    }
}

public function edit() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = intval($_POST['id']);
        $menu_id = intval($_POST['menu_id']);
        $quantity_produced = intval($_POST['quantity_produced']);
        $quantity_sold = intval($_POST['quantity_sold']);
        $barcode = $_POST['barcode'] ?? '';

        // subtract sold from produced directly
        $new_quantity_produced = max($quantity_produced - $quantity_sold, 0);

        $stmt = $this->conn->prepare("
            UPDATE production
            SET menu_id=?, barcode=?, quantity_produced=?, quantity_sold=?
            WHERE id=?
        ");
        $stmt->bind_param("isiii", $menu_id, $barcode, $new_quantity_produced, $quantity_sold, $id);
        $stmt->execute();
        $stmt->close();

        header('Location: /production');
        exit;
    }
}

public function updateSold() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sold'])) {
        // The key is now menu_id
        foreach ($_POST['sold'] as $menu_id => $sold_input) {
            $menu_id = intval($menu_id);
            $sold_to_deduct = intval($sold_input);

            if ($sold_to_deduct <= 0) {
                continue;
            }

            // Get all production entries for this menu with available quantity, oldest first
            $stmt = $this->conn->prepare("
                SELECT id, quantity_available, quantity_sold
                FROM production
                WHERE menu_id = ? AND quantity_available > 0
                ORDER BY created_at ASC
            ");
            $stmt->bind_param("i", $menu_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $productions_to_update = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            foreach ($productions_to_update as $prod) {
                if ($sold_to_deduct <= 0) {
                    break;
                }

                $deduct_from_this_row = min($prod['quantity_available'], $sold_to_deduct);

                $new_available = $prod['quantity_available'] - $deduct_from_this_row;
                $new_sold = $prod['quantity_sold'] + $deduct_from_this_row;

                $updateStmt = $this->conn->prepare("
                    UPDATE production
                    SET quantity_sold = ?, quantity_available = ?
                    WHERE id = ?
                ");
                $updateStmt->bind_param("iii", $new_sold, $new_available, $prod['id']);
                $updateStmt->execute();
                $updateStmt->close();

                $sold_to_deduct -= $deduct_from_this_row;
            }
        }

        header('Location: /production');
        exit;
    }
}
public function updateWastage() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['wastage'])) {
        // The key is now menu_id
        foreach ($_POST['wastage'] as $menu_id => $wastage_input) {
            $menu_id = intval($menu_id);
            $wastage_to_add = intval($wastage_input);

            if ($wastage_to_add <= 0) {
                continue;
            }

            // Get all production entries for this menu with available quantity, oldest first
            $stmt = $this->conn->prepare("
                SELECT id, quantity_available, quantity_sold, wastage
                FROM production
                WHERE menu_id = ? AND quantity_available > 0
                ORDER BY created_at ASC
            ");
            $stmt->bind_param("i", $menu_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $productions_to_update = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            foreach ($productions_to_update as $prod) {
                if ($wastage_to_add <= 0) {
                    break;
                }

                // We can only add wastage up to the available quantity
                $add_to_this_row = min($prod['quantity_available'], $wastage_to_add);

                $new_available = $prod['quantity_available'] - $add_to_this_row;
                $new_wastage = $prod['wastage'] + $add_to_this_row;

                $updateStmt = $this->conn->prepare("
                    UPDATE production
                    SET wastage = ?, quantity_available = ?
                    WHERE id = ?
                ");
                $updateStmt->bind_param("iii", $new_wastage, $new_available, $prod['id']);
                $updateStmt->execute();
                $updateStmt->close();

                $wastage_to_add -= $add_to_this_row;
            }
        }

        header('Location: /production');
        exit;
    }
}
public function delete() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['ids'])) {
        $ids = array_map('intval', explode(',', $_POST['ids']));
        $ids = array_filter($ids, fn($id) => $id > 0);
        if (!empty($ids)) {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $this->conn->prepare("DELETE FROM production WHERE id IN ($placeholders)");
            $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
            $stmt->execute();
            $stmt->close();
        }
        header('Location: /production');
        exit;
    }
}
public function getDetail() {
    $id = intval($_GET['id'] ?? 0);
    $stmt = $this->conn->prepare("
        SELECT p.*, m.name AS menu_name, p.barcode
        FROM production p
        JOIN menus m ON p.menu_id = m.id
        WHERE p.id=?
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
    $stmt->close();

    header('Content-Type: application/json');
    echo json_encode($item);
}

public function getMenuIngredients() {
    header('Content-Type: application/json');
    
    if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !isset($_GET['menu_id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
        return;
    }
    
    $menu_id = intval($_GET['menu_id']);
    
    // Get menu ingredients with current inventory levels
    $sql = "
        SELECT
            mi.inventory_id,
            mi.quantity as required_quantity,
            i.name as ingredient_name,
            i.quantity as available_quantity,
            i.unit,
            i.price as unit_price
        FROM menu_ingredients mi
        LEFT JOIN inventory i ON mi.inventory_id = i.id
        WHERE mi.menu_id = ?
        ORDER BY i.name ASC
    ";
    
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $menu_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $ingredients = [];
    $canProduce = 0;
    $hasIngredients = false;
    
    while ($row = $result->fetch_assoc()) {
        $required = floatval($row['required_quantity']);
        $available = floatval($row['available_quantity']);
        
        if ($required > 0) {
            $maxPossible = floor($available / $required);
            if (!$hasIngredients || $maxPossible < $canProduce) {
                $canProduce = $maxPossible;
            }
            $hasIngredients = true;
        }
        
        $row['max_production'] = $required > 0 ? floor($available / $required) : 0;
        $row['sufficient'] = $available >= $required;
        $ingredients[] = $row;
    }
    
    $stmt->close();
    
    echo json_encode([
        'success' => true,
        'ingredients' => $ingredients,
        'max_production' => max(0, $canProduce)
    ]);
}
}