<?php

namespace App\Controllers;

class DashboardController
{
    public function index()
    {

        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        view('dashboard', ['titulo' => 'Dashboard'], 'layouts/master');
    }
}
