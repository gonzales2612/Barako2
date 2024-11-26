<?php

namespace App\Models;

use PDO;

class MenuItem extends BaseModel
{
    public function getAllMenuItems()
    {
        $sql = "SELECT * FROM menu_items";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMenuItemById($id)
    {
        $sql = "SELECT * FROM menu_items WHERE Id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
