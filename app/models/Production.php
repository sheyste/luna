<?php

require_once BASE_PATH . '/core/Model.php';

#[AllowDynamicProperties]
class Production extends Model
{
    public function __construct()
    {
        $this->db = $this->connectDB();
    }

    public function getAllWithDetails()
    {
        $stmt = $this->db->query("
            SELECT p.id, p.menu_id, p.barcode, p.quantity_produced, p.quantity_available, p.quantity_sold, p.wastage, p.created_at,
                   m.name AS menu_name, m.price
            FROM production p
            JOIN menus m ON p.menu_id = m.id
            ORDER BY p.created_at DESC
        ");
        
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calculate additional fields for each item
        foreach ($items as &$item) {
            // Get unit cost for this menu item
            $costStmt = $this->db->prepare("
                SELECT COALESCE(SUM(mi.quantity * i.price), 0) as unit_cost
                FROM menu_ingredients mi
                LEFT JOIN inventory i ON mi.inventory_id = i.id
                WHERE mi.menu_id = :menu_id
            ");
            $costStmt->execute(['menu_id' => $item['menu_id']]);
            $costResult = $costStmt->fetch(PDO::FETCH_ASSOC);
            $unitCost = $costResult['unit_cost'] ?? 0;
            
            $price = $item['price'] ?? 0;
            $produced = $item['quantity_produced'] ?? 0;
            $sold = $item['quantity_sold'] ?? 0;
            $wastage = $item['wastage'] ?? 0;
            
            $item['unit_cost'] = $unitCost;
            $item['total_cost'] = $unitCost * $produced;
            $item['total_sales'] = $price * $sold;
            $item['waste_cost'] = $unitCost * $wastage;
            $item['profit'] = $item['total_sales'] - $item['total_cost'] - $item['waste_cost'];
        }
        
        return $items;
    }
}
