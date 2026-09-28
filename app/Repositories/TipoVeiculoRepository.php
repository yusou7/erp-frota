<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class TipoVeiculoRepository
{
    public function __construct(
        private PDO $connection
    ) {
    }

    public function listarAtivos(): array
    {
        $sql = <<<SQL
        SELECT
            id,
            nome
        FROM tipos_veiculos
        WHERE ativo = TRUE
        ORDER BY nome ASC
        SQL;

        $statement = $this->connection->query($sql);

        return $statement->fetchAll();
    }
}