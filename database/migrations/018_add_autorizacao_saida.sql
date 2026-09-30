ALTER TABLE movimentacoes_frota
ADD COLUMN status_autorizacao_saida VARCHAR(20)
    NOT NULL DEFAULT 'PENDENTE';

ALTER TABLE movimentacoes_frota
ADD CONSTRAINT ck_movimentacoes_status_autorizacao_saida
CHECK (
    status_autorizacao_saida IN (
        'PENDENTE',
        'AUTORIZADA',
        'RECUSADA'
    )
);