<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Database;
use App\Core\SeedRunner;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

try {
    $database = new Database();

    $runner = new SeedRunner(
        $database->getConnection()
    );

    $runner->run(
        __DIR__ . '/../database/seeds'
    );

    echo 'Seeds concluídos com sucesso!' . PHP_EOL;
} catch (Throwable $exception) {
    echo 'Erro: ' . $exception->getMessage() . PHP_EOL;

    exit(1);
}