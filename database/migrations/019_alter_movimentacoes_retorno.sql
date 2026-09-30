ALTER TABLE movimentacoes_frota
DROP CONSTRAINT ck_movimentacoes_retorno;

ALTER TABLE movimentacoes_frota
ADD CONSTRAINT ck_movimentacoes_retorno
CHECK (
    (
        status = 'ABERTA'
        AND data_hora_retorno IS NULL
        AND indicador_retorno IS NULL
    )
    OR
    (
        status = 'ABERTA'
        AND data_hora_retorno IS NOT NULL
        AND indicador_retorno IS NOT NULL
    )
    OR
    (
        status = 'FINALIZADA'
        AND data_hora_retorno IS NOT NULL
        AND indicador_retorno IS NOT NULL
    )
    OR
    (
        status = 'CANCELADA'
    )
);