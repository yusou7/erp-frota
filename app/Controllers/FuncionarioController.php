<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;
use App\Repositories\FuncionarioRepository;
use App\Services\FuncionarioService;

class FuncionarioController
{
    private FuncionarioService $service;

    public function __construct()
    {
        $database = new Database();

        $repository = new FuncionarioRepository(
            $database->getConnection()
        );

        $this->service = new FuncionarioService($repository);
    }

    public function index(): void
    {
        require __DIR__ . '/../../views/funcionarios/index.php';
    }

    public function store(): void
    {
        echo 'Cadastro de funcionário';
    }
}