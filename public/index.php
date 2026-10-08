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

$router->get('/usuarios', 'UsuarioController@listar');
$router->get('/usuarios/criarusuario', 'UsuarioController@criarusuarioForm');
$router->post('/usuarios/criarusuario', 'UsuarioController@criarusuarioSubmit');

$router->get('/usuarios/editar/{uuid}', 'UsuarioController@editarForm');
$router->post('/usuarios/editar/{uuid}', 'UsuarioController@editarSubmit');

$router->get('/usuarios/excluir/{uuid}', 'UsuarioController@excluir');

$router->get('/perfis/criarperfil', 'PerfilController@criarperfilForm');
$router->post('/perfis/criarperfil', 'PerfilController@criarperfilSubmit');

$router->get('/perfis/editar/{uuid}', 'PerfilController@editarForm');
$router->post('/perfis/editar/{uuid}', 'PerfilController@editarSubmit');

$router->get('/perfis/excluir/{uuid}', 'PerfilController@excluir');

$router->dispatch();