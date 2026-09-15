<?php

function view($nome)
{
    $caminho = __DIR__ . '/../Views/' . $nome . '.php';

    if (file_exists($caminho)) {
        require $caminho;
    } else { 
        echo "View não encontrada: " . $nome;
    }
}