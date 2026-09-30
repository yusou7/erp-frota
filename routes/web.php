<?php

declare(strict_types=1);

use App\Controllers\FuncionarioController;
use App\Controllers\VeiculoController;
use App\Controllers\MovimentacaoFrotaController;
use App\Controllers\ChecklistModeloController;
use App\Controllers\ChecklistItemController;
use App\Controllers\ChecklistExecucaoController;
use App\Core\Router;

return function (Router $router): void {
    $router->get('/', function (): void {
        echo 'ERP Frota';
    });

    $router->get('/funcionarios', function (): void {
        $controller = new FuncionarioController();

        $controller->index();
    });

    $router->get('/funcionarios/novo', function (): void {
    $controller = new FuncionarioController();

    $controller->create();
    });

    $router->post('/funcionarios', function (): void {
        $controller = new FuncionarioController();

        $controller->store();
    });

    $router->get('/funcionarios/{id}/editar', function (string $id): void {
    $controller = new FuncionarioController();

    $controller->edit((int) $id);
});

    $router->post('/funcionarios/{id}', function (string $id): void {
    $controller = new FuncionarioController();

    $controller->update((int) $id);
});

$router->post('/funcionarios/{id}/inativar', function (string $id): void {
    $controller = new FuncionarioController();

    $controller->deactivate((int) $id);
});

$router->get('/veiculos', function (): void {
    $controller = new VeiculoController();

    $controller->index();
});

$router->get('/veiculos/novo', function (): void {
    $controller = new VeiculoController();

    $controller->create();
});

$router->post('/veiculos', function (): void {
    $controller = new VeiculoController();

    $controller->store();
});

$router->get('/veiculos/{id}/editar', function (string $id): void {
    $controller = new VeiculoController();

    $controller->edit((int) $id);
});

$router->post('/veiculos/{id}', function (string $id): void {
    $controller = new VeiculoController();

    $controller->update((int) $id);
});

$router->post('/veiculos/{id}/inativar', function (string $id): void {
    $controller = new VeiculoController();

    $controller->deactivate((int) $id);
});


$router->get(
    '/saidas',
    fn() => (new MovimentacaoFrotaController())->index()
);

$router->get(
    '/saidas/nova',
    fn() => (new MovimentacaoFrotaController())->create()
);

$router->post(
    '/saidas',
    fn() => (new MovimentacaoFrotaController())->store()
);

$router->post(
    '/saidas/{movimentacaoId}/autorizar',
    fn(int $movimentacaoId) =>
        (new MovimentacaoFrotaController())
            ->autorizarSaida($movimentacaoId)
);

$router->get(
    '/saidas/checklist-veiculo/{veiculoId}',
    fn(int $veiculoId) =>
        (new MovimentacaoFrotaController())
            ->checklistVeiculo($veiculoId)
);

$router->get(
    '/saidas/{id}/retorno',
    fn(int $id) => (new MovimentacaoFrotaController())->retorno($id)
);

$router->post(
    '/saidas/{id}/retorno',
    fn(int $id) => (new MovimentacaoFrotaController())->return($id)
);

$router->post(
    '/saidas/{id}/cancelar',
    fn(int $id) => (new MovimentacaoFrotaController())->cancelar($id)
);

$router->get(
    '/saidas/{id}',
    fn(int $id) => (new MovimentacaoFrotaController())->show($id)
);

$router->get(
    '/checklists/modelos',
    fn() => (new ChecklistModeloController())->index()
);

$router->get(
    '/checklists/modelos/novo',
    fn() => (new ChecklistModeloController())->create()
);

$router->post(
    '/checklists/modelos',
    fn() => (new ChecklistModeloController())->store()
);

$router->get(
    '/checklists/modelos/{modeloId}/itens',
    fn(int $modeloId) => (new ChecklistItemController())->index($modeloId)
);

$router->get(
    '/checklists/modelos/{modeloId}/itens/novo',
    fn(int $modeloId) => (new ChecklistItemController())->create($modeloId)
);

$router->post(
    '/checklists/modelos/{modeloId}/itens',
    fn(int $modeloId) => (new ChecklistItemController())->store($modeloId)
);

$router->get(
    '/checklists/modelos/{modeloId}/itens/{itemId}/editar',
    fn(int $modeloId, int $itemId) =>
        (new ChecklistItemController())->edit(
            $modeloId,
            $itemId
        )
);

$router->post(
    '/checklists/modelos/{modeloId}/itens/{itemId}',
    fn(int $modeloId, int $itemId) =>
        (new ChecklistItemController())->update(
            $modeloId,
            $itemId
        )
);

$router->post(
    '/checklists/modelos/{modeloId}/itens/{itemId}/desativar',
    fn(int $modeloId, int $itemId) =>
        (new ChecklistItemController())->deactivate(
            $modeloId,
            $itemId
        )
);

$router->get(
    '/saidas/{movimentacaoId}/checklist',
    fn(int $movimentacaoId) =>
        (new ChecklistExecucaoController())->show($movimentacaoId)
);

$router->get(
    '/saidas/{movimentacaoId}/checklist/retorno',
    fn(int $movimentacaoId) =>
        (new ChecklistExecucaoController())
            ->show($movimentacaoId, 'RETORNO')
);

$router->post(
    '/saidas/{movimentacaoId}/checklist/resposta',
    fn(int $movimentacaoId) =>
        (new ChecklistExecucaoController())
            ->salvarResposta($movimentacaoId)
);

$router->post(
    '/saidas/{movimentacaoId}/checklist/finalizar',
    fn(int $movimentacaoId) =>
        (new ChecklistExecucaoController())
            ->finalizar($movimentacaoId)
);

$router->post(
    '/saidas/{movimentacaoId}/checklist/retorno/resposta',
    fn(int $movimentacaoId) =>
        (new ChecklistExecucaoController())
            ->salvarResposta($movimentacaoId, 'RETORNO')
);

$router->post(
    '/saidas/{movimentacaoId}/checklist/retorno/finalizar',
    fn(int $movimentacaoId) =>
        (new ChecklistExecucaoController())
            ->finalizar($movimentacaoId, 'RETORNO')
);
};