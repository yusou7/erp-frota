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

    public function update(int $id): void
    {
    try {
        $this->service->atualizar($id, $_POST);

        Session::setFlash(
            'success',
            'Funcionário atualizado com sucesso.'
        );

        header('Location: /funcionarios');
        exit;
    } catch (InvalidArgumentException $exception) {
        Session::setFlash(
            'error',
            $exception->getMessage()
        );

        header("Location: /funcionarios/{$id}/editar");
        exit;
    }
    }

    public function deactivate(int $id): void
{
    try {
        $this->service->inativar($id);

        Session::setFlash(
            'success',
            'Funcionário inativado com sucesso.'
        );

        header('Location: /funcionarios');
        exit;
    } catch (InvalidArgumentException $exception) {
        Session::setFlash(
            'error',
            $exception->getMessage()
        );

        header('Location: /funcionarios');
        exit;
    }
}

    public function create(): void
    {
    $funcoes = $this->funcaoRepository->listarAtivas();

    require __DIR__ . '/../../views/funcionarios/create.php';
    }

    public function edit(int $id): void
    {
    $funcionario = $this->repository->buscarPorId($id);

    if ($funcionario === null) {
        http_response_code(404);

        echo 'Funcionário não encontrado.';

        return;
    }

    $funcoes = $this->funcaoRepository->listarAtivas();

    require __DIR__ . '/../../views/funcionarios/edit.php';
    }

    public function retorno(int $id): void
{
    $movimentacao = $this->repository->buscarPorId($id);

    if ($movimentacao === null) {
        http_response_code(404);

        echo 'Movimentação não encontrada.';

        return;
    }

    if ($movimentacao['status'] !== 'ABERTA') {
        Session::setFlash(
            'error',
            'Somente uma movimentação aberta pode receber retorno.'
        );

        header("Location: /saidas/{$id}");
        exit;
    }

    require __DIR__ . '/../../views/movimentacoes/retorno.php';
}
}