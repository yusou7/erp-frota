<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;
use App\Core\Session;
use App\Repositories\MovimentacaoFrotaRepository;
use App\Services\MovimentacaoFrotaService;

class MovimentacaoFrotaController
{
    private MovimentacaoFrotaService $service;

    private MovimentacaoFrotaRepository $repository;

    public function __construct()
    {
        $database = new Database();

        $connection = $database->getConnection();

        $this->repository = new MovimentacaoFrotaRepository(
            $connection
        );

        $this->service = new MovimentacaoFrotaService(
            $this->repository
        );
    }

    public function index(): void
{
    $movimentacoes = $this->repository->listarTodos();

    require __DIR__ . '/../../views/movimentacoes/index.php';
}

public function create(): void
{
    $veiculos = $this->repository
        ->listarVeiculosDisponiveis();

    $carretas = $this->repository
        ->listarCarretasDisponiveis();

    $motoristas = $this->repository
        ->listarMotoristasDisponiveis();

    require __DIR__ . '/../../views/movimentacoes/create.php';
}

public function store(): void
{
    try {
        $this->service->criarSaida($_POST);

        Session::setFlash(
            'success',
            'Saída registrada com sucesso.'
        );

        header('Location: /saidas');
        exit;
    } catch (\InvalidArgumentException $exception) {
        Session::setFlash(
            'error',
            $exception->getMessage()
        );

        header('Location: /saidas/nova');
        exit;
    } catch (\Throwable $exception) {
        Session::setFlash(
            'error',
            'Não foi possível registrar a saída.'
        );

        header('Location: /saidas/nova');
        exit;
    }
}

public function show(int $id): void
{
    $movimentacao = $this->repository->buscarPorId($id);

    if ($movimentacao === null) {
        http_response_code(404);

        echo 'Movimentação não encontrada.';

        return;
    }

    require __DIR__ . '/../../views/movimentacoes/show.php';
}

public function return(int $id): void
{
    try {
        $this->service->registrarRetorno(
            $id,
            $_POST
        );

        Session::setFlash(
            'success',
            'Retorno registrado com sucesso.'
        );

        header("Location: /saidas/{$id}");
        exit;
    } catch (\InvalidArgumentException $exception) {
        Session::setFlash(
            'error',
            $exception->getMessage()
        );

        header("Location: /saidas/{$id}");
        exit;
    } catch (\Throwable $exception) {
        Session::setFlash(
            'error',
            'Não foi possível registrar o retorno.'
        );

        header("Location: /saidas/{$id}");
        exit;
    }
}

public function cancelar(int $id): void
{
    try {
        $this->service->cancelar($id);

        Session::setFlash(
            'success',
            'Movimentação cancelada com sucesso.'
        );

        header('Location: /saidas');
        exit;
    } catch (\InvalidArgumentException $exception) {
        Session::setFlash(
            'error',
            $exception->getMessage()
        );

        header("Location: /saidas/{$id}");
        exit;
    } catch (\Throwable $exception) {
        Session::setFlash(
            'error',
            'Não foi possível cancelar a movimentação.'
        );

        header("Location: /saidas/{$id}");
        exit;
    }
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