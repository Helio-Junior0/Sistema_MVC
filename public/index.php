<?php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

use App\Core\Router;

$router = new Router();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificar_csrf();
}

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

$router->post('/usuarios/excluir/{uuid}', 'UsuarioController@excluir');

$router->get('/perfis/criarperfil', 'PerfilController@criarperfilForm');
$router->post('/perfis/criarperfil', 'PerfilController@criarperfilSubmit');

$router->get('/perfis/editar/{uuid}', 'PerfilController@editarForm');
$router->post('/perfis/editar/{uuid}', 'PerfilController@editarSubmit');

$router->post('/perfis/excluir/{uuid}', 'PerfilController@excluir');

$router->get('/permissoes', 'PermissaoController@listar');

$router->get('/permissoes/criarpermissao', 'PermissaoController@criarpermissaoForm');
$router->post('/permissoes/criarpermissao', 'PermissaoController@criarpermissaoSubmit');

$router->get('/permissoes/editar/{uuid}', 'PermissaoController@editarForm');
$router->post('/permissoes/editar/{uuid}', 'PermissaoController@editarSubmit');

$router->get('/permissoes/listar', 'PermissaoController@listar');
$router->post('/permissoes/excluir/{uuid}', 'PermissaoController@excluir');

$router->get('/notificacoes/abrir/{uuid}', 'NotificacaoController@abrir');

$router->get('/usuarios/pdf', 'UsuarioController@pdf');

$router->get('/meu-perfil', 'MeuPerfilController@index');
$router->post('/meu-perfil/dados', 'MeuPerfilController@salvarDados');
$router->post('/meu-perfil/senha', 'MeuPerfilController@alterarSenha');

$router->dispatch();