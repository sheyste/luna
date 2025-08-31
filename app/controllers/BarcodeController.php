<?php
/**
 * Controller for Barcode Scanner page
 */
require BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/app/config.php';

class BarcodeController extends Controller
{
    private $conn;

    public function __construct() {
        parent::__construct();
        $this->checkAuth();
        $this->conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);
        if ($this->conn->connect_error) {
            die('Database connection failed: ' . $this->conn->connect_error);
        }
    }

    public function index()
    {
        // Get all inventory items for barcode lookup
        $inventoryItems = [];
        $result = $this->conn->query("SELECT id, name, barcode, quantity, unit, price FROM inventory ORDER BY name ASC");
        while ($row = $result->fetch_assoc()) {
            $inventoryItems[] = $row;
        }

        // Get all menu items for barcode lookup
        $menuItems = [];
        $result = $this->conn->query("SELECT id, name, barcode, price FROM menus ORDER BY name ASC");
        while ($row = $result->fetch_assoc()) {
            $menuItems[] = $row;
        }

        $this->view('barcode', [
            'inventoryItems' => $inventoryItems,
            'menuItems' => $menuItems
        ]);
    }
}