<?php
require BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/app/config.php';

class ProductionController extends Controller {
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
    $sql = "
        SELECT p.id, p.menu_id, p.barcode, p.quantity_produced, p.quantity_available, p.quantity_sold, p.created_at,
               m.name AS menu_name
        FROM production p
        JOIN menus m ON p.menu_id = m.id
        ORDER BY p.created_at DESC
    ";
    $result = $this->conn->query($sql);
    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }

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
            INSERT INTO production (menu_id, barcode, quantity_produced, quantity_available, quantity_sold)
            VALUES (?, ?, ?, ?, 0)
        ");
        $stmt->bind_param("isii", $menu_id, $barcode, $quantity_produced, $quantity_produced);
        $stmt->execute();
        $stmt->close();


        // Deduct ingredients from inventory
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

            $updateStmt = $this->conn->prepare("
                UPDATE inventory
                SET quantity = GREATEST(quantity - ?, 0)
                WHERE id = ?
            ");
            $updateStmt->bind_param("di", $deduct_qty, $inventory_id);
            $updateStmt->execute();
            $updateStmt->close();
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

}