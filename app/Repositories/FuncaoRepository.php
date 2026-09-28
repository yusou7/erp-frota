<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class FuncaoRepository
{
    public function __construct(
        private PDO $connection
    ) {
    }

    public function listarAtivas(): array
    {
        $sql = <<<SQL
        SELECT
            id,
            nome
        FROM funcoes
        WHERE ativo = TRUE
        ORDER BY nome ASC
        SQL;

        $statement = $this->connection->query($sql);

        return $statement->fetchAll();
    }
}