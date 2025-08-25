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
        $stmt = $this->db->prepare("
            INSERT INTO inventory (name, barcode, quantity, max_quantity, unit, price, purchase_date) 
            VALUES (:name, :barcode, :quantity, :max_quantity, :unit, :price, :purchase_date)
        ");

        return $stmt->execute([
            'name'          => $data['name'],
            'barcode'       => $data['barcode'],
            'quantity'      => $data['quantity'],
            'max_quantity'  => $data['max_quantity'],
            'unit'          => $data['unit'],
            'price'         => $data['price'],
            'purchase_date' => $data['purchase_date']
        ]);
    }

    public function update($data)
    {
        $stmt = $this->db->prepare("
            UPDATE inventory 
            SET name = :name, barcode = :barcode, quantity = :quantity, max_quantity = :max_quantity, unit = :unit, price = :price, purchase_date = :purchase_date 
            WHERE id = :id
        ");

        return $stmt->execute([
            'id'            => $data['id'],
            'name'          => $data['name'],
            'barcode'       => $data['barcode'],
            'quantity'      => $data['quantity'],
            'max_quantity'  => $data['max_quantity'],
            'unit'          => $data['unit'],
            'price'         => $data['price'],
            'purchase_date' => $data['purchase_date']
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM inventory WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
