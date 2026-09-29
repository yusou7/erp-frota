<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;
use App\Core\Session;
use App\Repositories\ChecklistItemRepository;
use App\Services\ChecklistItemService;
use InvalidArgumentException;

class ChecklistItemController
{
    private ChecklistItemService $service;

    public function __construct()
    {
        $database = new Database();

        $repository = new ChecklistItemRepository($database);

        $this->service = new ChecklistItemService($repository);
    }

    public function index(int $modeloId): void
    {
        $itens = $this->service->listarPorModelo($modeloId);

        require __DIR__ . '/../../views/checklists/itens/index.php';
    }

    public function create(int $modeloId): void
    {
        require __DIR__ . '/../../views/checklists/itens/create.php';
    }

    public function store(int $modeloId): void
    {
        try {
            $dados = $_POST;

            $dados['checklist_modelo_id'] = $modeloId;

            $this->service->criar($dados);

            Session::setFlash(
                'success',
                'Item de checklist criado com sucesso.'
            );

            header(
                'Location: /checklists/modelos/' . $modeloId . '/itens'
            );
            exit;
        } catch (InvalidArgumentException $e) {
            Session::setFlash(
                'error',
                $e->getMessage()
            );

            header(
                'Location: /checklists/modelos/'
                . $modeloId
                . '/itens/novo'
            );
            exit;
        } catch (\Throwable $e) {
            Session::setFlash(
                'error',
                'Não foi possível criar o item de checklist.'
            );

            header(
                'Location: /checklists/modelos/'
                . $modeloId
                . '/itens/novo'
            );
            exit;
        }
    }

    public function edit(int $modeloId, int $itemId): void
{
    $item = $this->service->buscarPorId($itemId);

    if ($item === null) {
        Session::setFlash(
            'error',
            'Item de checklist não encontrado.'
        );

        header(
            'Location: /checklists/modelos/'
            . $modeloId
            . '/itens'
        );
        exit;
    }

    require __DIR__ . '/../../views/checklists/itens/edit.php';
}

public function update(int $modeloId, int $itemId): void
{
    try {
        $dados = $_POST;

        $dados['checklist_modelo_id'] = $modeloId;

        $this->service->atualizar(
            $itemId,
            $dados
        );

        Session::setFlash(
            'success',
            'Item de checklist atualizado com sucesso.'
        );

        header(
            'Location: /checklists/modelos/'
            . $modeloId
            . '/itens'
        );
        exit;
    } catch (InvalidArgumentException $e) {
        Session::setFlash(
            'error',
            $e->getMessage()
        );

        header(
            'Location: /checklists/modelos/'
            . $modeloId
            . '/itens/'
            . $itemId
            . '/editar'
        );
        exit;
    } catch (\Throwable $e) {
        Session::setFlash(
            'error',
            'Não foi possível atualizar o item de checklist.'
        );

        header(
            'Location: /checklists/modelos/'
            . $modeloId
            . '/itens/'
            . $itemId
            . '/editar'
        );
        exit;
    }
}

public function deactivate(int $modeloId, int $itemId): void
{
    try {
        $this->service->desativar($itemId);

        Session::setFlash(
            'success',
            'Item de checklist desativado com sucesso.'
        );
    } catch (InvalidArgumentException $e) {
        Session::setFlash(
            'error',
            $e->getMessage()
        );
    } catch (\Throwable $e) {
        Session::setFlash(
            'error',
            'Não foi possível desativar o item de checklist.'
        );
    }

    header(
        'Location: /checklists/modelos/'
        . $modeloId
        . '/itens'
    );
    exit;
}
}