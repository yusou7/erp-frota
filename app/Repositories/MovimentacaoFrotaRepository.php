<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class MovimentacaoFrotaRepository
{
    public function __construct(
        private PDO $connection
    ) {
    }

    public function listarTodos(): array
{
    $sql = <<<SQL
    SELECT
        m.id,
        m.data_hora_saida,
        m.data_hora_retorno,
        m.indicador_saida,
        m.indicador_retorno,
        m.destino,
        m.finalidade,
        m.status,

        v.numero AS veiculo_numero,
        v.placa AS veiculo_placa,

        c.numero AS carreta_numero,
        c.placa AS carreta_placa,

        f.nome AS motorista_nome

    FROM movimentacoes_frota m

    INNER JOIN veiculos v
        ON v.id = m.veiculo_id

    INNER JOIN funcionarios f
        ON f.id = m.motorista_id

    LEFT JOIN veiculos c
        ON c.id = m.carreta_id

    ORDER BY m.data_hora_saida DESC
    SQL;

    $statement = $this->connection->query($sql);

    return $statement->fetchAll();
}

public function buscarPorId(int $id): ?array
{
    $sql = <<<SQL
    SELECT
        m.id,
        m.veiculo_id,
        m.carreta_id,
        m.motorista_id,

        m.data_hora_saida,
        m.indicador_saida,

        m.destino,
        m.finalidade,

        m.data_hora_retorno,
        m.indicador_retorno,

        m.observacoes_saida,
        m.observacoes_retorno,

        m.status,

        v.numero AS veiculo_numero,
        v.placa AS veiculo_placa,
        v.indicador_tipo AS veiculo_indicador_tipo,

        c.numero AS carreta_numero,
        c.placa AS carreta_placa,

        f.nome AS motorista_nome

    FROM movimentacoes_frota m

    INNER JOIN veiculos v
        ON v.id = m.veiculo_id

    INNER JOIN funcionarios f
        ON f.id = m.motorista_id

    LEFT JOIN veiculos c
        ON c.id = m.carreta_id

    WHERE m.id = :id

    LIMIT 1
    SQL;

    $statement = $this->connection->prepare($sql);

    $statement->execute([
        'id' => $id,
    ]);

    $movimentacao = $statement->fetch();

    return $movimentacao !== false
        ? $movimentacao
        : null;
}

public function buscarAbertaPorVeiculoId(int $veiculoId): ?array
{
    $sql = <<<SQL
    SELECT
        id,
        veiculo_id,
        carreta_id,
        motorista_id,
        data_hora_saida,
        indicador_saida,
        status
    FROM movimentacoes_frota
    WHERE veiculo_id = :veiculo_id
      AND status = 'ABERTA'
    LIMIT 1
    SQL;

    $statement = $this->connection->prepare($sql);

    $statement->execute([
        'veiculo_id' => $veiculoId,
    ]);

    $movimentacao = $statement->fetch();

    return $movimentacao !== false
        ? $movimentacao
        : null;
}

public function buscarAbertaPorCarretaId(int $carretaId): ?array
{
    $sql = <<<SQL
    SELECT
        id,
        veiculo_id,
        carreta_id,
        motorista_id,
        data_hora_saida,
        indicador_saida,
        status
    FROM movimentacoes_frota
    WHERE carreta_id = :carreta_id
      AND status = 'ABERTA'
    LIMIT 1
    SQL;

    $statement = $this->connection->prepare($sql);

    $statement->execute([
        'carreta_id' => $carretaId,
    ]);

    $movimentacao = $statement->fetch();

    return $movimentacao !== false
        ? $movimentacao
        : null;
}

public function listarVeiculosDisponiveis(): array
{
    $sql = <<<SQL
    SELECT
        v.id,
        v.numero,
        v.placa,
        v.marca,
        v.modelo,
        v.indicador_tipo,
        v.indicador_atual,
        tv.nome AS tipo_veiculo

    FROM veiculos v

    INNER JOIN tipos_veiculos tv
        ON tv.id = v.tipo_veiculo_id

    WHERE v.ativo = TRUE
      AND tv.nome <> 'Carreta'
      AND NOT EXISTS (
          SELECT 1
          FROM movimentacoes_frota m
          WHERE m.veiculo_id = v.id
            AND m.status = 'ABERTA'
      )

    ORDER BY v.numero ASC
    SQL;

    $statement = $this->connection->query($sql);

    return $statement->fetchAll();
}

public function listarCarretasDisponiveis(): array
{
    $sql = <<<SQL
    SELECT
        v.id,
        v.numero,
        v.placa,
        v.marca,
        v.modelo

    FROM veiculos v

    INNER JOIN tipos_veiculos tv
        ON tv.id = v.tipo_veiculo_id

    WHERE v.ativo = TRUE
      AND tv.nome = 'Carreta'
      AND NOT EXISTS (
          SELECT 1
          FROM movimentacoes_frota m
          WHERE m.carreta_id = v.id
            AND m.status = 'ABERTA'
      )

    ORDER BY v.numero ASC
    SQL;

    $statement = $this->connection->query($sql);

    return $statement->fetchAll();
}

public function listarMotoristasDisponiveis(): array
{
    $sql = <<<SQL
    SELECT
        f.id,
        f.nome,
        f.matricula
    FROM funcionarios f
    INNER JOIN funcoes fu
        ON fu.id = f.funcao_id
    WHERE f.ativo = TRUE
      AND fu.nome = 'Motorista'
    ORDER BY f.nome ASC
    SQL;

    $statement = $this->connection->query($sql);

    return $statement->fetchAll();
}

public function criar(array $dados): int
{
    $sql = <<<SQL
    INSERT INTO movimentacoes_frota (
        veiculo_id,
        carreta_id,
        motorista_id,
        data_hora_saida,
        indicador_saida,
        destino,
        finalidade,
        observacoes_saida,
        status
    )
    VALUES (
        :veiculo_id,
        :carreta_id,
        :motorista_id,
        :data_hora_saida,
        :indicador_saida,
        :destino,
        :finalidade,
        :observacoes_saida,
        'ABERTA'
    )
    RETURNING id
    SQL;

    $statement = $this->connection->prepare($sql);

    $statement->execute([
        'veiculo_id' => $dados['veiculo_id'],
        'carreta_id' => $dados['carreta_id'],
        'motorista_id' => $dados['motorista_id'],
        'data_hora_saida' => $dados['data_hora_saida'],
        'indicador_saida' => $dados['indicador_saida'],
        'destino' => $dados['destino'],
        'finalidade' => $dados['finalidade'],
        'observacoes_saida' => $dados['observacoes_saida'],
    ]);

    return (int) $statement->fetchColumn();
}

public function registrarRetorno(
    int $id,
    array $dados
): void {
    $sql = <<<SQL
    UPDATE movimentacoes_frota
    SET
        data_hora_retorno = :data_hora_retorno,
        indicador_retorno = :indicador_retorno,
        observacoes_retorno = :observacoes_retorno,
        status = 'FINALIZADA',
        atualizado_em = NOW()
    WHERE id = :id
      AND status = 'ABERTA'
    SQL;

    $statement = $this->connection->prepare($sql);

    $statement->execute([
        'id' => $id,
        'data_hora_retorno' => $dados['data_hora_retorno'],
        'indicador_retorno' => $dados['indicador_retorno'],
        'observacoes_retorno' => $dados['observacoes_retorno'],
    ]);
}

public function cancelar(int $id): void
{
    $sql = <<<SQL
    UPDATE movimentacoes_frota
    SET
        status = 'CANCELADA',
        atualizado_em = NOW()
    WHERE id = :id
      AND status = 'ABERTA'
    SQL;

    $statement = $this->connection->prepare($sql);

    $statement->execute([
        'id' => $id,
    ]);
}

public function buscarVeiculoParaMovimentacao(
    int $veiculoId
): ?array {
    $sql = <<<SQL
    SELECT
        v.id,
        v.numero,
        v.placa,
        v.tipo_veiculo_id,
        v.indicador_tipo,
        v.indicador_atual,
        v.ativo,

        tv.nome AS tipo_veiculo

    FROM veiculos v

    INNER JOIN tipos_veiculos tv
        ON tv.id = v.tipo_veiculo_id

    WHERE v.id = :id
    LIMIT 1
    SQL;

    $statement = $this->connection->prepare($sql);

    $statement->execute([
        'id' => $veiculoId,
    ]);

    $veiculo = $statement->fetch();

    return $veiculo !== false
        ? $veiculo
        : null;
}

public function buscarMotoristaParaMovimentacao(
    int $motoristaId
): ?array {
    $sql = <<<SQL
    SELECT
        f.id,
        f.nome,
        f.matricula,
        f.ativo,
        fu.nome AS funcao
    FROM funcionarios f
    INNER JOIN funcoes fu
        ON fu.id = f.funcao_id
    WHERE f.id = :id
    LIMIT 1
    SQL;

    $statement = $this->connection->prepare($sql);

    $statement->execute([
        'id' => $motoristaId,
    ]);

    $motorista = $statement->fetch();

    return $motorista !== false
        ? $motorista
        : null;
}

public function buscarCarretaParaMovimentacao(
    int $carretaId
): ?array {
    $sql = <<<SQL
    SELECT
        v.id,
        v.numero,
        v.placa,
        v.marca,
        v.modelo,
        v.ativo,

        tv.nome AS tipo_veiculo

    FROM veiculos v

    INNER JOIN tipos_veiculos tv
        ON tv.id = v.tipo_veiculo_id

    WHERE v.id = :id
    LIMIT 1
    SQL;

    $statement = $this->connection->prepare($sql);

    $statement->execute([
        'id' => $carretaId,
    ]);

    $carreta = $statement->fetch();

    return $carreta !== false
        ? $carreta
        : null;
}

public function atualizarIndicadorVeiculo(
    int $veiculoId,
    float $indicador
): void {
    $sql = <<<SQL
    UPDATE veiculos
    SET
        indicador_atual = :indicador,
        atualizado_em = NOW()
    WHERE id = :id
    SQL;

    $statement = $this->connection->prepare($sql);

    $statement->execute([
        'id' => $veiculoId,
        'indicador' => $indicador,
    ]);
}

public function getConnection(): PDO
{
    return $this->connection;
}
}