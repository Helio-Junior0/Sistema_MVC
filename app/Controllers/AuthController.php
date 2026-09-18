<?php

namespace App\Controllers;

class AuthController
{
    public function cadastroForm()
    {
        view('cadastro');
    }

        public function cadastroSubmit()
    {

        $nome = trim($_POST['nome'] ?? '');
        $cpf = preg_replace('/[^0-9]/', '', $_POST['cpf'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $confirmarSenha = $_POST['confirmar_senha'] ?? '';

        if (empty($nome) || empty($cpf) || empty($senha)) {
            echo "Preencha nome, CPF e senha.";
            exit;
        }

        if ($senha !== $confirmarSenha) {
            echo "As senhas nao conferem.";
            exit;
        }

        $db = \App\Core\Database::getInstance();

        $stmt = $db->prepare("SELECT uuid FROM usuarios WHERE cpf_cnpj = :cpf");
        $stmt->execute([':cpf' => $cpf]);
        if ($stmt->fetch()) {
            echo "Ja existe um usuario com esse CPF.";
            exit;
        }

        $uuid = $this->gerarUuid();
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (uuid, nome, email, cpf_cnpj, senha_hash, perfil_id, ativo, tipo_usuario)
                VALUES (:uuid, :nome, :email, :cpf, :senha_hash, 2, 1, 'INTERNO')";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':uuid' => $uuid,
            ':nome' => $nome,
            ':email' => $email,
            ':cpf' => $cpf,
            ':senha_hash' => $senhaHash
        ]);

        echo "Usuario cadastrado com sucesso! CPF: " . $cpf;
        exit;
    }

    private function gerarUuid()
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    public function loginForm()
    {
        view('login');
    }

    public function loginSubmit()
    {
        
        $cpf = preg_replace('/[^0-9]/', '', $_POST['cpf'] ?? '');
        $senha = $_POST['senha'] ?? '';

        if (empty($cpf) || empty($senha)) {
            echo "Preencha todos os campos.";
            exit;
        }

        $db = \App\Core\Database::getInstance();
        $stmt = $db->prepare("SELECT uuid, nome, senha_hash, perfil_id FROM usuarios WHERE cpf_cnpj = :cpf AND ativo = 1");
        $stmt->execute([':cpf' => $cpf]);
        $user = $stmt->fetch();

                if ($user && password_verify($senha, $user['senha_hash'])) {
            // Guarda os dados do usuario na sessao
            $_SESSION['user_id'] = $user['uuid'];
            $_SESSION['user_nome'] = $user['nome'];
            $_SESSION['user_perfil'] = $user['perfil_id'];

            // Carrega as permissoes do perfil e guarda na sessao
            $sqlPerms = "SELECT p.chave
                         FROM permissoes p
                         JOIN perfil_permissoes pp ON p.id = pp.permissao_id
                         WHERE pp.perfil_id = :pid";
            $stmtPerms = $db->prepare($sqlPerms);
            $stmtPerms->execute([':pid' => $user['perfil_id']]);
            $_SESSION['user_permissions'] = $stmtPerms->fetchAll(\PDO::FETCH_COLUMN);

            // Seguranca: regenera o ID da sessao
            session_regenerate_id(true);

            // Redireciona pro dashboard
            header('Location: /dashboard');
            exit;
        } else {
            echo "CPF ou senha invalidos.";
            exit;
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: /login');
        exit;
    }
}