<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ChecklistExecucaoRepository;
use InvalidArgumentException;

class ChecklistExecucaoService
{
    public function __construct(
        private ChecklistExecucaoRepository $repository
    ) {
    }

    public function buscarPorMovimentacao(
    int $movimentacaoId,
    string $tipo
): ?array {
    if ($movimentacaoId <= 0) {
        throw new InvalidArgumentException(
            'Movimentação inválida.'
        );
    }

    $tiposPermitidos = [
        'SAIDA',
        'RETORNO',
    ];

    if (!in_array($tipo, $tiposPermitidos, true)) {
        throw new InvalidArgumentException(
            'Tipo de checklist inválido.'
        );
    }

    return $this->repository->buscarPorMovimentacao(
        $movimentacaoId,
        $tipo
    );
}

    public function listarItens(
        int $checklistModeloId
    ): array {
        if ($checklistModeloId <= 0) {
            throw new InvalidArgumentException(
                'Modelo de checklist inválido.'
            );
        }

        return $this->repository->listarItens(
            $checklistModeloId
        );
    }

    public function salvarResposta(
    int $checklistId,
    int $checklistItemId,
    string $status,
    ?string $observacao
): void {
    if ($checklistId <= 0) {
        throw new InvalidArgumentException(
            'Checklist inválido.'
        );
    }

    if ($checklistItemId <= 0) {
        throw new InvalidArgumentException(
            'Item de checklist inválido.'
        );
    }

    $statusPermitidos = [
        'OK',
        'DEFEITO',
        'NAO_APLICA',
    ];

    if (!in_array($status, $statusPermitidos, true)) {
        throw new InvalidArgumentException(
            'Status de resposta inválido.'
        );
    }

    if ($observacao !== null) {
        $observacao = trim($observacao);

        if ($observacao === '') {
            $observacao = null;
        }
    }

    $this->repository->salvarResposta(
        $checklistId,
        $checklistItemId,
        $status,
        $observacao
    );
}

public function listarRespostas(int $checklistId): array
{
    if ($checklistId <= 0) {
        throw new InvalidArgumentException(
            'Checklist inválido.'
        );
    }

    return $this->repository->listarRespostas(
        $checklistId
    );
}

public function contarItensObrigatoriosPendentes(
    int $checklistId
): int {
    if ($checklistId <= 0) {
        throw new InvalidArgumentException(
            'Checklist inválido.'
        );
    }

    return $this->repository
        ->contarItensObrigatoriosPendentes(
            $checklistId
        );
}

public function finalizar(int $checklistId): void
{
    if ($checklistId <= 0) {
        throw new InvalidArgumentException(
            'Checklist inválido.'
        );
    }

    $pendentes = $this->contarItensObrigatoriosPendentes(
        $checklistId
    );

    if ($pendentes > 0) {
        throw new InvalidArgumentException(
            'Existem itens obrigatórios sem resposta.'
        );
    }

    $this->repository->finalizar($checklistId);
}
}