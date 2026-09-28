<?php

declare(strict_types=1);

$titulo = 'Funcionários';

use App\Core\Session;

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
            <h2>Funcionários</h2>
            <p>Gerenciamento dos funcionários da empresa.</p>
        </div>

        <a href="/funcionarios/novo" class="button">
            Novo funcionário
        </a>
    </header>

    <section class="content-card">
    <?php if (empty($funcionarios)): ?>

        <p>Nenhum funcionário cadastrado.</p>

    <?php else: ?>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Matrícula</th>
                    <th>CPF</th>
                    <th>Função</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($funcionarios as $funcionario): ?>

                    <tr>
                        <td>
                            <?= htmlspecialchars($funcionario['nome']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($funcionario['matricula']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($funcionario['cpf']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($funcionario['funcao']) ?>
                        </td>

                        <td>
    <a
        href="/funcionarios/<?= (int) $funcionario['id'] ?>/editar"
        class="button button-small"
    >
        Editar
    </a>

    <?php if ($funcionario['ativo']): ?>

        <form
            method="POST"
            action="/funcionarios/<?= (int) $funcionario['id'] ?>/inativar"
            style="display: inline;"
            onsubmit="return confirm('Tem certeza que deseja inativar este funcionário?');"
        >
            <button
                type="submit"
                class="button button-small button-danger"
            >
                Inativar
            </button>
        </form>

    <?php endif; ?>
</td>
                    </tr>

                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>
</section>
</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>