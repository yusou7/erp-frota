<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class ChecklistModeloRepository
{
    private PDO $db;

    public function __construct(Database $database)
    {
        $this->db = $database->getConnection();
    }

    public function listarAtivos(): array
{
    $sql = "
        SELECT
            cm.id,
            cm.nome,
            cm.descricao,
            cm.tipo_veiculo_id,
            tv.nome AS tipo_veiculo_nome,
            cm.ativo
        FROM checklist_modelos cm
        INNER JOIN tipos_veiculos tv
            ON tv.id = cm.tipo_veiculo_id
        WHERE cm.ativo = TRUE
        ORDER BY cm.nome
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll();
}

public function criar(array $dados): int
{
    $sql = "
        INSERT INTO checklist_modelos (
            nome,
            descricao,
            tipo_veiculo_id
        )
        VALUES (
            :nome,
            :descricao,
            :tipo_veiculo_id
        )
        RETURNING id
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        'nome' => $dados['nome'],
        'descricao' => $dados['descricao'],
        'tipo_veiculo_id' => $dados['tipo_veiculo_id'],
    ]);

    return (int) $stmt->fetchColumn();
}
}