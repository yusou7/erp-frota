<?php

declare(strict_types=1);

require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';

$flash = \App\Core\Session::getFlash();
?>

<main class="main-content">

    <div class="page-header">
        <div>
            <h1>Nova saída</h1>
            <p>Registre a saída de um veículo da frota.</p>
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

        <form action="/saidas" method="POST">

            <div class="form-grid">

                <div class="form-group">
                    <label for="veiculo_id">
                        Veículo *
                    </label>

                    <select
                        name="veiculo_id"
                        id="veiculo_id"
                        required
                    >
                        <option value="">
                            Selecione o veículo
                        </option>

                        <?php foreach ($veiculos as $veiculo): ?>

                            <option
                                value="<?= (int) $veiculo['id'] ?>"
                            >
                                <?= htmlspecialchars(
                                    $veiculo['numero']
                                ) ?>
                                -
                                <?= htmlspecialchars(
                                    $veiculo['marca']
                                ) ?>
                                <?= htmlspecialchars(
                                    $veiculo['modelo']
                                ) ?>
                                (<?= htmlspecialchars(
                                    $veiculo['indicador_tipo']
                                ) ?>:
                                <?= htmlspecialchars(
                                    (string) $veiculo['indicador_atual']
                                ) ?>)
                            </option>

                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="form-group">
                    <label for="carreta_id">
                        Carreta
                    </label>

                    <select
                        name="carreta_id"
                        id="carreta_id"
                    >
                        <option value="">
                            Sem carreta
                        </option>

                        <?php foreach ($carretas as $carreta): ?>

                            <option
                                value="<?= (int) $carreta['id'] ?>"
                            >
                                <?= htmlspecialchars(
                                    $carreta['numero']
                                ) ?>

                                <?php if (!empty($carreta['placa'])): ?>
                                    -
                                    <?= htmlspecialchars(
                                        $carreta['placa']
                                    ) ?>
                                <?php endif; ?>
                            </option>

                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="form-group">
                    <label for="motorista_id">
                        Motorista *
                    </label>

                    <select
                        name="motorista_id"
                        id="motorista_id"
                        required
                    >
                        <option value="">
                            Selecione o motorista
                        </option>

                        <?php foreach ($motoristas as $motorista): ?>

                            <option
                                value="<?= (int) $motorista['id'] ?>"
                            >
                                <?= htmlspecialchars(
                                    $motorista['nome']
                                ) ?>
                                -
                                Matrícula:
                                <?= htmlspecialchars(
                                    $motorista['matricula']
                                ) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="form-group">
                    <label for="data_hora_saida">
                        Data e hora da saída *
                    </label>

                    <input
                        type="datetime-local"
                        name="data_hora_saida"
                        id="data_hora_saida"
                        value="<?= date('Y-m-d\TH:i') ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="indicador_saida">
                        Indicador na saída *
                    </label>

                    <input
                        type="number"
                        name="indicador_saida"
                        id="indicador_saida"
                        min="0"
                        step="0.01"
                        required
                    >

                    <small>
                        Informe o KM ou horímetro atual do veículo.
                    </small>
                </div>

                <div class="form-group">
                    <label for="destino">
                        Destino
                    </label>

                    <input
                        type="text"
                        name="destino"
                        id="destino"
                        maxlength="255"
                    >
                </div>

                <div class="form-group">
                    <label for="finalidade">
                        Finalidade
                    </label>

                    <input
                        type="text"
                        name="finalidade"
                        id="finalidade"
                        maxlength="255"
                    >
                </div>

                <div class="form-group form-group-full">
                    <label for="observacoes_saida">
                        Observações da saída
                    </label>

                    <textarea
                        name="observacoes_saida"
                        id="observacoes_saida"
                        rows="4"
                    ></textarea>
                </div>

            </div>

            <div class="form-actions">

                <a
                    href="/saidas"
                    class="btn btn-secondary"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Registrar saída
                </button>

            </div>

        </form>

    </div>

</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>