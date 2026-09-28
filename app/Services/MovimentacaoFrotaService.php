<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\MovimentacaoFrotaRepository;

class MovimentacaoFrotaService
{
    public function __construct(
        private MovimentacaoFrotaRepository $repository
    ) {
    }

    public function validarSaida(array $dados): array
{
    $veiculoId = (int) ($dados['veiculo_id'] ?? 0);
    $carretaId = (int) ($dados['carreta_id'] ?? 0);
    $motoristaId = (int) ($dados['motorista_id'] ?? 0);

    $dataHoraSaida = trim(
        $dados['data_hora_saida'] ?? ''
    );

    $indicadorSaida = $dados['indicador_saida'] ?? null;

    $destino = trim($dados['destino'] ?? '');
    $finalidade = trim($dados['finalidade'] ?? '');
    $observacoesSaida = trim(
        $dados['observacoes_saida'] ?? ''
    );

    if ($veiculoId <= 0) {
        throw new \InvalidArgumentException(
            'O veículo é obrigatório.'
        );
    }

    if ($motoristaId <= 0) {
        throw new \InvalidArgumentException(
            'O motorista é obrigatório.'
        );
    }

    if ($dataHoraSaida === '') {
        throw new \InvalidArgumentException(
            'A data e hora da saída são obrigatórias.'
        );
    }

    if (
        $indicadorSaida === null ||
        trim((string) $indicadorSaida) === ''
    ) {
        throw new \InvalidArgumentException(
            'O indicador de saída é obrigatório.'
        );
    }

    if (!is_numeric($indicadorSaida)) {
        throw new \InvalidArgumentException(
            'O indicador de saída deve ser numérico.'
        );
    }

    $indicadorSaida = (float) $indicadorSaida;

    if ($indicadorSaida < 0) {
        throw new \InvalidArgumentException(
            'O indicador de saída não pode ser negativo.'
        );
    }

    $veiculo = $this->validarVeiculoPrincipal(
    $veiculoId
);

$this->validarDisponibilidadeVeiculo(
    $veiculoId
);

$this->validarMotorista(
    $motoristaId
);

$this->validarCarreta(
    $carretaId > 0 ? $carretaId : null
);

$indicadorSaida = (float) $indicadorSaida;

$this->validarIndicadorSaida(
    $veiculo,
    $indicadorSaida
);

$dataHoraSaida = $this->validarDataHoraSaida(
    $dataHoraSaida
);

    return [
        'veiculo_id' => $veiculoId,
        'carreta_id' => $carretaId > 0
            ? $carretaId
            : null,
        'motorista_id' => $motoristaId,
        'data_hora_saida' => $dataHoraSaida,
        'indicador_saida' => $indicadorSaida,
        'destino' => $destino !== ''
            ? $destino
            : null,
        'finalidade' => $finalidade !== ''
            ? $finalidade
            : null,
        'observacoes_saida' => $observacoesSaida !== ''
            ? $observacoesSaida
            : null,
    ];
}

private function validarVeiculoPrincipal(int $veiculoId): array
{
    $veiculo = $this->repository
        ->buscarVeiculoParaMovimentacao($veiculoId);

    if ($veiculo === null) {
        throw new \InvalidArgumentException(
            'O veículo informado não foi encontrado.'
        );
    }

    if (!$veiculo['ativo']) {
        throw new \InvalidArgumentException(
            'O veículo informado está inativo.'
        );
    }

    if ($veiculo['tipo_veiculo'] === 'Carreta') {
        throw new \InvalidArgumentException(
            'Uma carreta não pode ser utilizada como veículo principal.'
        );
    }

    return $veiculo;
}

private function validarDisponibilidadeVeiculo(
    int $veiculoId
): void {
    $movimentacao = $this->repository
        ->buscarAbertaPorVeiculoId($veiculoId);

    if ($movimentacao !== null) {
        throw new \InvalidArgumentException(
            'O veículo já possui uma saída em aberto.'
        );
    }
}

private function validarMotorista(int $motoristaId): array
{
    $motorista = $this->repository
        ->buscarMotoristaParaMovimentacao($motoristaId);

    if ($motorista === null) {
        throw new \InvalidArgumentException(
            'O motorista informado não foi encontrado.'
        );
    }

    if (!$motorista['ativo']) {
        throw new \InvalidArgumentException(
            'O funcionário informado está inativo.'
        );
    }

    if ($motorista['funcao'] !== 'Motorista') {
        throw new \InvalidArgumentException(
            'O funcionário selecionado não possui a função de motorista.'
        );
    }

    return $motorista;
}

private function validarCarreta(?int $carretaId): ?array
{
    if ($carretaId === null || $carretaId <= 0) {
        return null;
    }

    $carreta = $this->repository
        ->buscarCarretaParaMovimentacao($carretaId);

    if ($carreta === null) {
        throw new \InvalidArgumentException(
            'A carreta informada não foi encontrada.'
        );
    }

    if (!$carreta['ativo']) {
        throw new \InvalidArgumentException(
            'A carreta informada está inativa.'
        );
    }

    if ($carreta['tipo_veiculo'] !== 'Carreta') {
        throw new \InvalidArgumentException(
            'O veículo selecionado não é uma carreta.'
        );
    }

    $movimentacao = $this->repository
        ->buscarAbertaPorCarretaId($carretaId);

    if ($movimentacao !== null) {
        throw new \InvalidArgumentException(
            'A carreta já está vinculada a uma saída em aberto.'
        );
    }

    return $carreta;
}

private function validarIndicadorSaida(
    array $veiculo,
    float $indicadorSaida
): void {
    $indicadorTipo = $veiculo['indicador_tipo'];

    if ($indicadorTipo === 'NAO_APLICAVEL') {
        throw new \InvalidArgumentException(
            'O veículo informado não possui indicador de utilização.'
        );
    }

    $indicadorAtual = $veiculo['indicador_atual'];

    if ($indicadorAtual === null) {
        throw new \InvalidArgumentException(
            'O veículo não possui um indicador atual cadastrado.'
        );
    }

    if ($indicadorSaida < (float) $indicadorAtual) {
        $unidade = $indicadorTipo === 'KM'
            ? 'KM'
            : 'horímetro';

        throw new \InvalidArgumentException(
            "O indicador de saída não pode ser menor que o indicador atual do veículo ({$unidade}: {$indicadorAtual})."
        );
    }
}

private function validarDataHoraSaida(
    string $dataHora
): string {
    $data = \DateTimeImmutable::createFromFormat(
        'Y-m-d\TH:i',
        $dataHora
    );

    $erros = \DateTimeImmutable::getLastErrors();

    if (
        $data === false ||
        (
            is_array($erros) &&
            (
                $erros['warning_count'] > 0 ||
                $erros['error_count'] > 0
            )
        )
    ) {
        throw new \InvalidArgumentException(
            'A data e hora da saída são inválidas.'
        );
    }

    return $data->format('Y-m-d H:i:s');
}

public function validarRetorno(
    int $movimentacaoId,
    array $dados
): array {
    $dataHoraRetorno = trim(
        $dados['data_hora_retorno'] ?? ''
    );

    $indicadorRetorno = $dados['indicador_retorno'] ?? null;

    $observacoesRetorno = trim(
        $dados['observacoes_retorno'] ?? ''
    );

    if ($movimentacaoId <= 0) {
        throw new \InvalidArgumentException(
            'A movimentação informada é inválida.'
        );
    }

    $movimentacao = $this->repository
        ->buscarPorId($movimentacaoId);

    if ($movimentacao === null) {
        throw new \InvalidArgumentException(
            'A movimentação não foi encontrada.'
        );
    }

    if ($movimentacao['status'] !== 'ABERTA') {
        throw new \InvalidArgumentException(
            'Somente uma movimentação aberta pode receber retorno.'
        );
    }

    if ($dataHoraRetorno === '') {
        throw new \InvalidArgumentException(
            'A data e hora do retorno são obrigatórias.'
        );
    }

    if (
        $indicadorRetorno === null ||
        trim((string) $indicadorRetorno) === ''
    ) {
        throw new \InvalidArgumentException(
            'O indicador de retorno é obrigatório.'
        );
    }

    if (!is_numeric($indicadorRetorno)) {
        throw new \InvalidArgumentException(
            'O indicador de retorno deve ser numérico.'
        );
    }

    $indicadorRetorno = (float) $indicadorRetorno;

    if ($indicadorRetorno < 0) {
        throw new \InvalidArgumentException(
            'O indicador de retorno não pode ser negativo.'
        );
    }

    if (
        $indicadorRetorno <
        (float) $movimentacao['indicador_saida']
    ) {
        throw new \InvalidArgumentException(
            'O indicador de retorno não pode ser menor que o indicador registrado na saída.'
        );
    }

    $dataHoraRetorno = $this->validarDataHoraSaida(
        $dataHoraRetorno
    );

    if (
        strtotime($dataHoraRetorno) <
        strtotime($movimentacao['data_hora_saida'])
    ) {
        throw new \InvalidArgumentException(
            'A data e hora do retorno não podem ser anteriores à saída.'
        );
    }

    return [
        'movimentacao' => $movimentacao,
        'data_hora_retorno' => $dataHoraRetorno,
        'indicador_retorno' => $indicadorRetorno,
        'observacoes_retorno' => $observacoesRetorno !== ''
            ? $observacoesRetorno
            : null,
    ];
}

public function criarSaida(array $dados): int
{
    $dadosValidados = $this->validarSaida($dados);

    return $this->repository->criar(
        $dadosValidados
    );
}

public function registrarRetorno(
    int $movimentacaoId,
    array $dados
): void {
    $dadosValidados = $this->validarRetorno(
        $movimentacaoId,
        $dados
    );

    $movimentacao = $dadosValidados['movimentacao'];

    $connection = $this->repository->getConnection();

    $connection->beginTransaction();

    try {
        $this->repository->registrarRetorno(
            $movimentacaoId,
            [
                'data_hora_retorno' =>
                    $dadosValidados['data_hora_retorno'],

                'indicador_retorno' =>
                    $dadosValidados['indicador_retorno'],

                'observacoes_retorno' =>
                    $dadosValidados['observacoes_retorno'],
            ]
        );

        $this->repository->atualizarIndicadorVeiculo(
            (int) $movimentacao['veiculo_id'],
            $dadosValidados['indicador_retorno']
        );

        $connection->commit();
    } catch (\Throwable $exception) {
        $connection->rollBack();

        throw $exception;
    }
}

public function cancelar(int $movimentacaoId): void
{
    if ($movimentacaoId <= 0) {
        throw new \InvalidArgumentException(
            'A movimentação informada é inválida.'
        );
    }

    $movimentacao = $this->repository
        ->buscarPorId($movimentacaoId);

    if ($movimentacao === null) {
        throw new \InvalidArgumentException(
            'A movimentação não foi encontrada.'
        );
    }

    if ($movimentacao['status'] !== 'ABERTA') {
        throw new \InvalidArgumentException(
            'Somente uma movimentação aberta pode ser cancelada.'
        );
    }

    $this->repository->cancelar(
        $movimentacaoId
    );
}
}