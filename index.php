<?php

require "vendor/autoload.php";
require "init.php";

global $conn;

try {
    // Create Router instance
    $router = new \Bramus\Router\Router();

    // Define routes
    $router->get('/', '\App\Controllers\HomeController@index');
    
    $router->get('/register', '\App\Controllers\RegisterController@registrationForm');
    $router->post('/register', '\App\Controllers\RegisterController@register');

    $router->get('/login', '\App\Controllers\LoginController@loginForm');
    $router->post('/login', '\App\Controllers\LoginController@login');

    $router->get('/menu', '\App\Controllers\MenuController@showMenu');
    $router->post('/order/create', '\App\Controllers\OrderController@createOrder'); // Handle order submission


    // For GET requests to show orders
    $router->get('/orders', '\App\Controllers\OrderController@showOrders');

    // For POST requests to create an order
    $router->post('/orders', '\App\Controllers\OrderController@createOrder');

    // For deleting an order
    $router->get('/orders/remove/{id}', '\App\Controllers\OrderController@removeOrder'); // Correct route

    // Run it!
    $router->run();
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
