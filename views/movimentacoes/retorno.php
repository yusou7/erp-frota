<?php

declare(strict_types=1);

require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';

$flash = \App\Core\Session::getFlash();

$indicadorTipo = $movimentacao['veiculo_indicador_tipo'];

$unidadeIndicador = $indicadorTipo === 'KM'
    ? 'KM'
    : 'Horímetro';
?>

<main class="main-content">

    <div class="page-header">
        <div>
            <h1>Registrar retorno</h1>
            <p>
                Movimentação #<?= (int) $movimentacao['id'] ?>
            </p>
        </div>

        <a
            href="/saidas/<?= (int) $movimentacao['id'] ?>"
            class="btn btn-secondary"
        >
            Voltar
        </a>
    </div>

    <?php if ($flash !== null): ?>
        <div class="alert alert-<?= htmlspecialchars($flash['tipo']) ?>">
            <?= htmlspecialchars($flash['mensagem']) ?>
        </div>
    <?php endif; ?>

    <div class="content-card">

        <h2>Resumo da saída</h2>

        <div class="form-grid">

            <div class="form-group">
                <label>Veículo</label>

                <div>
                    <?= htmlspecialchars(
                        $movimentacao['veiculo_numero']
                    ) ?>

                    <?php if (!empty($movimentacao['veiculo_placa'])): ?>
                        -
                        <?= htmlspecialchars(
                            $movimentacao['veiculo_placa']
                        ) ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <label>Motorista</label>

                <div>
                    <?= htmlspecialchars(
                        $movimentacao['motorista_nome']
                    ) ?>
                </div>
            </div>

            <div class="form-group">
                <label>Saída</label>

                <div>
                    <?= htmlspecialchars(
                        date(
                            'd/m/Y H:i',
                            strtotime(
                                $movimentacao['data_hora_saida']
                            )
                        )
                    ) ?>
                </div>
            </div>

            <div class="form-group">
                <label>Indicador na saída</label>

                <div>
                    <?= htmlspecialchars(
                        (string) $movimentacao['indicador_saida']
                    ) ?>

                    <?= htmlspecialchars($unidadeIndicador) ?>
                </div>
            </div>

        </div>

    </div>

    <div class="content-card">

        <h2>Dados do retorno</h2>

        <form
            action="/saidas/<?= (int) $movimentacao['id'] ?>/retorno"
            method="POST"
        >

            <div class="form-grid">

                <div class="form-group">
                    <label for="data_hora_retorno">
                        Data e hora do retorno *
                    </label>

                    <input
                        type="datetime-local"
                        name="data_hora_retorno"
                        id="data_hora_retorno"
                        value="<?= date('Y-m-d\TH:i') ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="indicador_retorno">
                        Indicador no retorno *
                    </label>

                    <input
                        type="number"
                        name="indicador_retorno"
                        id="indicador_retorno"
                        min="<?= htmlspecialchars(
                            (string) $movimentacao['indicador_saida']
                        ) ?>"
                        step="0.01"
                        required
                    >

                    <small>
                        O valor não pode ser menor que
                        <?= htmlspecialchars(
                            (string) $movimentacao['indicador_saida']
                        ) ?>
                        <?= htmlspecialchars($unidadeIndicador) ?>.
                    </small>
                </div>

                <div class="form-group form-group-full">
                    <label for="observacoes_retorno">
                        Observações do retorno
                    </label>

                    <textarea
                        name="observacoes_retorno"
                        id="observacoes_retorno"
                        rows="4"
                    ></textarea>
                </div>

            </div>

            <div class="form-actions">

                <a
                    href="/saidas/<?= (int) $movimentacao['id'] ?>"
                    class="btn btn-secondary"
                >
                    Cancelar
                </a>

                <div id="checklist-container" style="display: none; margin-top: 20px;">
    <h3>Checklist de retorno</h3>

    <div id="checklist-loading">
        Carregando checklist...
    </div>

    <div id="checklist-sem-modelo" style="display: none;">
        Este veículo não possui checklist configurado.
    </div>

    <table
        id="checklist-tabela"
        style="display: none; width: 100%;"
    >
        <thead>
            <tr>
                <th>Item</th>
                <th>Obrigatório</th>
                <th>Status</th>
                <th>Observação</th>
            </tr>
        </thead>

        <tbody id="checklist-itens"></tbody>
    </table>
</div>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Registrar retorno
                </button>

            </div>

        </form>

        <script>
const checklistContainer = document.getElementById(
    'checklist-container'
);

const checklistLoading = document.getElementById(
    'checklist-loading'
);

const checklistSemModelo = document.getElementById(
    'checklist-sem-modelo'
);

const checklistTabela = document.getElementById(
    'checklist-tabela'
);

const checklistItens = document.getElementById(
    'checklist-itens'
);

const veiculoId = <?= (int) $movimentacao['veiculo_id'] ?>;

checklistContainer.style.display = 'block';

fetch('/saidas/checklist-veiculo/' + veiculoId)
    .then(function (response) {
        if (!response.ok) {
            throw new Error(
                'Não foi possível carregar o checklist.'
            );
        }

        return response.json();
    })
    .then(function (data) {
        checklistLoading.style.display = 'none';

        if (!data.modelo || !data.itens.length) {
            checklistSemModelo.style.display = 'block';
            return;
        }

        data.itens.forEach(function (item) {
            const tr = document.createElement('tr');

            tr.innerHTML = `
                <td>
                    ${item.descricao}
                </td>

                <td>
                    ${item.obrigatorio ? 'Sim' : 'Não'}
                </td>

                <td>
                    <select
                        name="checklist[${item.id}][status]"
                        ${item.obrigatorio ? 'required' : ''}
                    >
                        <option value="">Selecione</option>
                        <option value="OK">OK</option>
                        <option value="DEFEITO">Defeito</option>
                        <option value="NAO_APLICA">
                            Não se aplica
                        </option>
                    </select>
                </td>

                <td>
                    <input
                        type="text"
                        name="checklist[${item.id}][observacao]"
                        placeholder="Observação"
                    >
                </td>
            `;

            checklistItens.appendChild(tr);
        });

        checklistTabela.style.display = 'table';
    })
    .catch(function () {
        checklistLoading.style.display = 'none';

        checklistSemModelo.textContent =
            'Erro ao carregar o checklist.';

        checklistSemModelo.style.display = 'block';
    });
</script>

    </div>

</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>