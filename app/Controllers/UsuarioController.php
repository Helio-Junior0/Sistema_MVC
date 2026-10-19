<?php

namespace App\Controllers;

class UsuarioController
{
    public function listar()
    {
        exigir_permissao('usuarios.gerenciar');

        $db = \App\Core\Database::getInstance();
        $usuarios = $db->query("SELECT u.uuid, u.nome, u.email, u.cpf_cnpj, u.ativo, p.nome AS perfil
                                FROM usuarios u
                                JOIN perfis p ON p.id = u.perfil_id
                                ORDER BY u.nome")->fetchAll();

        view('usuarios/listar', ['usuarios' => $usuarios, 'titulo' => 'Usuários'], 'layouts/master');
    }

    public function criarusuarioForm()
    {
        exigir_permissao('usuarios.gerenciar');

        $db = \App\Core\Database::getInstance();
        $perfis = $db->query("SELECT id, nome FROM perfis WHERE ativo = 1 ORDER BY nome")->fetchAll();

        view('usuarios/criarusuario', ['perfis' => $perfis, 'titulo' => 'Novo Usuário'], 'layouts/master');
    }

    public function criarusuarioSubmit()
    {
        exigir_permissao('usuarios.gerenciar');

        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $cpf_cnpj = preg_replace('/[^0-9]/', '', $_POST['cpf_cnpj'] ?? '');
        $perfil_id = $_POST['perfil_id'] ?? '';
        $senha = $_POST['senha'] ?? '';
        $confirmarSenha = $_POST['confirmar_senha'] ?? '';
        $ativo = isset($_POST['ativo']) ? 1 : 0;

        if (!validar_cpf_cnpj($cpf_cnpj)) {
            echo "CPF/CNPJ invalido.";
            echo "<br><a href='/usuarios'>Voltar</a>";
            exit;
        }

        if ($senha !== $confirmarSenha) {
            echo "As senhas nao conferem.";
            echo "<br><a href='/usuarios/criarusuario'>Voltar</a>";
            exit;
        }

        $db = \App\Core\Database::getInstance();

        // Verifica se o CPF ja existe
        $stmt = $db->prepare("SELECT uuid FROM usuarios WHERE cpf_cnpj = :cpf");
        $stmt->execute([':cpf' => $cpf_cnpj]);
        if ($stmt->fetch()) {
            echo "Ja existe um usuario com esse CPF.";
            echo "<br><a href='/usuarios/criarusuario'>Voltar</a>";
            exit;
        }

        $uuid = gerar_uuid();
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $db->prepare("INSERT INTO usuarios (uuid, nome, email, cpf_cnpj, senha_hash, perfil_id, ativo, tipo_usuario)
                              VALUES (:uuid, :nome, :email, :cpf_cnpj, :senha_hash, :perfil_id, :ativo, 'INTERNO')");
        $stmt->execute([
            ':uuid' => $uuid,
            ':nome' => $nome,
            ':email' => $email,
            ':cpf_cnpj' => $cpf_cnpj,
            ':senha_hash' => $senhaHash,
            ':perfil_id' => $perfil_id,
            ':ativo' => $ativo
        ]);

        notificar(
            $uuid,
            'Sua conta foi criada',
            'Olá, ' . $nome . '! Sua conta no sistema foi criada. Você já pode acessar com seu CPF e a senha definida.',
            '/dashboard'
        );

        header('Location: /usuarios');
        exit;
    }

    public function editarForm($uuid)
    {
        exigir_permissao('usuarios.gerenciar');

        $db = \App\Core\Database::getInstance();
        $stmt = $db->prepare("SELECT u.uuid, u.nome, u.email, u.cpf_cnpj, u.perfil_id, u.ativo, p.nome AS perfil_nome
                              FROM usuarios u
                              JOIN perfis p ON p.id = u.perfil_id
                              WHERE u.uuid = :uuid");
        $stmt->execute([':uuid' => $uuid]);
        $usuario = $stmt->fetch();

        if (!$usuario) {
            header('Location: /usuarios');
            exit;
        }

        $perfis = $db->query("SELECT id, nome FROM perfis WHERE ativo = 1 ORDER BY nome")->fetchAll();

        view('usuarios/editar', ['usuario' => $usuario, 'perfis' => $perfis, 'titulo' => 'Editar Usuário'], 'layouts/master');
    }

    public function editarSubmit($uuid)
    {
        exigir_permissao('usuarios.gerenciar');

        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $cpf_cnpj = preg_replace('/[^0-9]/', '', $_POST['cpf_cnpj'] ?? '');
        $perfil_id = $_POST['perfil_id'] ?? '';
        $senha = $_POST['senha'] ?? '';
        $ativo = isset($_POST['ativo']) ? 1 : 0;

        if (!validar_cpf_cnpj($cpf_cnpj)) {
            echo "CPF/CNPJ invalido.";
            echo "<br><a href='/usuarios'>Voltar</a>";
            exit;
        }

        $db = \App\Core\Database::getInstance();

        // Verifica se o CPF ja pertence a OUTRO usuario
        $stmt = $db->prepare("SELECT uuid FROM usuarios WHERE cpf_cnpj = :cpf AND uuid <> :uuid");
        $stmt->execute([':cpf' => $cpf_cnpj, ':uuid' => $uuid]);
        if ($stmt->fetch()) {
            echo "Ja existe outro usuario com esse CPF.";
            echo "<br><a href='/usuarios/editar/$uuid'>Voltar</a>";
            exit;
        }

        $stmt = $db->prepare("UPDATE usuarios
                              SET nome = :nome, email = :email, cpf_cnpj = :cpf_cnpj, perfil_id = :perfil_id, ativo = :ativo
                              WHERE uuid = :uuid");
        $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':cpf_cnpj' => $cpf_cnpj,
            ':perfil_id' => $perfil_id,
            ':ativo' => $ativo,
            ':uuid' => $uuid
        ]);

        // Senha so e alterada se o campo foi preenchido
        if (!empty($senha)) {
            $stmt = $db->prepare("UPDATE usuarios SET senha_hash = :senha_hash WHERE uuid = :uuid");
            $stmt->execute([
                ':senha_hash' => password_hash($senha, PASSWORD_DEFAULT),
                ':uuid' => $uuid
            ]);
        }

        header('Location: /usuarios');
        exit;
    }

    public function excluir($uuid)
    {
        exigir_permissao('usuarios.gerenciar');

        // Impede o usuario logado de excluir a si mesmo
        if ($uuid === $_SESSION['user_id']) {
            echo "Voce nao pode excluir o seu proprio usuario.";
            echo "<br><a href='/usuarios'>Voltar</a>";
            exit;
        }

        $db = \App\Core\Database::getInstance();

        try {
            $stmt = $db->prepare("DELETE FROM usuarios WHERE uuid = :uuid");
            $stmt->execute([':uuid' => $uuid]);

            header('Location: /usuarios');
            exit;
        } catch (\PDOException $e) {
            echo "Nao foi possivel excluir este usuario.";
            echo "<br><a href='/usuarios'>Voltar</a>";
            exit;
        }
    }
    public function pdf()
    {
        exigir_permissao('usuarios.gerenciar');

        $db = \App\Core\Database::getInstance();
        $usuarios = $db->query("SELECT u.nome, u.email, u.cpf_cnpj, u.ativo, p.nome AS perfil
                                FROM usuarios u
                                JOIN perfis p ON p.id = u.perfil_id
                                ORDER BY u.nome")->fetchAll();

        $html = render('pdf/usuarios', ['usuarios' => $usuarios]);

        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => __DIR__ . '/../../storage/tmp',
            'format' => 'A4'
        ]);
        $mpdf->SetTitle('Relatório de Usuários');
        $mpdf->WriteHTML($html);
        $mpdf->Output('relatorio-usuarios.pdf', 'I');
        exit;
    }
}
