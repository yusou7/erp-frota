<?php require __DIR__ . '/../../layouts/header.php'; ?>

<?php require __DIR__ . '/../../layouts/sidebar.php'; ?>

<main class="main-content">

    <?php
    $flash = \App\Core\Session::getFlash();

    $respostasPorItem = [];

    $tipoChecklist = $tipo ?? 'SAIDA';

    $checklistBloqueado =
    $checklist['status'] === 'CONCLUIDO'
    || (
        $tipoChecklist === 'SAIDA'
        && ($checklist['status_autorizacao_saida'] ?? 'PENDENTE')
            === 'AUTORIZADA'
    );

$rotaChecklist = $tipoChecklist === 'RETORNO'
    ? '/saidas/' . (int) $checklist['movimentacao_id'] . '/checklist/retorno'
    : '/saidas/' . (int) $checklist['movimentacao_id'] . '/checklist';

    foreach ($respostas as $resposta) {
        $respostasPorItem[
            (int) $resposta['checklist_item_id']
        ] = $resposta;
    }
    ?>

    <?php if ($flash !== null): ?>
        <div class="alert alert-<?= htmlspecialchars($flash['tipo']) ?>">
            <?= htmlspecialchars($flash['mensagem']) ?>
        </div>
    <?php endif; ?>

    <div class="page-header">
        <div>
            <h1><?= htmlspecialchars($checklist['modelo_nome']) ?></h1>

            <p>
                Checklist da movimentação
                #<?= (int) $checklist['movimentacao_id'] ?>
            </p>
        </div>

        <div>
            <a
                href="/saidas/<?= (int) $checklist['movimentacao_id'] ?>"
                class="btn btn-secondary"
            >
                Voltar para a saída
            </a>
        </div>
    </div>

    <div class="content-card">

        <?php if (!empty($checklist['modelo_descricao'])): ?>
            <p>
                <?= htmlspecialchars($checklist['modelo_descricao']) ?>
            </p>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="data-table">

                <thead>
                    <tr>
                        <th>Ordem</th>
                        <th>Item</th>
                        <th>Obrigatório</th>
                        <th>Status</th>
                        <th>Observação</th>
                        <th>Ação</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (empty($itens)): ?>

                        <tr>
                            <td colspan="6">
                                Nenhum item cadastrado neste checklist.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($itens as $item): ?>

                            <?php
                            $itemId = (int) $item['id'];

                            $resposta = $respostasPorItem[$itemId]
                                ?? null;
                            ?>

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
                                    <select
    name="status"
    form="checklist-item-<?= $itemId ?>"
    required
    <?= $checklistBloqueado ? 'disabled' : '' ?>
>

                                        <option value="">
                                            Selecione
                                        </option>

                                        <option
                                            value="OK"
                                            <?= (
                                                $resposta !== null
                                                && $resposta['status'] === 'OK'
                                            ) ? 'selected' : '' ?>
                                        >
                                            OK
                                        </option>

                                        <option
                                            value="DEFEITO"
                                            <?= (
                                                $resposta !== null
                                                && $resposta['status'] === 'DEFEITO'
                                            ) ? 'selected' : '' ?>
                                        >
                                            Defeito
                                        </option>

                                        <option
                                            value="NAO_APLICA"
                                            <?= (
                                                $resposta !== null
                                                && $resposta['status'] === 'NAO_APLICA'
                                            ) ? 'selected' : '' ?>
                                        >
                                            Não se aplica
                                        </option>

                                    </select>
                                </td>

                                <td>
                                    <input
    type="text"
    name="observacao"
    form="checklist-item-<?= $itemId ?>"
    placeholder="Observação"
    <?= $checklistBloqueado ? 'disabled' : '' ?>
                                        value="<?= htmlspecialchars(
                                            $resposta['observacao'] ?? ''
                                        ) ?>"
                                    >
                                </td>

                                <td>

                                    <form
                                        id="checklist-item-<?= $itemId ?>"
                                        method="POST"
                                        action="<?= $rotaChecklist ?>/resposta"
                                    >

                                        <input
                                            type="hidden"
                                            name="checklist_item_id"
                                            value="<?= $itemId ?>"
                                        >

                                        <?php if (!$checklistBloqueado): ?>

    <button
        type="submit"
        class="btn btn-primary"
    >
        Salvar
    </button>

<?php endif; ?>

                                    </form>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </tbody>

            </table>
        </div>

                <?php if ($checklist['status'] === 'EM_ANDAMENTO'): ?>

            <div style="margin-top: 20px;">

                <form
                    method="POST"
                    action="<?= $rotaChecklist ?>/finalizar"
                >

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Finalizar checklist
                    </button>

                </form>

            </div>

        <?php else: ?>

            <div class="alert alert-success" style="margin-top: 20px;">
                Checklist concluído.
            </div>

        <?php endif; ?>

    </div>

</main>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>