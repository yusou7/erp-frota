<?php

declare(strict_types=1);

use App\Controllers\FuncionarioController;
use App\Controllers\VeiculoController;
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
};