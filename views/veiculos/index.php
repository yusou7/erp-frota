<?php

declare(strict_types=1);

use App\Core\Session;

$titulo = 'Veículos';

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
            <h2>Veículos</h2>
            <p>Gerenciamento dos veículos da empresa.</p>
        </div>

        <a href="/veiculos/novo" class="button">
            Novo veículo
        </a>
    </header>

    <section class="content-card">

        <?php if (empty($veiculos)): ?>

            <p>Nenhum veículo cadastrado.</p>

        <?php else: ?>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Número</th>
                        <th>Tipo</th>
                        <th>Placa</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Indicador</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($veiculos as $veiculo): ?>

                        <tr>

                            <td>
                                <?= htmlspecialchars($veiculo['numero']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($veiculo['tipo_veiculo']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($veiculo['placa'] ?? '-') ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($veiculo['marca']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($veiculo['modelo']) ?>
                            </td>

                            <td>
                                <?php if ($veiculo['indicador_tipo'] === 'KM'): ?>

                                    <?= number_format(
                                        (float) $veiculo['indicador_atual'],
                                        2,
                                        ',',
                                        '.'
                                    ) ?>
                                    km

                                <?php elseif ($veiculo['indicador_tipo'] === 'HORIMETRO'): ?>

                                    <?= number_format(
                                        (float) $veiculo['indicador_atual'],
                                        2,
                                        ',',
                                        '.'
                                    ) ?>
                                    h

                                <?php else: ?>

                                    Não aplicável

                                <?php endif; ?>
                            </td>

                            <td>
                                <?= $veiculo['ativo'] ? 'Ativo' : 'Inativo' ?>
                            </td>

                            <td>

                                <a
                                    href="/veiculos/<?= (int) $veiculo['id'] ?>/editar"
                                    class="button button-small"
                                >
                                    Editar
                                </a>

                                <?php if ($veiculo['ativo']): ?>

                                    <form
                                        method="POST"
                                        action="/veiculos/<?= (int) $veiculo['id'] ?>/inativar"
                                        style="display: inline;"
                                        onsubmit="return confirm('Tem certeza que deseja inativar este veículo?');"
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