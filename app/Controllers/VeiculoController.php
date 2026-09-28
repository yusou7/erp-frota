<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;
use App\Core\Session;
use App\Repositories\TipoVeiculoRepository;
use App\Repositories\VeiculoRepository;
use App\Services\VeiculoService;
use InvalidArgumentException;

class VeiculoController
{
    private VeiculoService $service;

    private VeiculoRepository $repository;

    private TipoVeiculoRepository $tipoVeiculoRepository;

    public function __construct()
    {
        $database = new Database();

        $connection = $database->getConnection();

        $this->repository = new VeiculoRepository(
            $connection
        );

        $this->tipoVeiculoRepository = new TipoVeiculoRepository(
            $connection
        );

        $this->service = new VeiculoService(
            $this->repository
        );
    }

    public function index(): void
    {
        $veiculos = $this->repository->listarTodos();

        require __DIR__ . '/../../views/veiculos/index.php';
    }

    public function create(): void
    {
        $tiposVeiculos = $this->tipoVeiculoRepository->listarAtivos();

        require __DIR__ . '/../../views/veiculos/create.php';
    }

    public function store(): void
    {
        try {
            $dados = $this->service->validarDados($_POST);

            $this->repository->criar($dados);

            Session::setFlash(
                'success',
                'Veículo cadastrado com sucesso.'
            );

            header('Location: /veiculos');
            exit;
        } catch (InvalidArgumentException $exception) {
            Session::setFlash(
                'error',
                $exception->getMessage()
            );

            header('Location: /veiculos/novo');
            exit;
        }
    }

    public function edit(int $id): void
    {
        $veiculo = $this->repository->buscarPorId($id);

        if ($veiculo === null) {
            http_response_code(404);

            echo 'Veículo não encontrado.';

            return;
        }

        $tiposVeiculos = $this->tipoVeiculoRepository->listarAtivos();

        require __DIR__ . '/../../views/veiculos/edit.php';
    }

    public function update(int $id): void
    {
        try {
            $dados = $this->service->validarDados(
                $_POST,
                $id
            );

            $this->repository->atualizar(
                $id,
                $dados
            );

            Session::setFlash(
                'success',
                'Veículo atualizado com sucesso.'
            );

            header('Location: /veiculos');
            exit;
        } catch (InvalidArgumentException $exception) {
            Session::setFlash(
                'error',
                $exception->getMessage()
            );

            header("Location: /veiculos/{$id}/editar");
            exit;
        }
    }

    public function deactivate(int $id): void
    {
        $veiculo = $this->repository->buscarPorId($id);

        if ($veiculo === null) {
            Session::setFlash(
                'error',
                'Veículo não encontrado.'
            );

            header('Location: /veiculos');
            exit;
        }

        if (!$veiculo['ativo']) {
            Session::setFlash(
                'error',
                'O veículo já está inativo.'
            );

            header('Location: /veiculos');
            exit;
        }

        $this->repository->inativar($id);

        Session::setFlash(
            'success',
            'Veículo inativado com sucesso.'
        );

        header('Location: /veiculos');
        exit;
    }

    
}