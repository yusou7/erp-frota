<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;
use App\Services\ChecklistExecucaoService;
use App\Repositories\ChecklistExecucaoRepository;

class ChecklistExecucaoController
{
    private ChecklistExecucaoService $service;

    public function __construct()
    {
        $database = new Database();
        $connection = $database->getConnection();

        $repository = new ChecklistExecucaoRepository(
            $connection
        );

        $this->service = new ChecklistExecucaoService(
            $repository
        );
    }

    public function show(
    int $movimentacaoId,
    string $tipo = 'SAIDA'
): void
    {
        $checklist = $this->service
    ->buscarPorMovimentacao(
        $movimentacaoId,
        $tipo
    );

        if ($checklist === null) {
            http_response_code(404);
            echo 'Checklist não encontrado.';
            return;
        }

        $itens = $this->service
            ->listarItens(
                (int) $checklist['checklist_modelo_id']
            );

        $respostas = $this->service
            ->listarRespostas(
                (int) $checklist['id']
            );

        require __DIR__ . '/../../views/checklists/execucao/show.php';
    }

    public function salvarResposta(
    int $movimentacaoId,
    string $tipo = 'SAIDA'
): void
    {
        $checklist = $this->service
    ->buscarPorMovimentacao(
        $movimentacaoId,
        $tipo
    );

        if ($checklist === null) {
            http_response_code(404);
            echo 'Checklist não encontrado.';
            return;
        }

        $checklistId = (int) $checklist['id'];

        $checklistItemId = (int) (
            $_POST['checklist_item_id'] ?? 0
        );

        $status = trim(
            (string) ($_POST['status'] ?? '')
        );

        $observacao = $_POST['observacao'] ?? null;

        try {
            $this->service->salvarResposta(
                $checklistId,
                $checklistItemId,
                $status,
                $observacao
            );

            \App\Core\Session::setFlash(
                'success',
                'Resposta salva com sucesso.'
            );

            header(
                'Location: /saidas/'
                . $movimentacaoId
                . '/checklist'
            );

            exit;

        } catch (\InvalidArgumentException $exception) {

            \App\Core\Session::setFlash(
                'error',
                $exception->getMessage()
            );

            header(
                'Location: /saidas/'
                . $movimentacaoId
                . '/checklist'
            );

            exit;
        }
    }

    public function finalizar(
    int $movimentacaoId,
    string $tipo = 'SAIDA'
): void
{
    $checklist = $this->service
    ->buscarPorMovimentacao(
        $movimentacaoId,
        $tipo
    );

    if ($checklist === null) {
        http_response_code(404);
        echo 'Checklist não encontrado.';
        return;
    }

    try {
        $this->service->finalizar(
            (int) $checklist['id']
        );

        \App\Core\Session::setFlash(
            'success',
            'Checklist finalizado com sucesso.'
        );

        header(
            'Location: /saidas/'
            . $movimentacaoId
            . '/checklist'
        );

        exit;

    } catch (\InvalidArgumentException $exception) {

        \App\Core\Session::setFlash(
            'error',
            $exception->getMessage()
        );

        header(
            'Location: /saidas/'
            . $movimentacaoId
            . '/checklist'
        );

        exit;
    }
}
}