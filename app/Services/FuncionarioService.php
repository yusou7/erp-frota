<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\FuncionarioRepository;
use InvalidArgumentException;

class FuncionarioService
{
    public function __construct(
        private FuncionarioRepository $repository
    ) {
    }

    public function criar(array $dados): int
    {
        $nome = trim($dados['nome'] ?? '');
        $cpf = trim($dados['cpf'] ?? '');
        $matricula = trim($dados['matricula'] ?? '');
        $telefone = trim($dados['telefone'] ?? '');
        $email = trim($dados['email'] ?? '');
        $funcaoId = (int) ($dados['funcao_id'] ?? 0);

        if ($nome === '') {
            throw new InvalidArgumentException(
                'O nome do funcionário é obrigatório.'
            );
        }

        if ($cpf === '') {
            throw new InvalidArgumentException(
                'O CPF do funcionário é obrigatório.'
            );
        }

        if ($matricula === '') {
            throw new InvalidArgumentException(
                'A matrícula do funcionário é obrigatória.'
            );
        }

        if ($funcaoId <= 0) {
            throw new InvalidArgumentException(
                'A função do funcionário é obrigatória.'
            );
        }

        $cpf = preg_replace('/\D/', '', $cpf) ?? '';

        if (strlen($cpf) !== 11) {
            throw new InvalidArgumentException(
                'O CPF deve possuir 11 dígitos.'
            );
        }

        if ($this->repository->buscarPorCpf($cpf) !== null) {
            throw new InvalidArgumentException(
                'Já existe um funcionário cadastrado com este CPF.'
            );
        }

        if ($this->repository->buscarPorMatricula($matricula) !== null) {
            throw new InvalidArgumentException(
                'Já existe um funcionário cadastrado com esta matrícula.'
            );
        }

        return $this->repository->criar([
            'nome' => $nome,
            'cpf' => $cpf,
            'matricula' => $matricula,
            'telefone' => $telefone !== '' ? $telefone : null,
            'email' => $email !== '' ? $email : null,
            'funcao_id' => $funcaoId,
            'ativo' => true,
        ]);
    }

    public function atualizar(int $id, array $dados): void
{
    $funcionario = $this->repository->buscarPorId($id);

    if ($funcionario === null) {
        throw new InvalidArgumentException(
            'Funcionário não encontrado.'
        );
    }

    $nome = trim($dados['nome'] ?? '');
    $cpf = trim($dados['cpf'] ?? '');
    $matricula = trim($dados['matricula'] ?? '');
    $telefone = trim($dados['telefone'] ?? '');
    $email = trim($dados['email'] ?? '');
    $funcaoId = (int) ($dados['funcao_id'] ?? 0);

    if ($nome === '') {
        throw new InvalidArgumentException(
            'O nome do funcionário é obrigatório.'
        );
    }

    if ($cpf === '') {
        throw new InvalidArgumentException(
            'O CPF do funcionário é obrigatório.'
        );
    }

    if ($matricula === '') {
        throw new InvalidArgumentException(
            'A matrícula do funcionário é obrigatória.'
        );
    }

    if ($funcaoId <= 0) {
        throw new InvalidArgumentException(
            'A função do funcionário é obrigatória.'
        );
    }

    $cpf = preg_replace('/\D/', '', $cpf) ?? '';

    if (strlen($cpf) !== 11) {
        throw new InvalidArgumentException(
            'O CPF deve possuir 11 dígitos.'
        );
    }

    $funcionarioCpf = $this->repository->buscarPorCpf($cpf);

    if (
        $funcionarioCpf !== null &&
        (int) $funcionarioCpf['id'] !== $id
    ) {
        throw new InvalidArgumentException(
            'Já existe outro funcionário cadastrado com este CPF.'
        );
    }

    $funcionarioMatricula = $this->repository
        ->buscarPorMatricula($matricula);

    if (
        $funcionarioMatricula !== null &&
        (int) $funcionarioMatricula['id'] !== $id
    ) {
        throw new InvalidArgumentException(
            'Já existe outro funcionário cadastrado com esta matrícula.'
        );
    }

    $this->repository->atualizar($id, [
        'nome' => $nome,
        'cpf' => $cpf,
        'matricula' => $matricula,
        'telefone' => $telefone !== '' ? $telefone : null,
        'email' => $email !== '' ? $email : null,
        'funcao_id' => $funcaoId,
    ]);
    }

    public function inativar(int $id): void
{
    $funcionario = $this->repository->buscarPorId($id);

    if ($funcionario === null) {
        throw new InvalidArgumentException(
            'Funcionário não encontrado.'
        );
    }

    if (!$funcionario['ativo']) {
        throw new InvalidArgumentException(
            'O funcionário já está inativo.'
        );
    }

    $this->repository->inativar($id);
}
}