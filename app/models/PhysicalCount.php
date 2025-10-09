<?php

require_once BASE_PATH . '/core/Model.php';

#[AllowDynamicProperties]
class PhysicalCount extends Model
{
    public function __construct()
    {
        $this->db = $this->connectDB();
    }

    public function addEntry($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO physical_count_entries
            (inventory_id, item_name, system_count, physical_count, difference, variance_percent, value_impact, unit_price)
            VALUES (:inventory_id, :item_name, :system_count, :physical_count, :difference, :variance_percent, :value_impact, :unit_price)
        ");

        $result = $stmt->execute([
            'inventory_id' => $data['inventory_id'],
            'item_name' => $data['item_name'],
            'system_count' => $data['system_count'],
            'physical_count' => $data['physical_count'],
            'difference' => $data['difference'],
            'variance_percent' => $data['variance_percent'],
            'value_impact' => $data['value_impact'],
            'unit_price' => $data['unit_price']
        ]);
        
        if ($result) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }

    public function getEntryByInventoryId($inventoryId)
    {
        $stmt = $this->db->prepare("SELECT * FROM physical_count_entries WHERE inventory_id = :inventory_id AND status = 'pending'");
        $stmt->execute(['inventory_id' => $inventoryId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateEntry($data)
    {
        $stmt = $this->db->prepare("
            UPDATE physical_count_entries
            SET physical_count = :physical_count,
                difference = :difference,
                variance_percent = :variance_percent,
                value_impact = :value_impact,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $data['id'],
            'physical_count' => $data['physical_count'],
            'difference' => $data['difference'],
            'variance_percent' => $data['variance_percent'],
            'value_impact' => $data['value_impact']
        ]);
    }

    public function getPendingEntries()
    {
        $stmt = $this->db->query("SELECT * FROM physical_count_entries WHERE status = 'pending' ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEntryById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM physical_count_entries WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateEntryStatus($id, $status)
    {
        $stmt = $this->db->prepare("UPDATE physical_count_entries SET status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        return $stmt->execute(['id' => $id, 'status' => $status]);
    }

    public function deleteEntry($id)
    {
        $stmt = $this->db->prepare("DELETE FROM physical_count_entries WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function deleteSavedEntries()
    {
        $stmt = $this->db->prepare("DELETE FROM physical_count_entries WHERE status = 'saved'");
        return $stmt->execute();
    }
}
