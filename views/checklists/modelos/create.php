<?php require __DIR__ . '/../../layouts/header.php'; ?>
<?php require __DIR__ . '/../../layouts/sidebar.php'; ?>

<main class="main-content">

    <div class="page-header">
        <div>
            <h1>Novo modelo de checklist</h1>
            <p>Cadastre um modelo que poderá ser utilizado na operação.</p>
        </div>

        <a href="/checklists/modelos" class="btn btn-secondary">
            Voltar
        </a>
    </div>

    <div class="content-card">

        <form method="POST" action="/checklists/modelos">

            <div class="form-group">
                <label for="nome">
                    Nome
                </label>

                <input
                    type="text"
                    id="nome"
                    name="nome"
                    maxlength="100"
                    required
                >
            </div>

            <div class="form-group">
                <label for="descricao">
                    Descrição
                </label>

                <textarea
                    id="descricao"
                    name="descricao"
                    maxlength="255"
                    rows="3"
                ></textarea>
            </div>

            <div class="form-group">
                <label for="tipo_veiculo_id">
                    Tipo de veículo
                </label>

                <select
                    id="tipo_veiculo_id"
                    name="tipo_veiculo_id"
                    required
                >
                    <option value="">
                        Selecione
                    </option>

                    <?php foreach ($tiposVeiculos as $tipo): ?>

                        <option value="<?= (int) $tipo['id'] ?>">
                            <?= htmlspecialchars($tipo['nome']) ?>
                        </option>

                    <?php endforeach; ?>

                </select>
            </div>

            <div class="form-actions">

                <a
                    href="/checklists/modelos"
                    class="btn btn-secondary"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Salvar modelo
                </button>

            </div>

        </form>

    </div>

</main>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>