CREATE UNIQUE INDEX uq_movimentacoes_veiculo_aberta
    ON movimentacoes_frota (veiculo_id)
    WHERE status = 'ABERTA';

CREATE UNIQUE INDEX uq_movimentacoes_carreta_aberta
    ON movimentacoes_frota (carreta_id)
    WHERE status = 'ABERTA'
      AND carreta_id IS NOT NULL;