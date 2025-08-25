<?php
/**
 * Controller for Home page
 */
require BASE_PATH . '/core/Controller.php';
require BASE_PATH . '/core/Database.php';

class HomeController extends Controller
{
    private $conn;

    public function __construct()
    {
        parent::__construct();
        $this->checkAuth();

        $db = new Database();
        $this->conn = $db->getConnection();
    }

public function index()
{
    // ✅ Production forecast (by day)
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

    // ✅ Weekly cost vs profit using ingredient cost and selling price
    $costProfitData = [];
    $result = $this->conn->query("
        SELECT YEARWEEK(p.created_at, 1) AS week,
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
        GROUP BY YEARWEEK(p.created_at, 1)
        ORDER BY week ASC
    ");
    while ($row = $result->fetch_assoc()) {
        $costProfitData[] = $row;
    }

    $this->view('home', [
        'productionData'    => $productionData,
        'costProfitData'    => $costProfitData
    ]);
}


}
