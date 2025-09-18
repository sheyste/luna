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
        SELECT DATE(created_at) as date,
               SUM(quantity_produced) as total_produced,
               SUM(quantity_sold) as total_sold,
               SUM(wastage) as total_wastage
        FROM production
        WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
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

    $result = $this->conn->query("SELECT COUNT(*) as count FROM inventory WHERE max_quantity > 0 AND quantity <= (max_quantity * 0.2)");
    $lowStockItems = $result->fetch_assoc()['count'] ?? 0;

    $result = $this->conn->query("SELECT COUNT(*) as count FROM inventory WHERE max_quantity > 0 AND quantity >= (max_quantity * 1.1)");
    $overStockItems = $result->fetch_assoc()['count'] ?? 0;

    $result = $this->conn->query("SELECT SUM(quantity * price) as value FROM inventory");
    $totalInventoryValue = $result->fetch_assoc()['value'] ?? 0;

    $result = $this->conn->query("SELECT COUNT(*) as count FROM purchase_orders WHERE status = 'Pending'");
    $pendingPurchaseOrders = $result->fetch_assoc()['count'] ?? 0;

    $result = $this->conn->query("SELECT COUNT(*) as count FROM purchase_orders WHERE status = 'Ordered'");
    $orderedPurchaseOrders = $result->fetch_assoc()['count'] ?? 0;

    $result = $this->conn->query("SELECT COUNT(*) as count FROM purchase_orders WHERE expected_delivery = CURDATE()");
    $arrivingToday = $result->fetch_assoc()['count'] ?? 0;

    // Inventory data for pie chart
    $result = $this->conn->query("SELECT name, quantity FROM inventory WHERE quantity > 0 ORDER BY quantity DESC");
    $inventoryData = [];
    while ($row = $result->fetch_assoc()) {
        $inventoryData[] = $row;
    }

    // Latest low stock alerts
    $result = $this->conn->query("SELECT item_name, current_quantity, unit, resolved, alert_date FROM low_stock_alerts WHERE status != 'resolved' ORDER BY alert_date DESC LIMIT 5");
    $latestLowStockAlerts = [];
    while ($row = $result->fetch_assoc()) {
        $latestLowStockAlerts[] = $row;
    }

    // Production efficiency data - multiple time periods
    // Today
    $result = $this->conn->query("
        SELECT
            SUM(quantity_produced) as total_produced,
            SUM(wastage) as total_wastage
        FROM production
        WHERE DATE(created_at) = CURDATE()
    ");
    $todayProduction = $result->fetch_assoc();
    $totalProducedToday = $todayProduction['total_produced'] ?? 0;
    $totalWastageToday = $todayProduction['total_wastage'] ?? 0;
    $wastagePercentageToday = $totalProducedToday > 0 ? round(($totalWastageToday / $totalProducedToday) * 100, 2) : 0;

    // This week (last 7 days)
    $result = $this->conn->query("
        SELECT
            SUM(quantity_produced) as total_produced,
            SUM(wastage) as total_wastage
        FROM production
        WHERE DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    ");
    $weekProduction = $result->fetch_assoc();
    $totalProducedWeek = $weekProduction['total_produced'] ?? 0;
    $totalWastageWeek = $weekProduction['total_wastage'] ?? 0;
    $wastagePercentageWeek = $totalProducedWeek > 0 ? round(($totalWastageWeek / $totalProducedWeek) * 100, 2) : 0;

    // This month (last 30 days)
    $result = $this->conn->query("
        SELECT
            SUM(quantity_produced) as total_produced,
            SUM(wastage) as total_wastage
        FROM production
        WHERE DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    ");
    $monthProduction = $result->fetch_assoc();
    $totalProducedMonth = $monthProduction['total_produced'] ?? 0;
    $totalWastageMonth = $monthProduction['total_wastage'] ?? 0;
    $wastagePercentageMonth = $totalProducedMonth > 0 ? round(($totalWastageMonth / $totalProducedMonth) * 100, 2) : 0;

    // Get total sold for the week (for the main production card)
    $result = $this->conn->query("
        SELECT SUM(quantity_sold) as total_sold
        FROM production
        WHERE DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    ");
    $soldData = $result->fetch_assoc();
    $totalSoldRecent = $soldData['total_sold'] ?? 0;



    // Recent production totals (for the main card)
    $totalProducedRecent = $totalProducedWeek;
    $totalWastageRecent = $totalWastageWeek;
    $wastagePercentage = $wastagePercentageWeek;

    // Ingredient availability alerts - check which menu items have insufficient ingredients
    $result = $this->conn->query("
        SELECT
            m.name as menu_name,
            m.id as menu_id,
            FLOOR(MIN(i.quantity / mi.quantity)) as max_producible
        FROM menus m
        JOIN menu_ingredients mi ON m.id = mi.menu_id
        JOIN inventory i ON mi.inventory_id = i.id
        WHERE mi.quantity > 0
        GROUP BY m.id, m.name
        HAVING max_producible < 20
        ORDER BY max_producible ASC
        LIMIT 5
    ");
    $ingredientAlerts = [];
    while ($row = $result->fetch_assoc()) {
        $ingredientAlerts[] = $row;
    }

    $this->view('home', [
        'productionData'    => $productionData,
        'costProfitData'    => $costProfitData,
        'totalItems' => $totalItems,
        'lowStockItems' => $lowStockItems,
        'overStockItems' => $overStockItems,
        'totalInventoryValue' => $totalInventoryValue,
        'pendingPurchaseOrders' => $pendingPurchaseOrders,
        'orderedPurchaseOrders' => $orderedPurchaseOrders,
        'arrivingToday' => $arrivingToday,
        'inventoryData' => $inventoryData,
        'latestLowStockAlerts' => $latestLowStockAlerts,
        'totalProducedRecent' => $totalProducedRecent,
        'totalSoldRecent' => $totalSoldRecent,
        'totalWastageRecent' => $totalWastageRecent,
        'wastagePercentage' => $wastagePercentage,
        'wastagePercentageToday' => $wastagePercentageToday,
        'wastagePercentageWeek' => $wastagePercentageWeek,
        'wastagePercentageMonth' => $wastagePercentageMonth,
        'ingredientAlerts' => $ingredientAlerts
    ]);
}


}
