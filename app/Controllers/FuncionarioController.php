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
        echo 'Página de funcionários';
    }

    public function store(): void
    {
        echo 'Cadastro de funcionário';
    }
}