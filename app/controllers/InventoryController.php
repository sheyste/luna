<?php
require BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/app/config.php';

class InventoryController extends Controller {
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
        $result = $this->conn->query("SELECT * FROM inventory");
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        $this->loadView('inventory.php', ['items' => $items]);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $sku = str_replace('.', '', microtime(true));
            $quantity = intval($_POST['quantity']);
            $unit = $_POST['unit'];

            $stmt = $this->conn->prepare("INSERT INTO inventory (name, sku, quantity, unit) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssis", $name, $sku, $quantity, $unit);
            $stmt->execute();
            $stmt->close();

            header('Location: /inventory');
            exit;
        }
    }

    public function edit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id']);
            $name = $_POST['name'];
            $quantity = intval($_POST['quantity']);
            $unit = $_POST['unit'];

            $stmt = $this->conn->prepare("UPDATE inventory SET name=?, quantity=?, unit=? WHERE id=?");
            $stmt->bind_param("sisi", $name, $quantity, $unit, $id);
            $stmt->execute();
            $stmt->close();

            header('Location: /inventory');
            exit;
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id']);
            $stmt = $this->conn->prepare("DELETE FROM inventory WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();

            header('Location: /inventory');
            exit;
        }
    }

    public function load() {
        $result = $this->conn->query("SELECT * FROM inventory");
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        header('Content-Type: application/json');
        echo json_encode($items);
    }

    public function getDetail() {
        $id = intval($_GET['id'] ?? 0);
        $stmt = $this->conn->prepare("SELECT * FROM inventory WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $item = $result->fetch_assoc();
        $stmt->close();

        header('Content-Type: application/json');
        echo json_encode($item);
    }

    public function search() {
        $q = $this->conn->real_escape_string($_GET['q'] ?? '');
        $result = $this->conn->query("SELECT * FROM inventory WHERE name LIKE '%$q%' OR sku LIKE '%$q%'");
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        header('Content-Type: application/json');
        echo json_encode($items);
    }

    public function export() {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename=inventory.csv');
        $result = $this->conn->query("SELECT * FROM inventory");
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Name', 'SKU', 'Quantity', 'Unit', 'Created At']);
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, $row);
        }
        fclose($output);
    }

    public function import() {
        // Example: handle CSV upload via $_FILES['file']
        // Not implemented for brevity
        echo "Import not implemented.";
    }

    public function print() {
        // Example: render printable inventory list
        $result = $this->conn->query("SELECT * FROM inventory");
        include_once __DIR__ . '/../views/inventory_print.php';
    }

    public function printable() {
        // Show printable view
        $this->print();
    }

    public function printableView() {
        $this->print();
    }

    public function printableExport() {
        $this->export();
    }

    public function printableImport() {
        $this->import();
    }

    public function printableDelete() {
        // Example: delete all inventory (be careful!)
        $this->conn->query("DELETE FROM inventory");
        header('Location: /inventory');
        exit;
    }
}
