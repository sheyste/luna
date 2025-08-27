<?php
/**
 * Controller for Home page
 */
require BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/app/config.php';


class HomeController extends Controller
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
    // Production forecast (by day)
    $productionData = [];
    $result = $this->conn->query("
        SELECT DATE(created_at) as date, SUM(quantity_produced) as total_produced
        FROM production
        GROUP BY DATE(created_at)
        ORDER BY date ASC
    ");
    while ($row = $result->fetch_assoc()) {
        $productionData[] = $row;
    }

    // Daily cost vs profit using ingredient cost and selling price
    $costProfitData = [];
    $result = $this->conn->query("
        SELECT DATE(p.created_at) AS date,
            SUM(p.quantity_sold * mi.cost_per_item) AS total_cost,
            SUM(p.quantity_sold * m.price) AS total_revenue,
            SUM(p.quantity_sold * m.price) - SUM(p.quantity_sold * mi.cost_per_item) AS total_profit
        FROM production p
        JOIN menus m ON p.menu_id = m.id
        JOIN (
            SELECT mi.menu_id, SUM(mi.quantity * i.price) AS cost_per_item
            FROM menu_ingredients mi
            JOIN inventory i ON mi.inventory_id = i.id
            GROUP BY mi.menu_id
        ) mi ON p.menu_id = mi.menu_id
        WHERE p.created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        GROUP BY DATE(p.created_at)
        ORDER BY date ASC
    ");
    while ($row = $result->fetch_assoc()) {
        $costProfitData[] = $row;
    }

    // Card data
    $result = $this->conn->query("SELECT COUNT(*) as total FROM inventory");
    $totalItems = $result->fetch_assoc()['total'] ?? 0;

    $result = $this->conn->query("SELECT COUNT(*) as count FROM inventory WHERE quantity <= 10");
    $lowStockItems = $result->fetch_assoc()['count'] ?? 0;

    $result = $this->conn->query("SELECT SUM(quantity * price) as value FROM inventory");
    $totalInventoryValue = $result->fetch_assoc()['value'] ?? 0;

    $result = $this->conn->query("SELECT SUM(quantity_available) as total FROM production");
    $totalProducedAmount = $result->fetch_assoc()['total'] ?? 0;

    $this->view('home', [
        'productionData'    => $productionData,
        'costProfitData'    => $costProfitData,
        'totalItems' => $totalItems,
        'lowStockItems' => $lowStockItems,
        'totalInventoryValue' => $totalInventoryValue,
        'totalProducedAmount' => $totalProducedAmount,
    ]);
}


}
