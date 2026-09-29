<?php require __DIR__ . '/../../layouts/header.php'; ?>
<?php require __DIR__ . '/../../layouts/sidebar.php'; ?>

<?php $flash = \App\Core\Session::getFlash(); ?>


<main class="main-content">

<?php if ($flash): ?>

    <div class="alert alert-<?= htmlspecialchars($flash['tipo']) ?>">
        <?= htmlspecialchars($flash['mensagem']) ?>
    </div>

<?php endif; ?>

    <div class="page-header">
        <div>
            <h1>Modelos de Checklist</h1>
            <p>Gerencie os modelos de checklist utilizados na operação.</p>
        </div>

        <a href="/checklists/modelos/novo" class="btn btn-primary">
            Novo modelo
        </a>
    </div>

    <?php if (empty($modelos)): ?>

        <div class="content-card">
            <p>Nenhum modelo de checklist cadastrado.</p>
        </div>

    <?php else: ?>

        <div class="content-card">

            <div class="table-responsive">

                <table class="data-table">

                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Tipo de veículo</th>
                            <th>Descrição</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($modelos as $modelo): ?>

                            <tr>
                                <td>
                                    <?= htmlspecialchars($modelo['nome']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($modelo['tipo_veiculo_nome']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $modelo['descricao'] ?? '-'
                                    ) ?>
                                </td>

                                <td>
                                    <span class="status status-active">
                                        Ativo
                                    </span>
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