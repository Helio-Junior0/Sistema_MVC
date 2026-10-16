<?php

namespace App\Controllers;

class MeuPerfilController
{
    public function index()
    {
        $this->exigirLogin();

        $db = \App\Core\Database::getInstance();
        $stmt = $db->prepare("SELECT u.nome, u.email, u.cpf_cnpj, p.nome AS perfil
                              FROM usuarios u
                              JOIN perfis p ON p.id = u.perfil_id
                              WHERE u.uuid = :uuid");
        $stmt->execute([':uuid' => $_SESSION['user_id']]);
        $usuario = $stmt->fetch();

        view('meu-perfil/index', ['usuario' => $usuario, 'titulo' => 'Meu perfil'], 'layouts/master');
    }

    public function salvarDados()
    {
        $this->exigirLogin();

        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if (empty($nome)) {
            $this->voltar('erro', 'O nome é obrigatório.');
        }

        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->voltar('erro', 'E-mail inválido.');
        }

        $db = \App\Core\Database::getInstance();
        $stmt = $db->prepare("UPDATE usuarios SET nome = :nome, email = :email WHERE uuid = :uuid");
        $stmt->execute([':nome' => $nome, ':email' => $email, ':uuid' => $_SESSION['user_id']]);

        // Atualiza o nome que aparece na topbar
        $_SESSION['user_nome'] = $nome;

        $this->voltar('sucesso', 'Dados atualizados.');
    }

    public function alterarSenha()
    {
        $this->exigirLogin();

        $atual = $_POST['senha_atual'] ?? '';
        $nova = $_POST['nova_senha'] ?? '';
        $confirmar = $_POST['confirmar_senha'] ?? '';

        if (empty($atual) || empty($nova) || empty($confirmar)) {
            $this->voltar('erro', 'Preencha todos os campos de senha.');
        }

        if (strlen($nova) < 6) {
            $this->voltar('erro', 'A nova senha deve ter pelo menos 6 caracteres.');
        }

        if ($nova !== $confirmar) {
            $this->voltar('erro', 'A confirmação não confere com a nova senha.');
        }

        $db = \App\Core\Database::getInstance();

        // Confere a senha atual antes de trocar
        $stmt = $db->prepare("SELECT senha_hash FROM usuarios WHERE uuid = :uuid");
        $stmt->execute([':uuid' => $_SESSION['user_id']]);
        $hashAtual = $stmt->fetchColumn();

        if (!$hashAtual || !password_verify($atual, $hashAtual)) {
            $this->voltar('erro', 'Senha atual incorreta.');
        }

        $stmt = $db->prepare("UPDATE usuarios SET senha_hash = :hash WHERE uuid = :uuid");
        $stmt->execute([
            ':hash' => password_hash($nova, PASSWORD_DEFAULT),
            ':uuid' => $_SESSION['user_id']
        ]);

        notificar(
            $_SESSION['user_id'],
            'Sua senha foi alterada',
            'A senha da sua conta foi alterada. Se não foi você, procure o administrador do sistema.',
            '/meu-perfil'
        );

        $this->voltar('sucesso', 'Senha alterada com sucesso.');
    }

    private function exigirLogin()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    private function voltar($tipo, $mensagem)
    {
        $_SESSION[$tipo] = $mensagem;
        header('Location: /meu-perfil');
        exit;
    }
}