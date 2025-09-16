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
        $result = $this->conn->query("SELECT id, name, barcode, quantity, unit, price, max_quantity FROM inventory ORDER BY name ASC");
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

        $this->view('barcode/scanner', [
            'inventoryItems' => $inventoryItems,
            'menuItems' => $menuItems,
            'productionItems' => $productionItems
        ]);
    }

    public function selection()
    {
        $barcode = $_GET['barcode'] ?? '';
        $inventoryId = $_GET['inventory_id'] ?? '';
        $productionId = $_GET['production_id'] ?? '';
        $menuId = $_GET['menu_id'] ?? '';
        
        // Allow selection page if barcode is present and at least one ID is provided
        if (empty($barcode) || (empty($inventoryId) && empty($productionId) && empty($menuId))) {
            header('Location: /barcode');
            exit;
        }

        $inventoryItem = null;
        $productionItem = null;
        $menuItem = null;
        $hasInventory = false;
        $hasProduction = false;
        $hasMenu = false;

        // Get inventory item if provided
        if (!empty($inventoryId)) {
            $stmt = $this->conn->prepare("SELECT id, name, barcode, quantity, unit, price, max_quantity FROM inventory WHERE id = ?");
            $stmt->bind_param("i", $inventoryId);
            $stmt->execute();
            $result = $stmt->get_result();
            $inventoryItem = $result->fetch_assoc();
            $stmt->close();
            $hasInventory = !empty($inventoryItem);
        }

        // Get production item if provided
        if (!empty($productionId)) {
            $stmt = $this->conn->prepare("
                SELECT p.id, p.menu_id, p.barcode, p.quantity_produced, p.quantity_available,
                       p.quantity_sold, p.wastage, m.name AS menu_name, m.price
                FROM production p
                JOIN menus m ON p.menu_id = m.id
                WHERE p.id = ?
            ");
            $stmt->bind_param("i", $productionId);
            $stmt->execute();
            $result = $stmt->get_result();
            $productionItem = $result->fetch_assoc();
            $stmt->close();
            $hasProduction = !empty($productionItem);
        }

        // Get menu item if provided
        if (!empty($menuId)) {
            $stmt = $this->conn->prepare("SELECT id, name, barcode, price FROM menus WHERE id = ?");
            $stmt->bind_param("i", $menuId);
            $stmt->execute();
            $result = $stmt->get_result();
            $menuItem = $result->fetch_assoc();
            $stmt->close();
            $hasMenu = !empty($menuItem);
        }

        if (!$hasInventory && !$hasProduction && !$hasMenu) {
            header('Location: /barcode');
            exit;
        }

        $this->view('barcode/selection', [
            'barcode' => $barcode,
            'hasInventory' => $hasInventory,
            'hasProduction' => $hasProduction,
            'hasMenu' => $hasMenu,
            'inventoryItem' => $inventoryItem,
            'productionItem' => $productionItem,
            'menuItem' => $menuItem
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
        $stmt = $this->conn->prepare("SELECT id, name, barcode, quantity, unit, price, max_quantity FROM inventory WHERE id = ?");
        $stmt->bind_param("i", $itemId);
        $stmt->execute();
        $result = $stmt->get_result();
        $item = $result->fetch_assoc();
        $stmt->close();
        
        if (!$item) {
            header('Location: /barcode');
            exit;
        }
        
        $this->view('barcode/physical-count', [
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
        $stmt = $this->conn->prepare("
            SELECT p.id, p.menu_id, p.barcode, p.quantity_produced, p.quantity_available,
                   p.quantity_sold, p.wastage, m.name AS menu_name, m.price
            FROM production p
            JOIN menus m ON p.menu_id = m.id
            WHERE p.id = ?
        ");
        $stmt->bind_param("i", $itemId);
        $stmt->execute();
        $result = $stmt->get_result();
        $item = $result->fetch_assoc();
        $stmt->close();
        
        if (!$item) {
            header('Location: /barcode');
            exit;
        }
        
        $this->view('barcode/production-actions', [
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
        $stmt = $this->conn->prepare("SELECT id, name, barcode, price FROM menus WHERE id = ?");
        $stmt->bind_param("i", $itemId);
        $stmt->execute();
        $result = $stmt->get_result();
        $item = $result->fetch_assoc();
        $stmt->close();
        
        if (!$item) {
            header('Location: /barcode');
            exit;
        }
        
        $this->view('barcode/menu-actions', [
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
        $stmt = $this->conn->prepare("SELECT id, name, price FROM menus WHERE id = ?");
        $stmt->bind_param("i", $menuId);
        $stmt->execute();
        $result = $stmt->get_result();
        $menu = $result->fetch_assoc();
        $stmt->close();
        
        if (!$menu) {
            header('Location: /barcode');
            exit;
        }
        
        $this->view('barcode/add-production', [
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
        $stmt = $this->conn->prepare("
            SELECT m.id, m.name, m.price,
                   SUM(p.quantity_available) as total_available,
                   SUM(p.quantity_sold) as total_sold
            FROM menus m
            LEFT JOIN production p ON m.id = p.menu_id
            WHERE m.id = ?
            GROUP BY m.id, m.name, m.price
        ");
        $stmt->bind_param("i", $menuId);
        $stmt->execute();
        $result = $stmt->get_result();
        $menu = $result->fetch_assoc();
        $stmt->close();
        
        if (!$menu) {
            header('Location: /barcode');
            exit;
        }
        
        $this->view('barcode/update-sold', [
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
        $stmt = $this->conn->prepare("
            SELECT m.id, m.name, m.price,
                   SUM(p.quantity_available) as total_available,
                   SUM(p.wastage) as total_wastage
            FROM menus m
            LEFT JOIN production p ON m.id = p.menu_id
            WHERE m.id = ?
            GROUP BY m.id, m.name, m.price
        ");
        $stmt->bind_param("i", $menuId);
        $stmt->execute();
        $result = $stmt->get_result();
        $menu = $result->fetch_assoc();
        $stmt->close();
        
        if (!$menu) {
            header('Location: /barcode');
            exit;
        }
        
        $this->view('barcode/update-wastage', [
            'menu' => $menu,
            'barcode' => $barcode
        ]);
    }
}