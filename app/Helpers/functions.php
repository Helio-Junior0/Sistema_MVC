<?php

function view($nome, $dados = [])
{
    $caminho = __DIR__ . '/../Views/' . $nome . '.php';

    if (file_exists($caminho)) {
        extract($dados);
        require $caminho;
    } else {
        echo "View nao encontrada: " . $nome;
    }
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