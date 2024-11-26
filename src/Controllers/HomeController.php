<?php

namespace App\Controllers;

class HomeController extends BaseController
{
    public function index()
    {
        session_start();
        if (!isset($_SESSION['is_logged_in'])) {
            header('Location: /login');
            exit();
        }

        // Pass the user's first name to the view
        $data = [
            'firstname' => $_SESSION['firstname'] ?? 'Guest',  // Use 'Guest' as a fallback
        ];

        return $this->render('home', $data); // Pass $data to the view
    }
}
