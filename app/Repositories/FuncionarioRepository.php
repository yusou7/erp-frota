<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class FuncionarioRepository
{
    public function __construct(
        private PDO $connection
    ) {
    }

    public function listarTodos(): array
    {
        $sql = <<<SQL
        SELECT
            f.id,
            f.nome,
            f.cpf,
            f.matricula,
            f.telefone,
            f.email,
            f.ativo,
            fu.nome AS funcao
        FROM funcionarios f
        INNER JOIN funcoes fu
            ON fu.id = f.funcao_id
        ORDER BY f.nome ASC
        SQL;

        $statement = $this->connection->query($sql);

        return $statement->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $sql = <<<SQL
        SELECT
            f.id,
            f.nome,
            f.cpf,
            f.matricula,
            f.telefone,
            f.email,
            f.funcao_id,
            f.ativo
        FROM funcionarios f
        WHERE f.id = :id
        LIMIT 1
        SQL;

        $statement = $this->connection->prepare($sql);

        $statement->execute([
            'id' => $id,
        ]);

        $funcionario = $statement->fetch();

        return $funcionario !== false ? $funcionario : null;
    }

    public function buscarPorCpf(string $cpf): ?array
    {
        $sql = <<<SQL
        SELECT
            id,
            nome,
            cpf,
            matricula
        FROM funcionarios
        WHERE cpf = :cpf
        LIMIT 1
        SQL;

        $statement = $this->connection->prepare($sql);

        $statement->execute([
            'cpf' => $cpf,
        ]);

        $funcionario = $statement->fetch();

        return $funcionario !== false ? $funcionario : null;
    }

    public function buscarPorMatricula(string $matricula): ?array
    {
        $sql = <<<SQL
        SELECT
            id,
            nome,
            cpf,
            matricula
        FROM funcionarios
        WHERE matricula = :matricula
        LIMIT 1
        SQL;

        $statement = $this->connection->prepare($sql);

        $statement->execute([
            'matricula' => $matricula,
        ]);

        $funcionario = $statement->fetch();

        return $funcionario !== false ? $funcionario : null;
    }

    public function criar(array $dados): int
    {
        $sql = <<<SQL
        INSERT INTO funcionarios (
            nome,
            cpf,
            matricula,
            telefone,
            email,
            funcao_id,
            ativo
        )
        VALUES (
            :nome,
            :cpf,
            :matricula,
            :telefone,
            :email,
            :funcao_id,
            :ativo
        )
        RETURNING id
        SQL;

        $statement = $this->connection->prepare($sql);

        $statement->execute([
            'nome' => $dados['nome'],
            'cpf' => $dados['cpf'],
            'matricula' => $dados['matricula'],
            'telefone' => $dados['telefone'],
            'email' => $dados['email'],
            'funcao_id' => $dados['funcao_id'],
            'ativo' => $dados['ativo'],
        ]);

        return (int) $statement->fetchColumn();
    }

    public function atualizar(int $id, array $dados): void
    {
    $sql = <<<SQL
    UPDATE funcionarios
    SET
        nome = :nome,
        cpf = :cpf,
        matricula = :matricula,
        telefone = :telefone,
        email = :email,
        funcao_id = :funcao_id,
        atualizado_em = NOW()
    WHERE id = :id
    SQL;

    $statement = $this->connection->prepare($sql);

    $statement->execute([
        'id' => $id,
        'nome' => $dados['nome'],
        'cpf' => $dados['cpf'],
        'matricula' => $dados['matricula'],
        'telefone' => $dados['telefone'],
        'email' => $dados['email'],
        'funcao_id' => $dados['funcao_id'],
    ]);
    }

    public function inativar(int $id): void
{
    $sql = <<<SQL
    UPDATE funcionarios
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