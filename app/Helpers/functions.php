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