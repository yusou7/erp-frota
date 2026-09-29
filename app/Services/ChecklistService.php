<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ChecklistRepository;
use App\Repositories\ChecklistExecucaoRepository;
use InvalidArgumentException;

class ChecklistService
{
    public function __construct(
    private ChecklistRepository $repository,
    private ChecklistExecucaoRepository $execucaoRepository
) {
}

    public function buscarModeloAtivoPorTipoVeiculo(
        int $tipoVeiculoId
    ): ?array {
        if ($tipoVeiculoId <= 0) {
            throw new InvalidArgumentException(
                'Tipo de veículo inválido.'
            );
        }

        return $this->repository
            ->buscarModeloAtivoPorTipoVeiculo($tipoVeiculoId);
    }

    public function criar(
    int $movimentacaoId,
    int $checklistModeloId,
    string $tipo = 'SAIDA',
    ?int $usuarioId = null
): int {
    if ($movimentacaoId <= 0) {
        throw new InvalidArgumentException(
            'Movimentação inválida.'
        );
    }

    if ($checklistModeloId <= 0) {
        throw new InvalidArgumentException(
            'Modelo de checklist inválido.'
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

    return $this->repository->criar(
        $movimentacaoId,
        $checklistModeloId,
        $tipo,
        $usuarioId
    );
}

public function listarItens(int $checklistModeloId): array
{
    if ($checklistModeloId <= 0) {
        throw new InvalidArgumentException(
            'Modelo de checklist inválido.'
        );
    }

    return $this->execucaoRepository
        ->listarItens($checklistModeloId);
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
            'Status de checklist inválido.'
        );
    }

    $this->execucaoRepository->salvarResposta(
        $checklistId,
        $checklistItemId,
        $status,
        $observacao
    );
}
}