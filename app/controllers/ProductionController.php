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
        // Assuming a table named 'production'
        $result = $this->conn->query("SELECT * FROM production");
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        $this->view('production', ['items' => $items]);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $menu_name = $_POST['menu_name'];
            $quantity_produced = intval($_POST['quantity_produced']);
            $quantity_available = intval($_POST['quantity_available']);

            $stmt = $this->conn->prepare("INSERT INTO production (menu_name, quantity_produced, quantity_available) VALUES (?, ?, ?)");
            $stmt->bind_param("sii", $menu_name, $quantity_produced, $quantity_available);
            $stmt->execute();
            $stmt->close();

            header('Location: /production');
            exit;
        }
    }

    public function edit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id']);
            $menu_name = $_POST['menu_name'];
            $quantity_produced = intval($_POST['quantity_produced']);
            $quantity_available = intval($_POST['quantity_available']);

            $stmt = $this->conn->prepare("UPDATE production SET menu_name=?, quantity_produced=?, quantity_available=? WHERE id=?");
            $stmt->bind_param("siii", $menu_name, $quantity_produced, $quantity_available, $id);
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
        $stmt = $this->conn->prepare("SELECT * FROM production WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $item = $result->fetch_assoc();
        $stmt->close();

        header('Content-Type: application/json');
        echo json_encode($item);
    }
}