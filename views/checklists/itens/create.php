<?php require __DIR__ . '/../../layouts/header.php'; ?>
<?php require __DIR__ . '/../../layouts/sidebar.php'; ?>

<main class="main-content">

    <div class="page-header">

        <div>
            <h1>Novo item de checklist</h1>
            <p>Adicione um item ao modelo de checklist.</p>
        </div>

        <a
            href="/checklists/modelos/<?= (int) $modeloId ?>/itens"
            class="btn btn-secondary"
        >
            Voltar
        </a>

    </div>

    <div class="content-card">

        <form
            method="POST"
            action="/checklists/modelos/<?= (int) $modeloId ?>/itens"
        >

            <div class="form-group">

                <label for="descricao">
                    Descrição
                </label>

                <input
                    type="text"
                    id="descricao"
                    name="descricao"
                    maxlength="255"
                    required
                >

            </div>

            <div class="form-group">

                <label for="ordem">
                    Ordem
                </label>

                <input
                    type="number"
                    id="ordem"
                    name="ordem"
                    min="1"
                    value="1"
                    required
                >

            </div>

            <div class="form-group">

                <label>
                    <input
                        type="checkbox"
                        name="obrigatorio"
                        value="1"
                        checked
                    >

                    Item obrigatório
                </label>

            </div>

            <div class="form-actions">

                <a
                    href="/checklists/modelos/<?= (int) $modeloId ?>/itens"
                    class="btn btn-secondary"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Salvar item
                </button>

            </div>

        </form>

    </div>

</main>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>