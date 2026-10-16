<?php

function view($nome, $dados = [], $layout = null)
{
    $caminho = __DIR__ . '/../Views/' . $nome . '.php';

    if (!file_exists($caminho)) {
        echo "View nao encontrada: " . $nome;
        return;
    }

    extract($dados);

    if ($layout === null) {
        require $caminho;
        return;
    }

    ob_start();
    require $caminho;
    $conteudo = ob_get_clean();

    require __DIR__ . '/../Views/' . $layout . '.php';
}

function gerar_uuid()
{
    $data = random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function tem_permissao($chave)
{
    return in_array($chave, $_SESSION['user_permissions'] ?? []);
}

function exigir_permissao($chave)
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }

    if (!tem_permissao($chave)) {
        http_response_code(403);
        echo "Voce nao tem permissao para acessar esta pagina.";
        echo "<br><a href='/dashboard'>Voltar</a>";
        exit;
    }
}

function notificacoes_nao_lidas($limite = 5)
{
    if (!isset($_SESSION['user_id'])) {
        return ['total' => 0, 'itens' => []];
    }

    $db = \App\Core\Database::getInstance();

    // Quantas nao lidas o usuario tem (para o numerozinho do sino)
    $stmt = $db->prepare("SELECT COUNT(*) FROM notificacoes WHERE usuario_uuid = :u AND lida = 0");
    $stmt->execute([':u' => $_SESSION['user_id']]);
    $total = (int) $stmt->fetchColumn();

    // As mais recentes, para a lista
    $stmt = $db->prepare("SELECT uuid, titulo, mensagem, criado_em
                          FROM notificacoes
                          WHERE usuario_uuid = :u AND lida = 0
                          ORDER BY criado_em DESC
                          LIMIT " . (int) $limite);
    $stmt->execute([':u' => $_SESSION['user_id']]);

    return ['total' => $total, 'itens' => $stmt->fetchAll()];
}

function notificar($usuarioUuid, $titulo, $mensagem, $link = null, $enviarEmail = true)
{
    $db = \App\Core\Database::getInstance();

    $stmt = $db->prepare("INSERT INTO notificacoes (uuid, usuario_uuid, titulo, mensagem, link, enviar_email)
                          VALUES (:uuid, :usuario, :titulo, :mensagem, :link, :enviar_email)");
    $stmt->execute([
        ':uuid' => gerar_uuid(),
        ':usuario' => $usuarioUuid,
        ':titulo' => $titulo,
        ':mensagem' => $mensagem,
        ':link' => $link,
        ':enviar_email' => $enviarEmail ? 1 : 0
    ]);
}

function render($nome, $dados = [])
{
    extract($dados);
    ob_start();
    require __DIR__ . '/../Views/' . $nome . '.php';
    return ob_get_clean();
}