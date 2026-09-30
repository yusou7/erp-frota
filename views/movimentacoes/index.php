<?php

declare(strict_types=1);

require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';

$flash = \App\Core\Session::getFlash();
?>

<main class="main-content">

    <div class="page-header">
        <div>
            <h1>Saídas e Retornos</h1>
            <p>Controle das movimentações da frota.</p>
        </div>

        <a href="/saidas/nova" class="btn btn-primary">
            Nova saída
        </a>
    </div>

    <?php if ($flash !== null): ?>
        <div class="alert alert-<?= htmlspecialchars($flash['tipo']) ?>">
            <?= htmlspecialchars($flash['mensagem']) ?>
        </div>
    <?php endif; ?>

    <div class="content-card">

    <form method="GET" action="/saidas" class="filters-form">

        <div class="form-group">
            <label for="status">
                Status da movimentação
            </label>

            <select
                id="status"
                name="status"
            >
                <option value="">
                    Todos
                </option>

                <option
                    value="ABERTA"
                    <?= (
                        ($filtros['status'] ?? '')
                        === 'ABERTA'
                    ) ? 'selected' : '' ?>
                >
                    Aberta
                </option>

                <option
                    value="FINALIZADA"
                    <?= (
                        ($filtros['status'] ?? '')
                        === 'FINALIZADA'
                    ) ? 'selected' : '' ?>
                >
                    Finalizada
                </option>

                <option
                    value="CANCELADA"
                    <?= (
                        ($filtros['status'] ?? '')
                        === 'CANCELADA'
                    ) ? 'selected' : '' ?>
                >
                    Cancelada
                </option>
            </select>
        </div>

        <div class="form-group">
    <label for="veiculo_id">
        Veículo
    </label>

    <select
        id="veiculo_id"
        name="veiculo_id"
    >
        <option value="">
            Todos
        </option>

        <?php foreach ($veiculos as $veiculo): ?>

            <option
                value="<?= (int) $veiculo['id'] ?>"
                <?= (
                    (string) ($filtros['veiculo_id'] ?? '')
                    === (string) $veiculo['id']
                ) ? 'selected' : '' ?>
            >
                <?= htmlspecialchars($veiculo['numero']) ?>
                -
                <?= htmlspecialchars($veiculo['placa']) ?>
            </option>

        <?php endforeach; ?>

    </select>
</div>

<div class="form-group">
    <label for="motorista_id">
        Motorista
    </label>

    <select
        id="motorista_id"
        name="motorista_id"
    >
        <option value="">
            Todos
        </option>

        <?php foreach ($funcionarios as $funcionario): ?>

            <option
                value="<?= (int) $funcionario['id'] ?>"
                <?= (
                    (string) ($filtros['motorista_id'] ?? '')
                    === (string) $funcionario['id']
                ) ? 'selected' : '' ?>
            >
                <?= htmlspecialchars($funcionario['nome']) ?>
            </option>

        <?php endforeach; ?>

    </select>
</div>

        <div class="form-group">
    <label for="status_autorizacao_saida">
        Autorização da saída
    </label>

    <select
        id="status_autorizacao_saida"
        name="status_autorizacao_saida"
    >
        <option value="">
            Todas
        </option>

        <option
            value="PENDENTE"
            <?= (
                ($filtros['status_autorizacao_saida'] ?? '')
                === 'PENDENTE'
            ) ? 'selected' : '' ?>
        >
            Pendente
        </option>

        <option
            value="AUTORIZADA"
            <?= (
                ($filtros['status_autorizacao_saida'] ?? '')
                === 'AUTORIZADA'
            ) ? 'selected' : '' ?>
        >
            Autorizada
        </option>

        <option
            value="RECUSADA"
            <?= (
                ($filtros['status_autorizacao_saida'] ?? '')
                === 'RECUSADA'
            ) ? 'selected' : '' ?>
        >
            Recusada
        </option>
    </select>
</div>

<div class="form-group">
    <label for="data_inicial">
        Data inicial
    </label>

    <input
        type="date"
        id="data_inicial"
        name="data_inicial"
        value="<?= htmlspecialchars(
            $filtros['data_inicial'] ?? ''
        ) ?>"
    >
</div>

<div class="form-group">
    <label for="data_final">
        Data final
    </label>

    <input
        type="date"
        id="data_final"
        name="data_final"
        value="<?= htmlspecialchars(
            $filtros['data_final'] ?? ''
        ) ?>"
    >
</div>

        <div>
            <button
                type="submit"
                class="btn btn-primary"
            >
                Filtrar
            </button>

            <a
                href="/saidas"
                class="btn btn-secondary"
            >
                Limpar
            </a>
        </div>

    </form>

    <div class="table-responsive">

        <table class="data-table">

                <thead>
                    <tr>
                        <th>Saída</th>
                        <th>Veículo</th>
                        <th>Carreta</th>
                        <th>Motorista</th>
                        <th>Destino</th>
                        <th>Retorno</th>
                        <th>Movimentação</th>
                        <th>Autorização</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (empty($movimentacoes)): ?>

                        <tr>
                            <td colspan="9">
                                Nenhuma movimentação encontrada.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($movimentacoes as $movimentacao): ?>

                            <tr>

                                <td>
                                    <?= htmlspecialchars(
                                        date(
                                            'd/m/Y H:i',
                                            strtotime(
                                                $movimentacao['data_hora_saida']
                                            )
                                        )
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $movimentacao['veiculo_numero']
                                    ) ?>

                                    <?php if (!empty($movimentacao['veiculo_placa'])): ?>
                                        <br>
                                        <small>
                                            <?= htmlspecialchars(
                                                $movimentacao['veiculo_placa']
                                            ) ?>
                                        </small>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if (!empty($movimentacao['carreta_numero'])): ?>

                                        <?= htmlspecialchars(
                                            $movimentacao['carreta_numero']
                                        ) ?>

                                        <?php if (!empty($movimentacao['carreta_placa'])): ?>
                                            <br>
                                            <small>
                                                <?= htmlspecialchars(
                                                    $movimentacao['carreta_placa']
                                                ) ?>
                                            </small>
                                        <?php endif; ?>

                                    <?php else: ?>

                                        —

                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $movimentacao['motorista_nome']
                                    ) ?>
                                </td>

                                <td>
                                    <?= !empty($movimentacao['destino'])
                                        ? htmlspecialchars(
                                            $movimentacao['destino']
                                        )
                                        : '—'
                                    ?>
                                </td>

                                <td>
                                    <?php if (
                                        !empty(
                                            $movimentacao['data_hora_retorno']
                                        )
                                    ): ?>

                                        <?= htmlspecialchars(
                                            date(
                                                'd/m/Y H:i',
                                                strtotime(
                                                    $movimentacao[
                                                        'data_hora_retorno'
                                                    ]
                                                )
                                            )
                                        ) ?>

                                    <?php else: ?>

                                        —

                                    <?php endif; ?>
                                </td>

                                <td>
    <?php
    $statusMovimentacao = match (
        $movimentacao['status']
    ) {
        'ABERTA' => 'Aberta',
        'FINALIZADA' => 'Finalizada',
        'CANCELADA' => 'Cancelada',
        default => $movimentacao['status'],
    };

    $classeStatusMovimentacao = match (
        $movimentacao['status']
    ) {
        'ABERTA' => 'status-warning',
        'FINALIZADA' => 'status-success',
        'CANCELADA' => 'status-danger',
        default => 'status-neutral',
    };
    ?>

    <span class="status-badge <?= $classeStatusMovimentacao ?>">
        <?= htmlspecialchars($statusMovimentacao) ?>
    </span>
</td>

<td>
    <?php
    $statusAutorizacao = $movimentacao[
        'status_autorizacao_saida'
    ] ?? 'PENDENTE';

    $textoAutorizacao = match (
        $statusAutorizacao
    ) {
        'PENDENTE' => 'Pendente',
        'AUTORIZADA' => 'Autorizada',
        'RECUSADA' => 'Recusada',
        default => $statusAutorizacao,
    };

    $classeStatusAutorizacao = match (
        $statusAutorizacao
    ) {
        'PENDENTE' => 'status-warning',
        'AUTORIZADA' => 'status-success',
        'RECUSADA' => 'status-danger',
        default => 'status-neutral',
    };
    ?>

    <span class="status-badge <?= $classeStatusAutorizacao ?>">
        <?= htmlspecialchars($textoAutorizacao) ?>
    </span>
</td>

                                <td>

                                    <a
                                        href="/saidas/<?= (int) $movimentacao['id'] ?>"
                                        class="btn btn-secondary"
                                    >
                                        Visualizar
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>