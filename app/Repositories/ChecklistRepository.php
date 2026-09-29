<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class ChecklistRepository
{
    public function __construct(
        private PDO $connection
    ) {
    }

    public function buscarModeloAtivoPorTipoVeiculo(
        int $tipoVeiculoId
    ): ?array {
        $sql = "
            SELECT
                id,
                nome,
                descricao,
                tipo_veiculo_id
            FROM checklist_modelos
            WHERE tipo_veiculo_id = :tipo_veiculo_id
              AND ativo = TRUE
            ORDER BY id ASC
            LIMIT 1
        ";

        $stmt = $this->connection->prepare($sql);

        $stmt->execute([
            'tipo_veiculo_id' => $tipoVeiculoId,
        ]);

        $modelo = $stmt->fetch();

        return $modelo !== false ? $modelo : null;
    }

    public function criar(
    int $movimentacaoId,
    int $checklistModeloId,
    string $tipo = 'SAIDA',
    ?int $usuarioId = null
): int {
    $sql = "
        INSERT INTO checklists (
            movimentacao_id,
            checklist_modelo_id,
            tipo,
            usuario_id,
            status
        )
        VALUES (
            :movimentacao_id,
            :checklist_modelo_id,
            :tipo,
            :usuario_id,
            'EM_ANDAMENTO'
        )
        RETURNING id
    ";

    $stmt = $this->connection->prepare($sql);

    $stmt->execute([
        'movimentacao_id' => $movimentacaoId,
        'checklist_modelo_id' => $checklistModeloId,
        'tipo' => $tipo,
        'usuario_id' => $usuarioId,
    ]);

    return (int) $stmt->fetchColumn();
}

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}