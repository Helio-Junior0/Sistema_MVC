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
        $perfis = $db->query("SELECT id, uuid, nome, descricao, ativo FROM perfis ORDER BY id")->fetchAll();

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

        $db = \App\Core\Database::getInstance();
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

        $db = \App\Core\Database::getInstance();
        $stmt = $db->prepare("SELECT id, uuid, nome, descricao, ativo FROM perfis WHERE uuid = :uuid");
        $stmt->execute([':uuid' => $uuid]);
        $perfil = $stmt->fetch();

        if (!$perfil) {
            header('Location: /perfis');
            exit;
        }

        view('perfis/editar', ['perfil' => $perfil]);
    }

    public function editarSubmit($uuid)
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

        $db = \App\Core\Database::getInstance();
        $stmt = $db->prepare("UPDATE perfis SET nome = :nome, descricao = :descricao, ativo = :ativo WHERE uuid = :uuid");
        $stmt->execute([
            ':nome' => $nome,
            ':descricao' => $descricao,
            ':ativo' => $ativo,
            ':uuid' => $uuid
        ]);

        header('Location: /perfis');
        exit;
    }

    public function excluir($uuid)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $db = \App\Core\Database::getInstance();

        try {
            $stmt = $db->prepare("DELETE FROM perfis WHERE uuid = :uuid");
            $stmt->execute([':uuid' => $uuid]);

            header('Location: /perfis');
            exit;

        } catch (\PDOException $e) {
            echo "Nao e possivel excluir este perfil porque existem usuarios vinculados a ele.";
            echo "<br><a href='/perfis'>Voltar</a>";
            exit;
        }
    }
}