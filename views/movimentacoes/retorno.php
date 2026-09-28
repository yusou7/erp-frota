<?php

declare(strict_types=1);

require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';

$flash = \App\Core\Session::getFlash();

$indicadorTipo = $movimentacao['veiculo_indicador_tipo'];

$unidadeIndicador = $indicadorTipo === 'KM'
    ? 'KM'
    : 'Horímetro';
?>

<main class="main-content">

    <div class="page-header">
        <div>
            <h1>Registrar retorno</h1>
            <p>
                Movimentação #<?= (int) $movimentacao['id'] ?>
            </p>
        </div>

        <a
            href="/saidas/<?= (int) $movimentacao['id'] ?>"
            class="btn btn-secondary"
        >
            Voltar
        </a>
    </div>

    <?php if ($flash !== null): ?>
        <div class="alert alert-<?= htmlspecialchars($flash['tipo']) ?>">
            <?= htmlspecialchars($flash['mensagem']) ?>
        </div>
    <?php endif; ?>

    <div class="content-card">

        <h2>Resumo da saída</h2>

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
                <label>Motorista</label>

                <div>
                    <?= htmlspecialchars(
                        $movimentacao['motorista_nome']
                    ) ?>
                </div>
            </div>

            <div class="form-group">
                <label>Saída</label>

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

                    <?= htmlspecialchars($unidadeIndicador) ?>
                </div>
            </div>

        </div>

    </div>

    <div class="content-card">

        <h2>Dados do retorno</h2>

        <form
            action="/saidas/<?= (int) $movimentacao['id'] ?>/retorno"
            method="POST"
        >

            <div class="form-grid">

                <div class="form-group">
                    <label for="data_hora_retorno">
                        Data e hora do retorno *
                    </label>

                    <input
                        type="datetime-local"
                        name="data_hora_retorno"
                        id="data_hora_retorno"
                        value="<?= date('Y-m-d\TH:i') ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="indicador_retorno">
                        Indicador no retorno *
                    </label>

                    <input
                        type="number"
                        name="indicador_retorno"
                        id="indicador_retorno"
                        min="<?= htmlspecialchars(
                            (string) $movimentacao['indicador_saida']
                        ) ?>"
                        step="0.01"
                        required
                    >

                    <small>
                        O valor não pode ser menor que
                        <?= htmlspecialchars(
                            (string) $movimentacao['indicador_saida']
                        ) ?>
                        <?= htmlspecialchars($unidadeIndicador) ?>.
                    </small>
                </div>

                <div class="form-group form-group-full">
                    <label for="observacoes_retorno">
                        Observações do retorno
                    </label>

                    <textarea
                        name="observacoes_retorno"
                        id="observacoes_retorno"
                        rows="4"
                    ></textarea>
                </div>

            </div>

            <div class="form-actions">

                <a
                    href="/saidas/<?= (int) $movimentacao['id'] ?>"
                    class="btn btn-secondary"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Registrar retorno
                </button>

            </div>

        </form>

    </div>

</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>