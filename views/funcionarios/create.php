<?php

declare(strict_types=1);

$titulo = 'Novo funcionário';

require __DIR__ . '/../layouts/header.php';
?>

<?php require __DIR__ . '/../layouts/sidebar.php'; ?>

<main class="main-content">
    <header class="page-header">
        <div>
            <h2>Novo funcionário</h2>
            <p>Cadastre um novo funcionário no sistema.</p>
        </div>

        <a href="/funcionarios" class="button">
            Voltar
        </a>
    </header>

    <section class="content-card">
        <form method="POST" action="/funcionarios">

            <div class="form-group">
                <label for="nome">Nome</label>

                <input
                    type="text"
                    id="nome"
                    name="nome"
                    maxlength="150"
                    required
                >
            </div>

            <div class="form-group">
                <label for="cpf">CPF</label>

                <input
                    type="text"
                    id="cpf"
                    name="cpf"
                    maxlength="14"
                    required
                >
            </div>

            <div class="form-group">
                <label for="matricula">Matrícula</label>

                <input
                    type="text"
                    id="matricula"
                    name="matricula"
                    maxlength="50"
                    required
                >
            </div>

            <div class="form-group">
                <label for="telefone">Telefone</label>

                <input
                    type="text"
                    id="telefone"
                    name="telefone"
                    maxlength="20"
                >
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    maxlength="150"
                >
            </div>

            <div class="form-group">
                <label for="funcao_id">Função</label>

                <select
                    id="funcao_id"
                    name="funcao_id"
                    required
                >
                    <option value="">Selecione uma função</option>

                    <?php foreach ($funcoes as $funcao): ?>
                        <option value="<?= $funcao['id'] ?>">
                            <?= htmlspecialchars($funcao['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="button">
                Cadastrar funcionário
            </button>

        </form>
    </section>
</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>