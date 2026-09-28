<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class VeiculoRepository
{
    public function __construct(
        private PDO $connection
    ) {
    }

    public function listarTodos(): array
    {
        $sql = <<<SQL
        SELECT
            v.id,
            v.numero,
            v.placa,
            v.renavam,
            v.marca,
            v.modelo,
            v.ano_fabricacao,
            v.ano_modelo,
            v.cor,
            v.combustivel,
            v.indicador_tipo,
            v.indicador_atual,
            v.observacoes,
            v.ativo,
            tv.nome AS tipo_veiculo
        FROM veiculos v
        INNER JOIN tipos_veiculos tv
            ON tv.id = v.tipo_veiculo_id
        ORDER BY v.numero ASC
        SQL;

        $statement = $this->connection->query($sql);

        return $statement->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $sql = <<<SQL
        SELECT
            id,
            numero,
            placa,
            renavam,
            marca,
            modelo,
            ano_fabricacao,
            ano_modelo,
            cor,
            tipo_veiculo_id,
            combustivel,
            indicador_tipo,
            indicador_atual,
            observacoes,
            ativo
        FROM veiculos
        WHERE id = :id
        LIMIT 1
        SQL;

        $statement = $this->connection->prepare($sql);

        $statement->execute([
            'id' => $id,
        ]);

        $veiculo = $statement->fetch();

        return $veiculo !== false ? $veiculo : null;
    }

    public function buscarPorNumero(string $numero): ?array
    {
        $sql = <<<SQL
        SELECT
            id,
            numero
        FROM veiculos
        WHERE numero = :numero
        LIMIT 1
        SQL;

        $statement = $this->connection->prepare($sql);

        $statement->execute([
            'numero' => $numero,
        ]);

        $veiculo = $statement->fetch();

        return $veiculo !== false ? $veiculo : null;
    }

    public function buscarPorPlaca(string $placa): ?array
    {
        $sql = <<<SQL
        SELECT
            id,
            placa
        FROM veiculos
        WHERE placa = :placa
        LIMIT 1
        SQL;

        $statement = $this->connection->prepare($sql);

        $statement->execute([
            'placa' => $placa,
        ]);

        $veiculo = $statement->fetch();

        return $veiculo !== false ? $veiculo : null;
    }

    public function criar(array $dados): int
{
    $sql = <<<SQL
    INSERT INTO veiculos (
        numero,
        placa,
        renavam,
        marca,
        modelo,
        ano_fabricacao,
        ano_modelo,
        cor,
        tipo_veiculo_id,
        combustivel,
        indicador_tipo,
        indicador_atual,
        observacoes,
        ativo
    )
    VALUES (
        :numero,
        :placa,
        :renavam,
        :marca,
        :modelo,
        :ano_fabricacao,
        :ano_modelo,
        :cor,
        :tipo_veiculo_id,
        :combustivel,
        :indicador_tipo,
        :indicador_atual,
        :observacoes,
        TRUE
    )
    RETURNING id
    SQL;

    $statement = $this->connection->prepare($sql);

    $statement->execute([
        'numero' => $dados['numero'],
        'placa' => $dados['placa'],
        'renavam' => $dados['renavam'],
        'marca' => $dados['marca'],
        'modelo' => $dados['modelo'],
        'ano_fabricacao' => $dados['ano_fabricacao'],
        'ano_modelo' => $dados['ano_modelo'],
        'cor' => $dados['cor'],
        'tipo_veiculo_id' => $dados['tipo_veiculo_id'],
        'combustivel' => $dados['combustivel'],
        'indicador_tipo' => $dados['indicador_tipo'],
        'indicador_atual' => $dados['indicador_atual'],
        'observacoes' => $dados['observacoes'],
    ]);

    return (int) $statement->fetchColumn();
}

public function atualizar(int $id, array $dados): void
{
    $sql = <<<SQL
    UPDATE veiculos
    SET
        numero = :numero,
        placa = :placa,
        renavam = :renavam,
        marca = :marca,
        modelo = :modelo,
        ano_fabricacao = :ano_fabricacao,
        ano_modelo = :ano_modelo,
        cor = :cor,
        tipo_veiculo_id = :tipo_veiculo_id,
        combustivel = :combustivel,
        indicador_tipo = :indicador_tipo,
        indicador_atual = :indicador_atual,
        observacoes = :observacoes,
        atualizado_em = NOW()
    WHERE id = :id
    SQL;

    $statement = $this->connection->prepare($sql);

    $statement->execute([
        'id' => $id,
        'numero' => $dados['numero'],
        'placa' => $dados['placa'],
        'renavam' => $dados['renavam'],
        'marca' => $dados['marca'],
        'modelo' => $dados['modelo'],
        'ano_fabricacao' => $dados['ano_fabricacao'],
        'ano_modelo' => $dados['ano_modelo'],
        'cor' => $dados['cor'],
        'tipo_veiculo_id' => $dados['tipo_veiculo_id'],
        'combustivel' => $dados['combustivel'],
        'indicador_tipo' => $dados['indicador_tipo'],
        'indicador_atual' => $dados['indicador_atual'],
        'observacoes' => $dados['observacoes'],
    ]);
}

public function inativar(int $id): void
{
    $sql = <<<SQL
    UPDATE veiculos
    SET
        ativo = FALSE,
        atualizado_em = NOW()
    WHERE id = :id
    SQL;

    $statement = $this->connection->prepare($sql);

    $statement->execute([
        'id' => $id,
    ]);
}
}