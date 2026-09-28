<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;
use App\Core\Session;
use App\Repositories\FuncaoRepository;
use App\Repositories\FuncionarioRepository;
use App\Services\FuncionarioService;
use InvalidArgumentException;

class FuncionarioController
{
    private FuncionarioService $service;

    private FuncionarioRepository $repository;

     private FuncaoRepository $funcaoRepository;

    public function __construct()
    {
    $database = new Database();

    $connection = $database->getConnection();

    $this->repository = new FuncionarioRepository(
        $connection
    );

    $this->funcaoRepository = new FuncaoRepository(
        $connection
    );

    $this->service = new FuncionarioService(
        $this->repository
    );
    }

    public function index(): void
    {
    $funcionarios = $this->repository->listarTodos();

    require __DIR__ . '/../../views/funcionarios/index.php';
    }

    public function store(): void
    {
    try {
        $this->service->criar($_POST);

        Session::setFlash(
            'success',
            'Funcionário cadastrado com sucesso.'
        );

        header('Location: /funcionarios');
        exit;
    } catch (InvalidArgumentException $exception) {
        Session::setFlash(
            'error',
            $exception->getMessage()
        );

        header('Location: /funcionarios/novo');
        exit;
    }
    }

    public function create(): void
    {
    $funcoes = $this->funcaoRepository->listarAtivas();

    require __DIR__ . '/../../views/funcionarios/create.php';
    }
}