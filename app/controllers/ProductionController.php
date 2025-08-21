<?php
require BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/app/config.php';

class ProductionController extends Controller {
    private $conn;

    public function __construct() {
        parent::__construct();
        $this->checkAuth();
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if ($this->conn->connect_error) {
            die('Database connection failed: ' . $this->conn->connect_error);
        }
    }

    public function index() {
        $sql = "
            SELECT p.id, p.barcode, p.quantity_produced, p.quantity_available, p.created_at,
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

        // also fetch menus for dropdown
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
        $menu_id = intval($_POST['menu_id']); // now using ID instead of name
        $quantity_produced = intval($_POST['quantity_produced']);
        $quantity_available = intval($_POST['quantity_available']);
        $barcode = $_POST['barcode'] ?? '';

        $stmt = $this->conn->prepare("
            INSERT INTO production (menu_id, barcode, quantity_produced, quantity_available)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("isii", $menu_id, $barcode, $quantity_produced, $quantity_available);
        $stmt->execute();
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
        $quantity_available = intval($_POST['quantity_available']);
        $barcode = $_POST['barcode'] ?? '';

        $stmt = $this->conn->prepare("
            UPDATE production
            SET menu_id=?, barcode=?, quantity_produced=?, quantity_available=?
            WHERE id=?
        ");
        $stmt->bind_param("isiii", $menu_id, $barcode, $quantity_produced, $quantity_available, $id);
        $stmt->execute();
        $stmt->close();

        header('Location: /production');
        exit;
    }
}


    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id']);
            $stmt = $this->conn->prepare("DELETE FROM production WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();

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