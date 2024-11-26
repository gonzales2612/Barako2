<?php

namespace App\Controllers;

use App\Models\Menu;
use PDO;

class MenuController extends BaseController
{
    private $menuModel;
    private $db;

    public function __construct($conn = null)
    {
        // Create database connection if not provided
        $this->db = $conn ?: new PDO('mysql:host=localhost;dbname=test', 'root', '');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exceptions for errors
        $this->menuModel = new Menu($this->db);
    }

    public function showMenu()
    {
        // Fetch menu data using the model
        $menu = $this->menuModel->getAllMenu();

        // Define template and data for rendering
        $template = 'menu'; // Corresponds to the view filename `menu.php`
        $data = ['title' => 'MenuTable', 'menu' => $menu];

        echo $this->render($template, $data);
    }
}
