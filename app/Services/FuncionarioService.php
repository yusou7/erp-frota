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
}