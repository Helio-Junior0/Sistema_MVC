<?php

namespace App\Controllers;

class PerfilController
{
    public function listar()
    {
        exigir_permissao('perfis.gerenciar');

        $db = \App\Core\Database::getInstance();
        $perfis = $db->query("SELECT id, uuid, nome, descricao, ativo FROM perfis ORDER BY id")->fetchAll();

        view('perfis/listar', ['perfis' => $perfis, 'titulo' => 'Perfis'], 'layouts/master');
    }

    public function criarperfilForm()
    {
        exigir_permissao('perfis.gerenciar');

        view('perfis/criarperfil', ['titulo' => 'Novo Perfil'], 'layouts/master');
    }

    public function criarperfilSubmit()
    {
        exigir_permissao('perfis.gerenciar');

        $nome = trim($_POST['nome'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $ativo = isset($_POST['ativo']) ? 1 : 0;

        if (empty($nome)) {
            echo "O nome do perfil e obrigatorio.";
            echo "<br><a href='/perfis/criarperfil'>Voltar</a>";
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
        exigir_permissao('perfis.gerenciar');

        $db = \App\Core\Database::getInstance();
        $stmt = $db->prepare("SELECT id, uuid, nome, descricao, ativo FROM perfis WHERE uuid = :uuid");
        $stmt->execute([':uuid' => $uuid]);
        $perfil = $stmt->fetch();

        if (!$perfil) {
            header('Location: /perfis');
            exit;
        }

        $permissoes = $db->query("SELECT id, chave, nome_exibicao, grupo FROM permissoes ORDER BY grupo, chave")->fetchAll();

        $stmt = $db->prepare("SELECT permissao_id FROM perfil_permissoes WHERE perfil_id = :pid");
        $stmt->execute([':pid' => $perfil['id']]);
        $permissoesDoPerfil = $stmt->fetchAll(\PDO::FETCH_COLUMN);

        view('perfis/editar', [
            'perfil' => $perfil,
            'permissoes' => $permissoes,
            'permissoesDoPerfil' => $permissoesDoPerfil,
            'titulo' => 'Editar Perfil'
        ], 'layouts/master');
    }

    public function editarSubmit($uuid)
    {
        exigir_permissao('perfis.gerenciar');

        $nome = trim($_POST['nome'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $ativo = isset($_POST['ativo']) ? 1 : 0;

        if (empty($nome)) {
            echo "O nome do perfil e obrigatorio.";
            echo "<br><a href='/perfis/editar/$uuid'>Voltar</a>";
            exit;
        }

        $db = \App\Core\Database::getInstance();

        // Busca o id interno do perfil pelo uuid
        $stmt = $db->prepare("SELECT id FROM perfis WHERE uuid = :uuid");
        $stmt->execute([':uuid' => $uuid]);
        $perfilId = $stmt->fetchColumn();

        if (!$perfilId) {
            header('Location: /perfis');
            exit;
        }

        // Permissoes marcadas no formulario (vem como array)
        $permissoesMarcadas = $_POST['permissoes'] ?? [];

        try {
            $db->beginTransaction();

            $stmt = $db->prepare("UPDATE perfis SET nome = :nome, descricao = :descricao, ativo = :ativo WHERE id = :id");
            $stmt->execute([
                ':nome' => $nome,
                ':descricao' => $descricao,
                ':ativo' => $ativo,
                ':id' => $perfilId
            ]);

            // Apaga os vinculos antigos e grava os novos
            $stmt = $db->prepare("DELETE FROM perfil_permissoes WHERE perfil_id = :pid");
            $stmt->execute([':pid' => $perfilId]);

            $stmt = $db->prepare("INSERT INTO perfil_permissoes (perfil_id, permissao_id) VALUES (:pid, :permid)");
            foreach ($permissoesMarcadas as $permissaoId) {
                $stmt->execute([':pid' => $perfilId, ':permid' => (int) $permissaoId]);
            }

            $db->commit();
        } catch (\PDOException $e) {
            $db->rollBack();
            echo "Erro ao salvar o perfil.";
            echo "<br><a href='/perfis'>Voltar</a>";
            exit;
        }

        header('Location: /perfis');
        exit;
    }

    public function excluir($uuid)
    {
        exigir_permissao('perfis.gerenciar');

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
