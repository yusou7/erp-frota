<?php

declare(strict_types=1);

require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';

$flash = \App\Core\Session::getFlash();

$status = $movimentacao['status'];

$statusLabel = match ($status) {
    'ABERTA' => 'Aberta',
    'FINALIZADA' => 'Finalizada',
    'CANCELADA' => 'Cancelada',
    default => $status,
};
?>

<main class="main-content">

    <div class="page-header">
        <div>
            <h1>Movimentação #<?= (int) $movimentacao['id'] ?></h1>
            <p>Detalhes da saída e do retorno.</p>
        </div>

        <a href="/saidas" class="btn btn-secondary">
            Voltar
        </a>
    </div>

    <?php if ($flash !== null): ?>
        <div class="alert alert-<?= htmlspecialchars($flash['tipo']) ?>">
            <?= htmlspecialchars($flash['mensagem']) ?>
        </div>
    <?php endif; ?>

    <div class="content-card">

        <h2>Dados da saída</h2>

        <div class="form-grid">

            <div class="form-group">
                <label>Veículo</label>

                <div>
                    <?= htmlspecialchars(
                        $movimentacao['veiculo_numero']
                    ) ?>

                    <?php if (!empty($movimentacao['veiculo_placa'])): ?>
                        -
                        <?= htmlspecialchars(
                            $movimentacao['veiculo_placa']
                        ) ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <label>Carreta</label>

                <div>
                    <?php if (!empty($movimentacao['carreta_numero'])): ?>

                        <?= htmlspecialchars(
                            $movimentacao['carreta_numero']
                        ) ?>

                        <?php if (!empty($movimentacao['carreta_placa'])): ?>
                            -
                            <?= htmlspecialchars(
                                $movimentacao['carreta_placa']
                            ) ?>
                        <?php endif; ?>

                    <?php else: ?>

                        —

                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <label>Motorista</label>

                <div>
                    <?= htmlspecialchars(
                        $movimentacao['motorista_nome']
                    ) ?>
                </div>
            </div>

            <div class="form-group">
                <label>Status</label>

                <div>
                    <?= htmlspecialchars($statusLabel) ?>
                </div>
            </div>

            <div class="form-group">
                <label>Data e hora da saída</label>

                <div>
                    <?= htmlspecialchars(
                        date(
                            'd/m/Y H:i',
                            strtotime(
                                $movimentacao['data_hora_saida']
                            )
                        )
                    ) ?>
                </div>
            </div>

            <div class="form-group">
                <label>Indicador na saída</label>

                <div>
                    <?= htmlspecialchars(
                        (string) $movimentacao['indicador_saida']
                    ) ?>

                    <?= $movimentacao['veiculo_indicador_tipo'] === 'KM'
                        ? 'KM'
                        : 'horímetro'
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label>Destino</label>

                <div>
                    <?= !empty($movimentacao['destino'])
                        ? htmlspecialchars(
                            $movimentacao['destino']
                        )
                        : '—'
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label>Finalidade</label>

                <div>
                    <?= !empty($movimentacao['finalidade'])
                        ? htmlspecialchars(
                            $movimentacao['finalidade']
                        )
                        : '—'
                    ?>
                </div>
            </div>

            <div class="form-group form-group-full">
                <label>Observações da saída</label>

                <div>
                    <?= !empty($movimentacao['observacoes_saida'])
                        ? nl2br(
                            htmlspecialchars(
                                $movimentacao['observacoes_saida']
                            )
                        )
                        : '—'
                    ?>
                </div>
            </div>

        </div>

    </div>

    <div class="content-card">

        <h2>Dados do retorno</h2>

        <div class="form-grid">

            <div class="form-group">
                <label>Data e hora do retorno</label>

                <div>
                    <?= !empty(
                        $movimentacao['data_hora_retorno']
                    )
                        ? htmlspecialchars(
                            date(
                                'd/m/Y H:i',
                                strtotime(
                                    $movimentacao[
                                        'data_hora_retorno'
                                    ]
                                )
                            )
                        )
                        : 'Ainda não registrado'
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label>Indicador no retorno</label>

                <div>
                    <?= $movimentacao['indicador_retorno'] !== null
                        ? htmlspecialchars(
                            (string) $movimentacao[
                                'indicador_retorno'
                            ]
                        )
                        : 'Ainda não registrado'
                    ?>
                </div>
            </div>

            <div class="form-group form-group-full">
                <label>Observações do retorno</label>

                <div>
                    <?= !empty(
                        $movimentacao['observacoes_retorno']
                    )
                        ? nl2br(
                            htmlspecialchars(
                                $movimentacao[
                                    'observacoes_retorno'
                                ]
                            )
                        )
                        : '—'
                    ?>
                </div>
            </div>

        </div>

    </div>


    <?php if ($status === 'ABERTA'): ?>

    <div class="content-card">

        <div class="form-actions">

            <a
                href="/saidas/<?= (int) $movimentacao['id'] ?>/retorno"
                class="btn btn-primary"
            >
                Registrar retorno
            </a>

            <form
                action="/saidas/<?= (int) $movimentacao['id'] ?>/cancelar"
                method="POST"
                style="display: inline;"
            >
                <button
                    type="submit"
                    class="btn btn-secondary"
                    onclick="return confirm(
                        'Tem certeza que deseja cancelar esta movimentação?'
                    );"
                >
                    Cancelar movimentação
                </button>
            </form>

        </div>

    </div>

<?php endif; ?>
</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>