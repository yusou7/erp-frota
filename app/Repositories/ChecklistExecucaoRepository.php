<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class ChecklistExecucaoRepository
{
    public function __construct(
        private PDO $connection
    ) {
    }

    public function buscarPorMovimentacao(
    int $movimentacaoId,
    string $tipo
): ?array {
    $sql = <<<SQL
    SELECT
        c.id,
        c.movimentacao_id,
        c.checklist_modelo_id,
        c.usuario_id,
        c.status,
        c.iniciado_em,
        c.finalizado_em,

        m.status_autorizacao_saida,

        cm.nome AS modelo_nome,
        cm.descricao AS modelo_descricao

    FROM checklists c

    INNER JOIN movimentacoes_frota m
        ON m.id = c.movimentacao_id

    INNER JOIN checklist_modelos cm
        ON cm.id = c.checklist_modelo_id

    WHERE c.movimentacao_id = :movimentacao_id
      AND c.tipo = :tipo

    LIMIT 1
    SQL;

    $statement = $this->connection->prepare($sql);

    $statement->execute([
        'movimentacao_id' => $movimentacaoId,
        'tipo' => $tipo,
    ]);

    $checklist = $statement->fetch();

    return $checklist !== false
        ? $checklist
        : null;
}

    public function listarItens(
        int $checklistModeloId
    ): array {
        $sql = <<<SQL
        SELECT
            id,
            checklist_modelo_id,
            descricao,
            ordem,
            obrigatorio,
            ativo

        FROM checklist_itens

        WHERE checklist_modelo_id = :checklist_modelo_id
          AND ativo = TRUE

        ORDER BY ordem ASC, id ASC
        SQL;

        $statement = $this->connection->prepare($sql);

        $statement->execute([
            'checklist_modelo_id' => $checklistModeloId,
        ]);

        return $statement->fetchAll();
    }

    public function salvarResposta(
    int $checklistId,
    int $checklistItemId,
    string $status,
    ?string $observacao
): void {
    $sql = <<<SQL
    INSERT INTO checklist_respostas (
        checklist_id,
        checklist_item_id,
        status,
        observacao
    )
    VALUES (
        :checklist_id,
        :checklist_item_id,
        :status,
        :observacao
    )
    ON CONFLICT (checklist_id, checklist_item_id)
    DO UPDATE SET
        status = EXCLUDED.status,
        observacao = EXCLUDED.observacao,
        respondido_em = NOW()
    SQL;

    $statement = $this->connection->prepare($sql);

    $statement->execute([
        'checklist_id' => $checklistId,
        'checklist_item_id' => $checklistItemId,
        'status' => $status,
        'observacao' => $observacao,
    ]);
}

public function listarRespostas(int $checklistId): array
{
    $sql = <<<SQL
    SELECT
        id,
        checklist_id,
        checklist_item_id,
        status,
        observacao,
        respondido_em
    FROM checklist_respostas
    WHERE checklist_id = :checklist_id
    ORDER BY checklist_item_id ASC
    SQL;

    $statement = $this->connection->prepare($sql);

    $statement->execute([
        'checklist_id' => $checklistId,
    ]);

    return $statement->fetchAll();
}

public function contarItensObrigatoriosPendentes(int $checklistId): int
{
    $sql = <<<SQL
    SELECT COUNT(*)
    FROM checklist_itens ci
    INNER JOIN checklists c
        ON c.checklist_modelo_id = ci.checklist_modelo_id
    LEFT JOIN checklist_respostas cr
        ON cr.checklist_id = c.id
        AND cr.checklist_item_id = ci.id
    WHERE c.id = :checklist_id
      AND ci.ativo = TRUE
      AND ci.obrigatorio = TRUE
      AND cr.id IS NULL
    SQL;

    $statement = $this->connection->prepare($sql);

    $statement->execute([
        'checklist_id' => $checklistId,
    ]);

    return (int) $statement->fetchColumn();
}

public function finalizar(int $checklistId): void
{
    $sql = <<<SQL
    UPDATE checklists
    SET
        status = 'CONCLUIDO',
        finalizado_em = NOW(),
        atualizado_em = NOW()
    WHERE id = :checklist_id
    SQL;

    $statement = $this->connection->prepare($sql);

    $statement->execute([
        'checklist_id' => $checklistId,
    ]);
}

public function buscarDadosDoChecklist(
    int $checklistId
): ?array {
    $sql = <<<SQL
    SELECT
        c.id AS checklist_id,
        c.movimentacao_id,
        c.tipo AS checklist_tipo,
        c.status AS checklist_status,
        m.status AS movimentacao_status,
        m.status_autorizacao_saida
    FROM checklists c
    INNER JOIN movimentacoes_frota m
        ON m.id = c.movimentacao_id
    WHERE c.id = :checklist_id
    LIMIT 1
    SQL;

    $statement = $this->connection->prepare($sql);

    $statement->execute([
        'checklist_id' => $checklistId,
    ]);

    $dados = $statement->fetch();

    return $dados !== false
        ? $dados
        : null;
}
}