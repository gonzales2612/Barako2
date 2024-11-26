<?php

namespace App\Controllers;

use App\Models\Order;
use App\Models\MenuItem;
use PDO;

class OrderController extends BaseController
{
    private $orderModel;
    private $menuItemModel;
    private $db;

    public function __construct($conn = null)
    {
        $this->db = $conn ?: new PDO('mysql:host=localhost;dbname=test', 'root', '');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->orderModel = new Order($this->db);
        $this->menuItemModel = new MenuItem($this->db);
    }

    // Show orders and menu items
    public function showOrders()
    {
        // Fetch all orders with associated menu item names
        $orders = $this->orderModel->getAllOrdersWithMenuNames();

        // Fetch all menu items for the order form
        $menuItems = $this->menuItemModel->getAllMenuItems();

        // Render view with orders and menu items
        $template = 'orders';
        $data = [
            'title' => 'OrdersTable',
            'orders' => $orders,
            'menuItems' => $menuItems
        ];

        echo $this->render($template, $data);
    }

    // Handle the form submission to create a new order
    public function createOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customerName = $_POST['customerName'];
            $menuItemIds = $_POST['menuItemIds']; // An array of selected menu item IDs

            // Calculate total amount
            $totalAmount = 0;
            foreach ($menuItemIds as $menuItemId) {
                $menuItem = $this->menuItemModel->getMenuItemById($menuItemId);
                $totalAmount += $menuItem['Price'];
            }

            // Create the order and get the order ID
            $orderId = $this->orderModel->createOrder($customerName, $totalAmount);

            // Add each selected menu item to the order
            foreach ($menuItemIds as $menuItemId) {
                $menuItem = $this->menuItemModel->getMenuItemById($menuItemId);
                $subtotal = $menuItem['Price']; // Assuming quantity is 1 for simplicity
                $this->orderModel->addMenuItemToOrder($orderId, $menuItemId, 1, $subtotal);
            }

            // Redirect to the orders page after order creation
            header('Location: /orders');
            exit();
        }
    }

    // Remove order by ID
    public function removeOrder($id)
    {
        // Delete the order from the database
        $this->orderModel->deleteOrderById($id);

        // Redirect back to the orders page after deletion
        header('Location: /orders');
        exit();
    }
}
