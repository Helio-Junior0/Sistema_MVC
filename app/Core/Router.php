<?php

namespace App\Core;

class Router
{
    private $rotas = [];

    public function get($caminho, $acao)
    {
        $this->rotas['GET'][$caminho] = $acao;
    }

    public function dispatch()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $metodo = $_SERVER['REQUEST_METHOD'];

        if (isset($this->rotas[$metodo][$uri])) {
            $acao = $this->rotas[$metodo][$uri];

            list($nomeClasse, $nomeMetodo) = explode('@', $acao);
            $classeCompleta = 'App\\Controllers\\' . $nomeClasse;

            $controller = new $classeCompleta();
            $controller->$nomeMetodo();
        } else {
            http_response_code(404);
            echo "404 - rota nao encontrada: " . $uri;
        }
    }

    public function post($caminho, $acao)
    {
        $this->rotas['POST'][$caminho] = $acao;
    }
}