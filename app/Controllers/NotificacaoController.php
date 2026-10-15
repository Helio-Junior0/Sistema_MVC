<?php

namespace App\Controllers;

class NotificacaoController
{
    public function abrir($uuid)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $db = \App\Core\Database::getInstance();

        // Busca a notificacao, garantindo que pertence ao usuario logado
        $stmt = $db->prepare("SELECT link FROM notificacoes WHERE uuid = :uuid AND usuario_uuid = :u");
        $stmt->execute([':uuid' => $uuid, ':u' => $_SESSION['user_id']]);
        $notificacao = $stmt->fetch();

        if (!$notificacao) {
            header('Location: /dashboard');
            exit;
        }

        // Marca como lida
        $stmt = $db->prepare("UPDATE notificacoes SET lida = 1, lida_em = NOW() WHERE uuid = :uuid AND usuario_uuid = :u");
        $stmt->execute([':uuid' => $uuid, ':u' => $_SESSION['user_id']]);

        // Vai para o link da notificacao (so aceita caminhos internos)
        $destino = $notificacao['link'] ?? '';
        if (!str_starts_with($destino, '/')) {
            $destino = '/dashboard';
        }

        header('Location: ' . $destino);
        exit;
    }
}