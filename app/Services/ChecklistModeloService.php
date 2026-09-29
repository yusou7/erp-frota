<?php

namespace App\Services;

use App\Repositories\ChecklistModeloRepository;
use InvalidArgumentException;

class ChecklistModeloService
{
    public function __construct(
        private ChecklistModeloRepository $repository
    ) {
    }

    public function listarAtivos(): array
    {
        return $this->repository->listarAtivos();
    }

    public function validar(array $dados): array
    {
        $nome = trim($dados['nome'] ?? '');
        $descricao = trim($dados['descricao'] ?? '');
        $tipoVeiculoId = (int) ($dados['tipo_veiculo_id'] ?? 0);

        if ($nome === '') {
            throw new InvalidArgumentException(
                'O nome do modelo de checklist é obrigatório.'
            );
        }

        if ($tipoVeiculoId <= 0) {
            throw new InvalidArgumentException(
                'O tipo de veículo é obrigatório.'
            );
        }

        return [
            'nome' => $nome,
            'descricao' => $descricao !== '' ? $descricao : null,
            'tipo_veiculo_id' => $tipoVeiculoId,
        ];
    }

    public function criar(array $dados): int
{
    $dadosValidados = $this->validar($dados);

    return $this->repository->criar($dadosValidados);
}
}