<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ChecklistItemRepository;
use InvalidArgumentException;

class ChecklistItemService
{
    public function __construct(
        private ChecklistItemRepository $repository
    ) {
    }

    public function listarPorModelo(int $modeloId): array
    {
        if ($modeloId <= 0) {
            throw new InvalidArgumentException(
                'Modelo de checklist inválido.'
            );
        }

        return $this->repository->listarPorModelo($modeloId);
    }

    public function validar(array $dados): array
    {
        $modeloId = (int) ($dados['checklist_modelo_id'] ?? 0);
        $descricao = trim($dados['descricao'] ?? '');
        $ordem = (int) ($dados['ordem'] ?? 0);

        $obrigatorio = isset($dados['obrigatorio'])
            ? (bool) $dados['obrigatorio']
            : true;

        if ($modeloId <= 0) {
            throw new InvalidArgumentException(
                'O modelo de checklist é obrigatório.'
            );
        }

        if ($descricao === '') {
            throw new InvalidArgumentException(
                'A descrição do item é obrigatória.'
            );
        }

        if ($ordem <= 0) {
            throw new InvalidArgumentException(
                'A ordem do item deve ser maior que zero.'
            );
        }

        return [
            'checklist_modelo_id' => $modeloId,
            'descricao' => $descricao,
            'ordem' => $ordem,
            'obrigatorio' => $obrigatorio,
        ];
    }

    public function criar(array $dados): int
    {
        $dadosValidados = $this->validar($dados);

        return $this->repository->criar($dadosValidados);
    }

    public function buscarPorId(int $id): ?array
{
    if ($id <= 0) {
        throw new InvalidArgumentException(
            'Item de checklist inválido.'
        );
    }

    return $this->repository->buscarPorId($id);
}

public function atualizar(int $id, array $dados): void
{
    if ($id <= 0) {
        throw new InvalidArgumentException(
            'Item de checklist inválido.'
        );
    }

    $item = $this->repository->buscarPorId($id);

    if ($item === null) {
        throw new InvalidArgumentException(
            'Item de checklist não encontrado.'
        );
    }

    $dadosValidados = $this->validar($dados);

    $this->repository->atualizar(
        $id,
        $dadosValidados
    );
}

public function desativar(int $id): void
{
    if ($id <= 0) {
        throw new InvalidArgumentException(
            'Item de checklist inválido.'
        );
    }

    $item = $this->repository->buscarPorId($id);

    if ($item === null) {
        throw new InvalidArgumentException(
            'Item de checklist não encontrado.'
        );
    }

    if (!$item['ativo']) {
        throw new InvalidArgumentException(
            'Este item já está desativado.'
        );
    }

    $this->repository->desativar($id);
}
}