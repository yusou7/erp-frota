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
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (empty($movimentacoes)): ?>

                        <tr>
                            <td colspan="8">
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
                                    <?= htmlspecialchars(
                                        $movimentacao['status']
                                    ) ?>
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