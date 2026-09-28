<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use RuntimeException;

class SeedRunner
{
    public function __construct(
        private PDO $connection
    ) {
    }

    public function run(string $seedsPath): void
    {
        $files = glob($seedsPath . '/*.sql');

        if ($files === false) {
            throw new RuntimeException(
                'Não foi possível localizar a pasta de seeds.'
            );
        }

        sort($files);

        foreach ($files as $file) {
            $filename = basename($file);

            $sql = file_get_contents($file);

            if ($sql === false) {
                throw new RuntimeException(
                    "Não foi possível ler o seed: {$filename}"
                );
            }

            $this->connection->beginTransaction();

            try {
                $this->connection->exec($sql);

                $this->connection->commit();

                echo "Seed executado: {$filename}" . PHP_EOL;
            } catch (\Throwable $exception) {
                $this->connection->rollBack();

                throw new RuntimeException(
                    "Erro ao executar o seed: {$filename}",
                    0,
                    $exception
                );
            }
        }
    }
}