<?php

namespace App\Models;

use PDO;

class Menu extends BaseModel
{
    public function getAllMenu()
    {
        $sql = "SELECT * FROM menu";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        // Fetch all rows as an associative array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
