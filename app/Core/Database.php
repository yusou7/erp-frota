<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use RuntimeException;

class Database
{
    private PDO $connection;

    public function __construct()
    {
        $host = $_ENV['DB_HOST'] ?? '';
        $port = $_ENV['DB_PORT'] ?? '5432';
        $database = $_ENV['DB_DATABASE'] ?? '';
        $username = $_ENV['DB_USERNAME'] ?? '';
        $password = $_ENV['DB_PASSWORD'] ?? '';

        if (
            $host === '' ||
            $database === '' ||
            $username === '' ||
            $password === ''
        ) {
            throw new RuntimeException(
                'As configurações do banco de dados não foram preenchidas.'
            );
        }

        $dsn = "pgsql:host={$host};port={$port};dbname={$database}";

        try {
            $this->connection = new PDO(
                $dsn,
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $exception) {
            throw new RuntimeException(
                'Não foi possível conectar ao banco de dados.',
                0,
                $exception
            );
        }
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}