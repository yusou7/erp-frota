<?php

declare(strict_types=1);

use App\Controllers\FuncionarioController;
use App\Core\Router;

return function (Router $router): void {
    $router->get('/', function (): void {
        echo 'ERP Frota';
    });

    $router->get('/funcionarios', function (): void {
        $controller = new FuncionarioController();

        $controller->index();
    });

    $router->post('/funcionarios', function (): void {
        $controller = new FuncionarioController();

        $controller->store();
    });
};