<?php

namespace App\Controllers;

class PermissaoController
{
    public function listar()
    {
        exigir_permissao('permissoes.gerenciar');

        $db = \App\Core\Database::getInstance();
        $permissoes = $db->query("SELECT id, uuid, chave, nome_exibicao, grupo FROM permissoes ORDER BY grupo, chave")->fetchAll();

        view('permissoes/listar', ['permissoes' => $permissoes]);
    }

    public function criarpermissaoForm()
    {
        exigir_permissao('permissoes.gerenciar');

        view('permissoes/criarpermissao');
    }

    public function criarpermissaoSubmit()
    {
        exigir_permissao('permissoes.gerenciar');

        $chave = trim($_POST['chave'] ?? '');
        $nome_exibicao = trim($_POST['nome_exibicao'] ?? '');
        $grupo = trim($_POST['grupo'] ?? '');

        if (empty($chave) || empty($nome_exibicao)) {
            echo "Chave e nome de exibicao sao obrigatorios.";
            echo "<br><a href='/permissoes/criarpermissao'>Voltar</a>";
            exit;
        }

        $db = \App\Core\Database::getInstance();

        // A chave e unica no banco
        $stmt = $db->prepare("SELECT uuid FROM permissoes WHERE chave = :chave");
        $stmt->execute([':chave' => $chave]);
        if ($stmt->fetch()) {
            echo "Ja existe uma permissao com essa chave.";
            echo "<br><a href='/permissoes/criarpermissao'>Voltar</a>";
            exit;
        }

        $uuid = gerar_uuid();

        $stmt = $db->prepare("INSERT INTO permissoes (uuid, chave, nome_exibicao, grupo) VALUES (:uuid, :chave, :nome_exibicao, :grupo)");
        $stmt->execute([
            ':uuid' => $uuid,
            ':chave' => $chave,
            ':nome_exibicao' => $nome_exibicao,
            ':grupo' => $grupo
        ]);

        header('Location: /permissoes');
        exit;
    }

    public function editarForm($uuid)
    {
        exigir_permissao('permissoes.gerenciar');

        $db = \App\Core\Database::getInstance();
        $stmt = $db->prepare("SELECT id, uuid, chave, nome_exibicao, grupo FROM permissoes WHERE uuid = :uuid");
        $stmt->execute([':uuid' => $uuid]);
        $permissao = $stmt->fetch();

        if (!$permissao) {
            header('Location: /permissoes');
            exit;
        }

        view('permissoes/editar', ['permissao' => $permissao]);
    }

    public function editarSubmit($uuid)
    {
        exigir_permissao('permissoes.gerenciar');

        $chave = trim($_POST['chave'] ?? '');
        $nome_exibicao = trim($_POST['nome_exibicao'] ?? '');
        $grupo = trim($_POST['grupo'] ?? '');

        if (empty($chave) || empty($nome_exibicao)) {
            echo "Chave e nome de exibicao sao obrigatorios.";
            echo "<br><a href='/permissoes/editar/$uuid'>Voltar</a>";
            exit;
        }

        $db = \App\Core\Database::getInstance();

        // A chave nao pode pertencer a OUTRA permissao
        $stmt = $db->prepare("SELECT uuid FROM permissoes WHERE chave = :chave AND uuid <> :uuid");
        $stmt->execute([':chave' => $chave, ':uuid' => $uuid]);
        if ($stmt->fetch()) {
            echo "Ja existe outra permissao com essa chave.";
            echo "<br><a href='/permissoes/editar/$uuid'>Voltar</a>";
            exit;
        }

        $stmt = $db->prepare("UPDATE permissoes SET chave = :chave, nome_exibicao = :nome_exibicao, grupo = :grupo WHERE uuid = :uuid");
        $stmt->execute([
            ':chave' => $chave,
            ':nome_exibicao' => $nome_exibicao,
            ':grupo' => $grupo,
            ':uuid' => $uuid
        ]);

        header('Location: /permissoes');
        exit;
    }

    public function excluir($uuid)
    {
        exigir_permissao('permissoes.gerenciar');

        $db = \App\Core\Database::getInstance();

        try {
            // O ON DELETE CASCADE remove a permissao de todos os perfis automaticamente
            $stmt = $db->prepare("DELETE FROM permissoes WHERE uuid = :uuid");
            $stmt->execute([':uuid' => $uuid]);

            header('Location: /permissoes');
            exit;

        } catch (\PDOException $e) {
            echo "Nao foi possivel excluir esta permissao.";
            echo "<br><a href='/permissoes'>Voltar</a>";
            exit;
        }
    }
}