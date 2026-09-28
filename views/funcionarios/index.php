<?php

declare(strict_types=1);

$titulo = 'Funcionários';

require __DIR__ . '/../layouts/header.php';
?>

<?php require __DIR__ . '/../layouts/sidebar.php'; ?>

<main class="main-content">
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
        <p>Nenhum funcionário carregado ainda.</p>
    </section>
</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>