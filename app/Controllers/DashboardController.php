<?php

namespace App\Controllers;

class DashboardController
{
    public function index()
    {
        // Protecao de rota: so entra quem esta logado
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        view('dashboard');
    }
}