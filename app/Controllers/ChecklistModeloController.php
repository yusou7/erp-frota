<?php

namespace App\Controllers;

use App\Core\Database;
use App\Core\Session;
use App\Repositories\ChecklistModeloRepository;
use App\Services\ChecklistModeloService;
use App\Repositories\TipoVeiculoRepository;
use InvalidArgumentException;

class ChecklistModeloController
{
    private ChecklistModeloService $service;

    public function __construct()
    {
        $database = new Database();

        $repository = new ChecklistModeloRepository($database);

        $this->service = new ChecklistModeloService($repository);
    }

    public function index(): void
    {
        $modelos = $this->service->listarAtivos();

        require __DIR__ . '/../../views/checklists/modelos/index.php';
    }

    public function create(): void
{
    $database = new Database();

$tipoVeiculoRepository = new TipoVeiculoRepository(
    $database->getConnection()
);

    $tiposVeiculos = $tipoVeiculoRepository->listarAtivos();

    require __DIR__ . '/../../views/checklists/modelos/create.php';
}

    public function store(): void
    {
        try {
            $id = $this->service->criar($_POST);

            Session::setFlash(
    'success',
    'Modelo de checklist criado com sucesso.'
);

            header('Location: /checklists/modelos');
            exit;
        } catch (InvalidArgumentException $e) {
            Session::setFlash('error', $e->getMessage());

            header('Location: /checklists/modelos/novo');
            exit;
        } catch (\Throwable $e) {
            Session::setFlash(
    'error',
    'Não foi possível criar o modelo de checklist.'
);

            header('Location: /checklists/modelos/novo');
            exit;
        }
    }
}