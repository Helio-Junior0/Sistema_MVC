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

function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

function verificar_csrf()
{
    $tokenSessao = $_SESSION['csrf_token'] ?? '';
    $tokenPost = $_POST['csrf_token'] ?? '';

    if (!$tokenSessao || !$tokenPost || !hash_equals($tokenSessao, $tokenPost)) {
        http_response_code(403);
        echo "Sua sessao expirou ou a requisicao e invalida. Volte, recarregue a pagina e tente novamente.";
        exit;
    }
}

function validar_cpf($cpf)
{
    $cpf = preg_replace('/\D/', '', $cpf);

    if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
        return false;
    }

    // Calcula os dois digitos verificadores (posicoes 9 e 10)
    for ($t = 9; $t < 11; $t++) {
        $soma = 0;
        for ($i = 0; $i < $t; $i++) {
            $soma += $cpf[$i] * (($t + 1) - $i);
        }
        $digito = ((10 * $soma) % 11) % 10;

        if ((int) $cpf[$t] !== $digito) {
            return false;
        }
    }

    return true;
}

function validar_cnpj($cnpj)
{
    $cnpj = preg_replace('/\D/', '', $cnpj);

    if (strlen($cnpj) !== 14 || preg_match('/^(\d)\1{13}$/', $cnpj)) {
        return false;
    }

    $pesos1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
    $pesos2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

    // Primeiro digito verificador
    $soma = 0;
    for ($i = 0; $i < 12; $i++) {
        $soma += $cnpj[$i] * $pesos1[$i];
    }
    $resto = $soma % 11;
    $digito1 = $resto < 2 ? 0 : 11 - $resto;

    if ((int) $cnpj[12] !== $digito1) {
        return false;
    }

    // Segundo digito verificador
    $soma = 0;
    for ($i = 0; $i < 13; $i++) {
        $soma += $cnpj[$i] * $pesos2[$i];
    }
    $resto = $soma % 11;
    $digito2 = $resto < 2 ? 0 : 11 - $resto;

    return (int) $cnpj[13] === $digito2;
}

function validar_cpf_cnpj($documento)
{
    $documento = preg_replace('/\D/', '', $documento);

    if (strlen($documento) === 11) {
        return validar_cpf($documento);
    }

    if (strlen($documento) === 14) {
        return validar_cnpj($documento);
    }

    return false;
}