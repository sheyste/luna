<?php

require_once BASE_PATH . '/core/Model.php';

class Menu extends Model
{
    public function __construct()
    {
        $this->db = $this->connectDB();
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM menus ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $menuStmt = $this->db->prepare("SELECT * FROM menus WHERE id = :id");
        $menuStmt->execute(['id' => $id]);
        $menu = $menuStmt->fetch(PDO::FETCH_ASSOC);

        if ($menu) {
            $ingredientsStmt = $this->db->prepare("
                SELECT mi.inventory_id, mi.quantity, i.unit, i.name 
                FROM menu_ingredients mi
                JOIN inventory i ON mi.inventory_id = i.id
                WHERE mi.menu_id = :menu_id
            ");
            $ingredientsStmt->execute(['menu_id' => $id]);
            $menu['ingredients'] = $ingredientsStmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $menu;
    }

    public function create($data)
    {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("INSERT INTO menus (name) VALUES (:name)");
            $stmt->execute(['name' => $data['name']]);
            $menuId = $this->db->lastInsertId();

            if (!empty($data['ingredients'])) {
                $inventoryIds = $data['ingredients']['inventory_id'];
                $quantities = $data['ingredients']['quantity'];
                $ingStmt = $this->db->prepare("INSERT INTO menu_ingredients (menu_id, inventory_id, quantity) VALUES (:menu_id, :inventory_id, :quantity)");
                for ($i = 0; $i < count($inventoryIds); $i++) {
                    if (!empty($inventoryIds[$i]) && !empty($quantities[$i])) {
                        $ingStmt->execute([
                            'menu_id' => $menuId,
                            'inventory_id' => $inventoryIds[$i],
                            'quantity' => $quantities[$i]
                        ]);
                    }
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Menu creation failed: " . $e->getMessage());
            return false;
        }
    }

    public function update($data)
    {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("UPDATE menus SET name = :name WHERE id = :id");
            $stmt->execute([
                'name' => $data['name'],
                'id' => $data['id']
            ]);

            $delStmt = $this->db->prepare("DELETE FROM menu_ingredients WHERE menu_id = :menu_id");
            $delStmt->execute(['menu_id' => $data['id']]);

            if (!empty($data['ingredients'])) {
                $inventoryIds = $data['ingredients']['inventory_id'];
                $quantities = $data['ingredients']['quantity'];
                $ingStmt = $this->db->prepare("INSERT INTO menu_ingredients (menu_id, inventory_id, quantity) VALUES (:menu_id, :inventory_id, :quantity)");
                for ($i = 0; $i < count($inventoryIds); $i++) {
                    if (!empty($inventoryIds[$i]) && !empty($quantities[$i])) {
                        $ingStmt->execute([
                            'menu_id' => $data['id'],
                            'inventory_id' => $inventoryIds[$i],
                            'quantity' => $quantities[$i]
                        ]);
                    }
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Menu update failed: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id)
    {
        $this->db->beginTransaction();
        try {
            $ingStmt = $this->db->prepare("DELETE FROM menu_ingredients WHERE menu_id = :menu_id");
            $ingStmt->execute(['menu_id' => $id]);

            $menuStmt = $this->db->prepare("DELETE FROM menus WHERE id = :id");
            $menuStmt->execute(['id' => $id]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Menu deletion failed: " . $e->getMessage());
            return false;
        }
    }
}
