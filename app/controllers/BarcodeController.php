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

        // Get all production items for barcode lookup
        $productionItems = [];
        $result = $this->conn->query("
            SELECT p.id, p.menu_id, p.barcode, p.quantity_produced, p.quantity_available,
                   p.quantity_sold, p.wastage, m.name AS menu_name, m.price
            FROM production p
            JOIN menus m ON p.menu_id = m.id
            WHERE p.barcode IS NOT NULL AND p.barcode != ''
            ORDER BY p.created_at DESC
        ");
        while ($row = $result->fetch_assoc()) {
            $productionItems[] = $row;
        }

        $this->view('barcode', [
            'inventoryItems' => $inventoryItems,
            'menuItems' => $menuItems,
            'productionItems' => $productionItems
        ]);
    }

    public function physicalCount()
    {
        $barcode = $_GET['barcode'] ?? '';
        $itemId = $_GET['item_id'] ?? '';
        
        if (empty($barcode) || empty($itemId)) {
            header('Location: /barcode');
            exit;
        }
        
        // Get inventory item details
        $result = $this->conn->query("SELECT id, name, barcode, quantity, unit, price FROM inventory WHERE id = " . intval($itemId));
        $item = $result->fetch_assoc();
        
        if (!$item) {
            header('Location: /barcode');
            exit;
        }
        
        $this->view('barcode_physical_count', [
            'item' => $item,
            'barcode' => $barcode
        ]);
    }

    public function productionActions()
    {
        $barcode = $_GET['barcode'] ?? '';
        $itemId = $_GET['item_id'] ?? '';
        
        if (empty($barcode) || empty($itemId)) {
            header('Location: /barcode');
            exit;
        }
        
        // Get production item details
        $result = $this->conn->query("
            SELECT p.id, p.menu_id, p.barcode, p.quantity_produced, p.quantity_available,
                   p.quantity_sold, p.wastage, m.name AS menu_name, m.price
            FROM production p
            JOIN menus m ON p.menu_id = m.id
            WHERE p.id = " . intval($itemId)
        );
        $item = $result->fetch_assoc();
        
        if (!$item) {
            header('Location: /barcode');
            exit;
        }
        
        $this->view('barcode_production_actions', [
            'item' => $item,
            'barcode' => $barcode
        ]);
    }

    public function menuActions()
    {
        $barcode = $_GET['barcode'] ?? '';
        $itemId = $_GET['item_id'] ?? '';
        
        if (empty($barcode) || empty($itemId)) {
            header('Location: /barcode');
            exit;
        }
        
        // Get menu item details
        $result = $this->conn->query("SELECT id, name, barcode, price FROM menus WHERE id = " . intval($itemId));
        $item = $result->fetch_assoc();
        
        if (!$item) {
            header('Location: /barcode');
            exit;
        }
        
        $this->view('barcode_menu_actions', [
            'item' => $item,
            'barcode' => $barcode
        ]);
    }

    public function addProduction()
    {
        $menuId = $_GET['menu_id'] ?? '';
        $barcode = $_GET['barcode'] ?? '';
        
        if (empty($menuId) || empty($barcode)) {
            header('Location: /barcode');
            exit;
        }
        
        // Get menu details
        $result = $this->conn->query("SELECT id, name, price FROM menus WHERE id = " . intval($menuId));
        $menu = $result->fetch_assoc();
        
        if (!$menu) {
            header('Location: /barcode');
            exit;
        }
        
        $this->view('barcode_add_production', [
            'menu' => $menu,
            'barcode' => $barcode
        ]);
    }

    public function updateSold()
    {
        $menuId = $_GET['menu_id'] ?? '';
        $barcode = $_GET['barcode'] ?? '';
        
        if (empty($menuId) || empty($barcode)) {
            header('Location: /barcode');
            exit;
        }
        
        // Get menu details and current production stats
        $result = $this->conn->query("
            SELECT m.id, m.name, m.price,
                   SUM(p.quantity_available) as total_available,
                   SUM(p.quantity_sold) as total_sold
            FROM menus m
            LEFT JOIN production p ON m.id = p.menu_id
            WHERE m.id = " . intval($menuId) . "
            GROUP BY m.id, m.name, m.price
        ");
        $menu = $result->fetch_assoc();
        
        if (!$menu) {
            header('Location: /barcode');
            exit;
        }
        
        $this->view('barcode_update_sold', [
            'menu' => $menu,
            'barcode' => $barcode
        ]);
    }

    public function updateWastage()
    {
        $menuId = $_GET['menu_id'] ?? '';
        $barcode = $_GET['barcode'] ?? '';
        
        if (empty($menuId) || empty($barcode)) {
            header('Location: /barcode');
            exit;
        }
        
        // Get menu details and current production stats
        $result = $this->conn->query("
            SELECT m.id, m.name, m.price,
                   SUM(p.quantity_available) as total_available,
                   SUM(p.wastage) as total_wastage
            FROM menus m
            LEFT JOIN production p ON m.id = p.menu_id
            WHERE m.id = " . intval($menuId) . "
            GROUP BY m.id, m.name, m.price
        ");
        $menu = $result->fetch_assoc();
        
        if (!$menu) {
            header('Location: /barcode');
            exit;
        }
        
        $this->view('barcode_update_wastage', [
            'menu' => $menu,
            'barcode' => $barcode
        ]);
    }
}