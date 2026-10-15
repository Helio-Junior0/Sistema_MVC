<?php

namespace App\Core;

use PDO;

class Database
{
    private static $instancia = null;

    private function __construct() {}

    public static function getInstance()
    {
        if (self::$instancia === null) {
            $host = $_ENV['DB_HOST'];
            $banco = $_ENV['DB_NAME'];
            $usuario = $_ENV['DB_USER'];
            $senha = $_ENV['DB_PASS'];

            $dsn = "mysql:host=$host;dbname=$banco;charset=utf8mb4";

            self::$instancia = new PDO($dsn, $usuario, $senha);
            self::$instancia->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instancia->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }

        return self::$instancia;
    }
}
