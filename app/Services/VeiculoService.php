<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\VeiculoRepository;
use InvalidArgumentException;

class VeiculoService
{
    public function __construct(
        private VeiculoRepository $repository
    ) {
    }

    public function validarDados(array $dados, ?int $id = null): array
    {
        $numero = trim($dados['numero'] ?? '');
        $placa = strtoupper(trim($dados['placa'] ?? ''));
        $renavam = trim($dados['renavam'] ?? '');
        $marca = trim($dados['marca'] ?? '');
        $modelo = trim($dados['modelo'] ?? '');
        $cor = trim($dados['cor'] ?? '');
        $combustivel = trim($dados['combustivel'] ?? '');
        $observacoes = trim($dados['observacoes'] ?? '');

        $tipoVeiculoId = (int) ($dados['tipo_veiculo_id'] ?? 0);

        $indicadorTipo = strtoupper(
            trim($dados['indicador_tipo'] ?? '')
        );

        $indicadorAtual = $dados['indicador_atual'] ?? null;

        if ($numero === '') {
            throw new InvalidArgumentException(
                'O número do veículo é obrigatório.'
            );
        }

        if ($marca === '') {
            throw new InvalidArgumentException(
                'A marca do veículo é obrigatória.'
            );
        }

        if ($modelo === '') {
            throw new InvalidArgumentException(
                'O modelo do veículo é obrigatório.'
            );
        }

        if ($tipoVeiculoId <= 0) {
            throw new InvalidArgumentException(
                'O tipo do veículo é obrigatório.'
            );
        }

        if (!in_array(
            $indicadorTipo,
            ['KM', 'HORIMETRO', 'NAO_APLICAVEL'],
            true
        )) {
            throw new InvalidArgumentException(
                'O tipo de indicador informado é inválido.'
            );
        }

        $veiculoNumero = $this->repository->buscarPorNumero($numero);

if (
    $veiculoNumero !== null &&
    ($id === null || (int) $veiculoNumero['id'] !== $id)
) {
    throw new InvalidArgumentException(
        'Já existe um veículo cadastrado com este número.'
    );
}

        if ($placa !== '') {
            $placa = preg_replace('/[^A-Z0-9]/', '', $placa) ?? '';

            $veiculo = $this->repository->buscarPorPlaca($placa);

            if (
                $veiculo !== null &&
                ($id === null || (int) $veiculo['id'] !== $id)
            ) {
                throw new InvalidArgumentException(
                    'Já existe um veículo cadastrado com esta placa.'
                );
            }
        } else {
            $placa = null;
        }

        $anoFabricacao = $this->normalizarAno(
            $dados['ano_fabricacao'] ?? null,
            'fabricação'
        );

        $anoModelo = $this->normalizarAno(
            $dados['ano_modelo'] ?? null,
            'modelo'
        );

        if (
            $anoFabricacao !== null &&
            $anoModelo !== null &&
            $anoModelo < $anoFabricacao
        ) {
            throw new InvalidArgumentException(
                'O ano do modelo não pode ser menor que o ano de fabricação.'
            );
        }

        if ($indicadorTipo === 'NAO_APLICAVEL') {
            $indicadorAtual = null;
        } else {
            if (
                $indicadorAtual === null ||
                trim((string) $indicadorAtual) === ''
            ) {
                throw new InvalidArgumentException(
                    'O indicador atual é obrigatório.'
                );
            }

            if (!is_numeric($indicadorAtual)) {
                throw new InvalidArgumentException(
                    'O indicador atual deve ser numérico.'
                );
            }

            $indicadorAtual = (float) $indicadorAtual;

            if ($indicadorAtual < 0) {
                throw new InvalidArgumentException(
                    'O indicador atual não pode ser negativo.'
                );
            }
        }

        return [
            'numero' => $numero,
            'placa' => $placa,
            'renavam' => $renavam !== '' ? $renavam : null,
            'marca' => $marca,
            'modelo' => $modelo,
            'ano_fabricacao' => $anoFabricacao,
            'ano_modelo' => $anoModelo,
            'cor' => $cor !== '' ? $cor : null,
            'tipo_veiculo_id' => $tipoVeiculoId,
            'combustivel' => $combustivel !== ''
                ? $combustivel
                : null,
            'indicador_tipo' => $indicadorTipo,
            'indicador_atual' => $indicadorAtual,
            'observacoes' => $observacoes !== ''
                ? $observacoes
                : null,
        ];
    }

    private function normalizarAno(
        mixed $ano,
        string $descricao
    ): ?int {
        if ($ano === null || trim((string) $ano) === '') {
            return null;
        }

        if (
            !filter_var(
                $ano,
                FILTER_VALIDATE_INT
            )
        ) {
            throw new InvalidArgumentException(
                "O ano de {$descricao} deve ser válido."
            );
        }

        $ano = (int) $ano;

        $anoAtual = (int) date('Y');

        if ($ano < 1900 || $ano > $anoAtual + 1) {
            throw new InvalidArgumentException(
                "O ano de {$descricao} está fora do intervalo permitido."
            );
        }

        return $ano;
    }
}