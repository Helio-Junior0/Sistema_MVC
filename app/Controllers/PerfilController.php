<?php

namespace App\Controllers;

class PerfilController
{
    public function listar()
    {

        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $db = \App\Core\Database::getInstance();
        $perfis = $db->query("SELECT id, nome, descricao, ativo FROM perfis ORDER BY id")->fetchAll();

        view('perfis/listar', ['perfis' => $perfis]);
    }
    public function criarperfilForm()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        view('perfis/criarperfil');
    }

    public function criarperfilSubmit()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $nome = trim($_POST['nome'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $ativo = isset($_POST['ativo']) ? 1 : 0;

        if (empty($nome)) {
            echo "O nome do perfil e obrigatorio.";
            exit;
        }

        $uuid = gerar_uuid();

        $stmt = $db->prepare("INSERT INTO perfis (uuid, nome, descricao, ativo) VALUES (:uuid, :nome, :descricao, :ativo)");
        $stmt->execute([
            ':uuid' => $uuid,
            ':nome' => $nome,
            ':descricao' => $descricao,
            ':ativo' => $ativo
        ]);

        header('Location: /perfis');
        exit;
    }
    public function editarForm($uuid)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        // Busca o perfil pelo uuid
        $db = \App\Core\Database::getInstance();
        $stmt = $db->prepare("SELECT id, uuid, nome, descricao, ativo FROM perfis WHERE uuid = :uuid");
        $stmt->execute([':uuid' => $uuid]);
        $perfil = $stmt->fetch();

        // Se nao achou, volta pra listagem
        if (!$perfil) {
            header('Location: /perfis');
            exit;
        }

        // Passa o perfil pra view preencher o formulario
        view('perfis/editar', ['perfil' => $perfil]);
    }
    public function editarSubmit($uuid)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        // Recebe os dados do formulario
        $nome = trim($_POST['nome'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $ativo = isset($_POST['ativo']) ? 1 : 0;

        // Validacao
        if (empty($nome)) {
            echo "O nome do perfil e obrigatorio.";
            exit;
        }

        // Atualiza no banco, identificando o perfil pelo uuid
        $db = \App\Core\Database::getInstance();
        $stmt = $db->prepare("UPDATE perfis SET nome = :nome, descricao = :descricao, ativo = :ativo WHERE uuid = :uuid");
        $stmt->execute([
            ':nome' => $nome,
            ':descricao' => $descricao,
            ':ativo' => $ativo,
            ':uuid' => $uuid
        ]);

        // Volta pra listagem
        header('Location: /perfis');
        exit;
    }
}
