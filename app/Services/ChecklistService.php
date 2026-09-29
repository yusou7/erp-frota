<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ChecklistRepository;
use InvalidArgumentException;

class ChecklistService
{
    public function __construct(
        private ChecklistRepository $repository
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

        return $this->repository->criar(
            $movimentacaoId,
            $checklistModeloId,
            $usuarioId
        );
    }
}