<?php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;

$router = new Router();

$router->get('/login', 'AuthController@loginForm');
$router->post('/login', 'AuthController@loginSubmit');

$router->get('/cadastro', 'AuthController@cadastroForm');
$router->post('/cadastro', 'AuthController@cadastroSubmit');

$router->get('/dashboard', 'DashboardController@index');
$router->get('/logout', 'AuthController@logout');

$router->get('/perfis', 'PerfilController@listar');

$router->get('/perfis/criarperfil', 'PerfilController@criarperfilForm');
$router->post('/perfis/criarperfil', 'PerfilController@criarperfilSubmit');

$router->dispatch();