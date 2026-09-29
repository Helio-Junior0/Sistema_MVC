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

        $rotasDoMetodo = $this->rotas[$metodo] ?? [];

        foreach ($rotasDoMetodo as $rota => $acao) {

            $padrao = preg_replace('/\{[a-zA-Z_]+\}/', '([^/]+)', $rota);
            $padrao = '#^' . $padrao . '$#';

            if (preg_match($padrao, $uri, $matches)) {

                array_shift($matches);

                list($nomeClasse, $nomeMetodo) = explode('@', $acao);
                $classeCompleta = 'App\\Controllers\\' . $nomeClasse;

                $controller = new $classeCompleta();

                call_user_func_array([$controller, $nomeMetodo], $matches);
                return;
            }
        }

        http_response_code(404);
        echo "404 - rota nao encontrada: " . $uri;
    }

    public function post($caminho, $acao)
    {
        $this->rotas['POST'][$caminho] = $acao;
    }
}
