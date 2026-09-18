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

        // Recebe os dados
        $nome = trim($_POST['nome'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $ativo = isset($_POST['ativo']) ? 1 : 0;

        // Validacao
        if (empty($nome)) {
            echo "O nome do perfil e obrigatorio.";
            exit;
        }

        // Insere no banco
        $db = \App\Core\Database::getInstance();
        $stmt = $db->prepare("INSERT INTO perfis (nome, descricao, ativo) VALUES (:nome, :descricao, :ativo)");
        $stmt->execute([
            ':nome' => $nome,
            ':descricao' => $descricao,
            ':ativo' => $ativo
        ]);

        // Volta pra listagem
        header('Location: /perfis');
        exit;
    }
}