<?php

declare(strict_types=1);

$titulo = 'Novo veículo';

require __DIR__ . '/../layouts/header.php';
?>

<?php

$flash = \App\Core\Session::getFlash();
?>

<?php require __DIR__ . '/../layouts/sidebar.php'; ?>

<main class="main-content">

<?php if ($flash !== null): ?>

    <div class="alert alert-<?= htmlspecialchars($flash['tipo']) ?>">
        <?= htmlspecialchars($flash['mensagem']) ?>
    </div>

<?php endif; ?>

    <header class="page-header">
        <div>
            <h2>Novo veículo</h2>
            <p>Cadastre um novo veículo no sistema.</p>
        </div>

        <a href="/veiculos" class="button">
            Voltar
        </a>
    </header>

    <section class="content-card">

        <form method="POST" action="/veiculos">

            <div class="form-group">
                <label for="numero">Número do veículo</label>

                <input
                    type="text"
                    id="numero"
                    name="numero"
                    maxlength="20"
                    required
                >
            </div>

            <div class="form-group">
                <label for="tipo_veiculo_id">Tipo de veículo</label>

                <select
                    id="tipo_veiculo_id"
                    name="tipo_veiculo_id"
                    required
                >
                    <option value="">Selecione um tipo</option>

                    <?php foreach ($tiposVeiculos as $tipo): ?>

                        <option value="<?= (int) $tipo['id'] ?>">
                            <?= htmlspecialchars($tipo['nome']) ?>
                        </option>

                    <?php endforeach; ?>

                </select>
            </div>

            <div class="form-group">
                <label for="placa">Placa</label>

                <input
                    type="text"
                    id="placa"
                    name="placa"
                    maxlength="10"
                >
            </div>

            <div class="form-group">
                <label for="renavam">RENAVAM</label>

                <input
                    type="text"
                    id="renavam"
                    name="renavam"
                    maxlength="20"
                >
            </div>

            <div class="form-group">
                <label for="marca">Marca</label>

                <input
                    type="text"
                    id="marca"
                    name="marca"
                    maxlength="100"
                    required
                >
            </div>

            <div class="form-group">
                <label for="modelo">Modelo</label>

                <input
                    type="text"
                    id="modelo"
                    name="modelo"
                    maxlength="100"
                    required
                >
            </div>

            <div class="form-group">
                <label for="ano_fabricacao">Ano de fabricação</label>

                <input
                    type="number"
                    id="ano_fabricacao"
                    name="ano_fabricacao"
                    min="1900"
                    max="<?= date('Y') + 1 ?>"
                >
            </div>

            <div class="form-group">
                <label for="ano_modelo">Ano do modelo</label>

                <input
                    type="number"
                    id="ano_modelo"
                    name="ano_modelo"
                    min="1900"
                    max="<?= date('Y') + 1 ?>"
                >
            </div>

            <div class="form-group">
                <label for="cor">Cor</label>

                <input
                    type="text"
                    id="cor"
                    name="cor"
                    maxlength="50"
                >
            </div>

            <div class="form-group">
                <label for="combustivel">Combustível</label>

                <input
                    type="text"
                    id="combustivel"
                    name="combustivel"
                    maxlength="30"
                    placeholder="Ex.: Diesel, Gasolina, Gás"
                >
            </div>

            <div class="form-group">
                <label for="indicador_tipo">Tipo de indicador</label>

                <select
                    id="indicador_tipo"
                    name="indicador_tipo"
                    required
                >
                    <option value="">Selecione</option>
                    <option value="KM">KM</option>
                    <option value="HORIMETRO">Horímetro</option>
                    <option value="NAO_APLICAVEL">
                        Não aplicável
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="indicador_atual">Indicador atual</label>

                <input
                    type="number"
                    id="indicador_atual"
                    name="indicador_atual"
                    min="0"
                    step="0.01"
                    placeholder="Ex.: 185420"
                >
            </div>

            <div class="form-group">
                <label for="observacoes">Observações</label>

                <textarea
                    id="observacoes"
                    name="observacoes"
                    rows="5"
                ></textarea>
            </div>

            <button type="submit" class="button">
                Cadastrar veículo
            </button>

        </form>

    </section>

</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>