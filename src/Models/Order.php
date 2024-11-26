<?php

namespace App\Models;

use PDO;

class Order extends BaseModel
{
    public function getAllOrdersWithMenuNames()
    {
        $sql = "
            SELECT 
                o.Id AS OrderId,
                o.CustomerName,
                o.OrderDate,
                o.TotalAmount,
                GROUP_CONCAT(mi.Name ORDER BY oi.MenuItemId SEPARATOR ', ') AS MenuItemNames
            FROM orders o
            JOIN order_items oi ON o.Id = oi.OrderId
            JOIN menu_items mi ON oi.MenuItemId = mi.Id
            GROUP BY o.Id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createOrder($customerName, $totalAmount)
    {
        $sql = "INSERT INTO orders (CustomerName, TotalAmount) VALUES (:customerName, :totalAmount)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':customerName', $customerName);
        $stmt->bindParam(':totalAmount', $totalAmount);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function addMenuItemToOrder($orderId, $menuItemId, $quantity, $subtotal)
    {
        $sql = "INSERT INTO order_items (OrderId, MenuItemId, Quantity, Subtotal) 
                VALUES (:orderId, :menuItemId, :quantity, :subtotal)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':orderId', $orderId);
        $stmt->bindParam(':menuItemId', $menuItemId);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':subtotal', $subtotal);
        $stmt->execute();
    }

    public function updateOrderTotal($orderId, $totalAmount)
    {
        $sql = "UPDATE orders SET TotalAmount = :totalAmount WHERE Id = :orderId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':orderId', $orderId);
        $stmt->bindParam(':totalAmount', $totalAmount);
        $stmt->execute();
    }

    // Corrected delete method
    public function deleteOrderById($orderId)
    {
        // Correct column name is 'Id'
        $stmt = $this->db->prepare("DELETE FROM orders WHERE Id = :orderId");
        $stmt->bindParam(':orderId', $orderId);
        $stmt->execute();
    }
}
