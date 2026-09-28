<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use RuntimeException;

class MigrationRunner
{
    public function __construct(
        private PDO $connection
    ) {
    }

    public function run(string $migrationsPath): void
    {
        $this->createMigrationsTable();

        $files = glob($migrationsPath . '/*.sql');

        if ($files === false) {
            throw new RuntimeException(
                'Não foi possível localizar a pasta de migrations.'
            );
        }

        sort($files);

        foreach ($files as $file) {
            $filename = basename($file);

            if ($this->alreadyExecuted($filename)) {
                continue;
            }

            $sql = file_get_contents($file);

            if ($sql === false) {
                throw new RuntimeException(
                    "Não foi possível ler a migration: {$filename}"
                );
            }

            $this->connection->beginTransaction();

            try {
                $this->connection->exec($sql);

                $statement = $this->connection->prepare(
                    'INSERT INTO migrations (arquivo) VALUES (:arquivo)'
                );

                $statement->execute([
                    'arquivo' => $filename,
                ]);

                $this->connection->commit();

                echo "Migration executada: {$filename}" . PHP_EOL;
            } catch (\Throwable $exception) {
                $this->connection->rollBack();

                throw new RuntimeException(
                    "Erro ao executar a migration: {$filename}",
                    0,
                    $exception
                );
            }
        }
    }

    private function createMigrationsTable(): void
    {
        $sql = <<<SQL
        CREATE TABLE IF NOT EXISTS migrations (
            id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
            arquivo VARCHAR(255) NOT NULL,
            executado_em TIMESTAMPTZ NOT NULL DEFAULT NOW(),

            CONSTRAINT uq_migrations_arquivo UNIQUE (arquivo)
        );
        SQL;

        $this->connection->exec($sql);
    }

    private function alreadyExecuted(string $filename): bool
    {
        $statement = $this->connection->prepare(
            'SELECT 1 FROM migrations WHERE arquivo = :arquivo LIMIT 1'
        );

        $statement->execute([
            'arquivo' => $filename,
        ]);

        return $statement->fetchColumn() !== false;
    }
}