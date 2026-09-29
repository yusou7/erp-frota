<?php require __DIR__ . '/../../layouts/header.php'; ?>
<?php require __DIR__ . '/../../layouts/sidebar.php'; ?>

<main class="main-content">

    <?php $flash = \App\Core\Session::getFlash(); ?>

    <?php if ($flash): ?>

        <div class="alert alert-<?= htmlspecialchars($flash['tipo']) ?>">
            <?= htmlspecialchars($flash['mensagem']) ?>
        </div>

    <?php endif; ?>

    <div class="page-header">

        <div>
            <h1>Itens do Checklist</h1>
            <p>Gerencie os itens deste modelo de checklist.</p>
        </div>

        <div>

            <a
                href="/checklists/modelos"
                class="btn btn-secondary"
            >
                Voltar
            </a>

            <a
                href="/checklists/modelos/<?= (int) $modeloId ?>/itens/novo"
                class="btn btn-primary"
            >
                Novo item
            </a>

        </div>

    </div>

    <?php if (empty($itens)): ?>

        <div class="content-card">
            <p>Nenhum item cadastrado neste modelo.</p>
        </div>

    <?php else: ?>

        <div class="content-card">

            <div class="table-responsive">

                <table class="data-table">

                    <thead>
                        <tr>
                            <th>Ordem</th>
                            <th>Descrição</th>
                            <th>Obrigatório</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($itens as $item): ?>

                            <tr>

                                <td>
                                    <?= (int) $item['ordem'] ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $item['descricao']
                                    ) ?>
                                </td>

                                <td>

                                    <?php if ($item['obrigatorio']): ?>

                                        Sim

                                    <?php else: ?>

                                        Não

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <span class="status status-active">
                                        Ativo
                                    </span>

                                </td>

                                <td>

    <a
        href="/checklists/modelos/<?= (int) $modeloId ?>/itens/<?= (int) $item['id'] ?>/editar"
        class="btn btn-secondary"
    >
        Editar
    </a>

    <form
        method="POST"
        action="/checklists/modelos/<?= (int) $modeloId ?>/itens/<?= (int) $item['id'] ?>/desativar"
        style="display: inline;"
    >

        <button
            type="submit"
            class="btn btn-secondary"
            onclick="return confirm('Deseja realmente desativar este item?');"
        >
            Desativar
        </button>

    </form>

</td>


                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>

    <?php endif; ?>

</main>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>