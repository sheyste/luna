<?php

require_once BASE_PATH . '/core/Model.php';

class Inventory extends Model
{
    public function __construct()
    {
        $this->db = $this->connectDB();
    }

    public function getAllItems()
    {
        $stmt = $this->db->query("SELECT * FROM inventory ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getItemById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM inventory WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        // Generate a unique barcode
        $barcode = time() . rand(100, 999);

        $stmt = $this->db->prepare("
            INSERT INTO inventory (name, barcode, quantity, unit, price) 
            VALUES (:name, :barcode, :quantity, :unit, :price)
        ");

        return $stmt->execute([
            'name'     => $data['name'],
            'barcode'  => $barcode,
            'quantity' => $data['quantity'],
            'unit'     => $data['unit'],
            'price'    => $data['price']
        ]);
    }

    public function update($data)
    {
        $stmt = $this->db->prepare("
            UPDATE inventory 
            SET name = :name, quantity = :quantity, unit = :unit, price = :price 
            WHERE id = :id
        ");

        return $stmt->execute([
            'id'       => $data['id'],
            'name'     => $data['name'],
            'quantity' => $data['quantity'],
            'unit'     => $data['unit'],
            'price'    => $data['price']
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM inventory WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
