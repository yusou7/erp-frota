<?php

declare(strict_types=1);

use App\Core\Session;

$titulo = 'Editar veículo';

$flash = Session::getFlash();

require __DIR__ . '/../layouts/header.php';
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
            <h2>Editar veículo</h2>
            <p>Atualize os dados do veículo.</p>
        </div>

        <a href="/veiculos" class="button">
            Voltar
        </a>
    </header>

    <section class="content-card">

        <form
            method="POST"
            action="/veiculos/<?= (int) $veiculo['id'] ?>"
        >

            <div class="form-group">
                <label for="numero">Número do veículo</label>

                <input
                    type="text"
                    id="numero"
                    name="numero"
                    maxlength="20"
                    value="<?= htmlspecialchars($veiculo['numero']) ?>"
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

                        <option
                            value="<?= (int) $tipo['id'] ?>"
                            <?= (int) $tipo['id'] === (int) $veiculo['tipo_veiculo_id']
                                ? 'selected'
                                : '' ?>
                        >
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
                    value="<?= htmlspecialchars($veiculo['placa'] ?? '') ?>"
                >
            </div>

            <div class="form-group">
                <label for="renavam">RENAVAM</label>

                <input
                    type="text"
                    id="renavam"
                    name="renavam"
                    maxlength="20"
                    value="<?= htmlspecialchars($veiculo['renavam'] ?? '') ?>"
                >
            </div>

            <div class="form-group">
                <label for="marca">Marca</label>

                <input
                    type="text"
                    id="marca"
                    name="marca"
                    maxlength="100"
                    value="<?= htmlspecialchars($veiculo['marca']) ?>"
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
                    value="<?= htmlspecialchars($veiculo['modelo']) ?>"
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
                    value="<?= htmlspecialchars((string) ($veiculo['ano_fabricacao'] ?? '')) ?>"
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
                    value="<?= htmlspecialchars((string) ($veiculo['ano_modelo'] ?? '')) ?>"
                >
            </div>

            <div class="form-group">
                <label for="cor">Cor</label>

                <input
                    type="text"
                    id="cor"
                    name="cor"
                    maxlength="50"
                    value="<?= htmlspecialchars($veiculo['cor'] ?? '') ?>"
                >
            </div>

            <div class="form-group">
                <label for="combustivel">Combustível</label>

                <input
                    type="text"
                    id="combustivel"
                    name="combustivel"
                    maxlength="30"
                    value="<?= htmlspecialchars($veiculo['combustivel'] ?? '') ?>"
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

                    <option
                        value="KM"
                        <?= $veiculo['indicador_tipo'] === 'KM'
                            ? 'selected'
                            : '' ?>
                    >
                        KM
                    </option>

                    <option
                        value="HORIMETRO"
                        <?= $veiculo['indicador_tipo'] === 'HORIMETRO'
                            ? 'selected'
                            : '' ?>
                    >
                        Horímetro
                    </option>

                    <option
                        value="NAO_APLICAVEL"
                        <?= $veiculo['indicador_tipo'] === 'NAO_APLICAVEL'
                            ? 'selected'
                            : '' ?>
                    >
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
                    value="<?= htmlspecialchars((string) ($veiculo['indicador_atual'] ?? '')) ?>"
                >
            </div>

            <div class="form-group">
                <label for="observacoes">Observações</label>

                <textarea
                    id="observacoes"
                    name="observacoes"
                    rows="5"
                ><?= htmlspecialchars($veiculo['observacoes'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="button">
                Salvar alterações
            </button>

        </form>

    </section>

</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>