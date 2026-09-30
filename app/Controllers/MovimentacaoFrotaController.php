<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;
use App\Core\Session;
use App\Repositories\ChecklistRepository;
use App\Repositories\MovimentacaoFrotaRepository;
use App\Services\ChecklistService;
use App\Services\MovimentacaoFrotaService;
use App\Repositories\ChecklistExecucaoRepository;
use App\Repositories\FuncionarioRepository;
use App\Repositories\VeiculoRepository;

class MovimentacaoFrotaController
{
    private MovimentacaoFrotaService $service;

    private MovimentacaoFrotaRepository $repository;

    private VeiculoRepository $veiculoRepository;

    private FuncionarioRepository $funcionarioRepository;

    public function __construct()
{
    $database = new Database();

    $connection = $database->getConnection();

    $veiculoRepository = new VeiculoRepository(
    $connection
);

    $funcionarioRepository = new FuncionarioRepository(
    $connection
);


    $this->repository = new MovimentacaoFrotaRepository(
        $connection
    );

    $this->veiculoRepository = $veiculoRepository;

    $this->funcionarioRepository = $funcionarioRepository;

    $checklistRepository = new ChecklistRepository(
    $connection
);

$checklistExecucaoRepository = new ChecklistExecucaoRepository(
    $connection
);

$checklistService = new ChecklistService(
    $checklistRepository,
    $checklistExecucaoRepository
);

    $this->service = new MovimentacaoFrotaService(
        $this->repository,
        $checklistService
    );
}

    public function index(): void
{
    $filtros = [
    'status' => $_GET['status'] ?? '',
    'status_autorizacao_saida' =>
        $_GET['status_autorizacao_saida'] ?? '',
    'data_inicial' => $_GET['data_inicial'] ?? '',
    'data_final' => $_GET['data_final'] ?? '',
    'veiculo_id' => $_GET['veiculo_id'] ?? '',
    'motorista_id' => $_GET['motorista_id'] ?? '',
];

    $movimentacoes = $this->repository
        ->listarTodos($filtros);

    $veiculos = $this->veiculoRepository
        ->listarTodos();

    $funcionarios = $this->funcionarioRepository
        ->listarTodos();

    require __DIR__ . '/../../views/movimentacoes/index.php';
}

public function create(): void
{
    $veiculos = $this->repository
        ->listarVeiculosDisponiveis();

    $carretas = $this->repository
        ->listarCarretasDisponiveis();

    $motoristas = $this->repository
        ->listarMotoristasDisponiveis();

    $checklistRepository = new ChecklistRepository(
        $this->repository->getConnection()
    );

    require __DIR__ . '/../../views/movimentacoes/create.php';
}

public function checklistVeiculo(int $veiculoId): void
{
    $veiculo = $this->repository
        ->buscarVeiculoParaMovimentacao($veiculoId);

    if ($veiculo === null) {
        http_response_code(404);

        header('Content-Type: application/json');

        echo json_encode([
            'erro' => 'Veículo não encontrado.'
        ]);

        return;
    }

    $checklistRepository = new ChecklistRepository(
        $this->repository->getConnection()
    );

    $modelo = $checklistRepository
        ->buscarModeloAtivoPorTipoVeiculo(
            (int) $veiculo['tipo_veiculo_id']
        );

    if ($modelo === null) {
        header('Content-Type: application/json');

        echo json_encode([
            'modelo' => null,
            'itens' => []
        ]);

        return;
    }

    $execucaoRepository = new \App\Repositories\ChecklistExecucaoRepository(
        $this->repository->getConnection()
    );

    $itens = $execucaoRepository->listarItens(
        (int) $modelo['id']
    );

    header('Content-Type: application/json');

    echo json_encode([
        'modelo' => [
            'id' => (int) $modelo['id'],
            'nome' => $modelo['nome'],
            'descricao' => $modelo['descricao'],
        ],
        'itens' => $itens,
    ]);
}
public function store(): void
{
    try {
        $this->service->criarSaida($_POST);

        Session::setFlash(
            'success',
            'Saída registrada com sucesso.'
        );

        header('Location: /saidas');
        exit;
    } catch (\InvalidArgumentException $exception) {
        Session::setFlash(
            'error',
            $exception->getMessage()
        );

        header('Location: /saidas/nova');
        exit;
    } catch (\Throwable $exception) {
        Session::setFlash(
            'error',
            'Não foi possível registrar a saída.'
        );

        header('Location: /saidas/nova');
        exit;
    }
}

public function show(int $id): void
{
    $movimentacao = $this->repository->buscarPorId($id);

    if ($movimentacao === null) {
        http_response_code(404);

        echo 'Movimentação não encontrada.';

        return;
    }

    $checklistSaida = $this->service
        ->buscarChecklistSaida($id);

        $checklistRetorno = $this->service
    ->buscarChecklistRetorno($id);

    require __DIR__ . '/../../views/movimentacoes/show.php';
}

public function return(int $id): void
{
    try {
        $this->service->registrarRetorno(
            $id,
            $_POST
        );

        Session::setFlash(
            'success',
            'Retorno registrado com sucesso.'
        );

        header("Location: /saidas/{$id}");
        exit;
    } catch (\InvalidArgumentException $exception) {
        Session::setFlash(
            'error',
            $exception->getMessage()
        );

        header("Location: /saidas/{$id}");
        exit;
    } catch (\Throwable $exception) {
    Session::setFlash(
        'error',
        $exception->getMessage()
    );

    header("Location: /saidas/{$id}");
    exit;
}
}

public function cancelar(int $id): void
{
    try {
        $this->service->cancelar($id);

        Session::setFlash(
            'success',
            'Movimentação cancelada com sucesso.'
        );

        header('Location: /saidas');
        exit;
    } catch (\InvalidArgumentException $exception) {
        Session::setFlash(
            'error',
            $exception->getMessage()
        );

        header("Location: /saidas/{$id}");
        exit;
    } catch (\Throwable $exception) {
        Session::setFlash(
            'error',
            'Não foi possível cancelar a movimentação.'
        );

        header("Location: /saidas/{$id}");
        exit;
    }
}

public function retorno(int $id): void
{
    $movimentacao = $this->repository->buscarPorId($id);

    if ($movimentacao === null) {
        http_response_code(404);

        echo 'Movimentação não encontrada.';

        return;
    }

    if ($movimentacao['status'] !== 'ABERTA') {
        Session::setFlash(
            'error',
            'Somente uma movimentação aberta pode receber retorno.'
        );

        header("Location: /saidas/{$id}");
        exit;
    }

    if (!empty($movimentacao['data_hora_retorno'])) {
    Session::setFlash(
        'error',
        'O retorno desta movimentação já foi registrado.'
    );

    header("Location: /saidas/{$id}");
    exit;
}

    require __DIR__ . '/../../views/movimentacoes/retorno.php';
}

public function autorizarSaida(int $movimentacaoId): void
{
    try {
        $this->service->autorizarSaida($movimentacaoId);

        $_SESSION['success'] =
            'Saída autorizada com sucesso.';

    } catch (\Throwable $exception) {
        $_SESSION['error'] =
            $exception->getMessage();
    }

    header(
        'Location: /saidas/' . $movimentacaoId
    );

    exit;
}
}