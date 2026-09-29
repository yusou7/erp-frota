<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

class ChecklistItemRepository
{
    private PDO $db;

    public function __construct(Database $database)
    {
        $this->db = $database->getConnection();
    }

    public function listarPorModelo(int $modeloId): array
    {
        $sql = "
            SELECT
                id,
                checklist_modelo_id,
                descricao,
                ordem,
                obrigatorio,
                ativo
            FROM checklist_itens
            WHERE checklist_modelo_id = :modelo_id
              AND ativo = TRUE
            ORDER BY ordem ASC, id ASC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'modelo_id' => $modeloId,
        ]);

        return $stmt->fetchAll();
    }

    public function criar(array $dados): int
    {
        $sql = "
            INSERT INTO checklist_itens (
                checklist_modelo_id,
                descricao,
                ordem,
                obrigatorio
            )
            VALUES (
                :checklist_modelo_id,
                :descricao,
                :ordem,
                :obrigatorio
            )
            RETURNING id
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'checklist_modelo_id' => $dados['checklist_modelo_id'],
            'descricao' => $dados['descricao'],
            'ordem' => $dados['ordem'],
            'obrigatorio' => $dados['obrigatorio'],
        ]);

        return (int) $stmt->fetchColumn();
    }

    public function buscarPorId(int $id): ?array
{
    $sql = "
        SELECT
            id,
            checklist_modelo_id,
            descricao,
            ordem,
            obrigatorio,
            ativo
        FROM checklist_itens
        WHERE id = :id
        LIMIT 1
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        'id' => $id,
    ]);

    $item = $stmt->fetch();

    return $item !== false ? $item : null;
}

public function atualizar(int $id, array $dados): void
{
    $sql = "
        UPDATE checklist_itens
        SET
            descricao = :descricao,
            ordem = :ordem,
            obrigatorio = :obrigatorio,
            atualizado_em = NOW()
        WHERE id = :id
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        'id' => $id,
        'descricao' => $dados['descricao'],
        'ordem' => $dados['ordem'],
        'obrigatorio' => $dados['obrigatorio'],
    ]);
}

public function desativar(int $id): void
{
    $sql = "
        UPDATE checklist_itens
        SET
            ativo = FALSE,
            atualizado_em = NOW()
        WHERE id = :id
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        'id' => $id,
    ]);
}
}