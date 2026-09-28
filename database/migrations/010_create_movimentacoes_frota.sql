CREATE TABLE movimentacoes_frota (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    veiculo_id BIGINT NOT NULL,
    carreta_id BIGINT,
    motorista_id BIGINT NOT NULL,

    data_hora_saida TIMESTAMPTZ NOT NULL,
    indicador_saida NUMERIC(12,2) NOT NULL,

    destino VARCHAR(255),
    finalidade VARCHAR(255),

    data_hora_retorno TIMESTAMPTZ,
    indicador_retorno NUMERIC(12,2),

    observacoes_saida TEXT,
    observacoes_retorno TEXT,

    status VARCHAR(20) NOT NULL DEFAULT 'ABERTA',

    criado_em TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    atualizado_em TIMESTAMPTZ NOT NULL DEFAULT NOW(),

    CONSTRAINT fk_movimentacoes_veiculo
        FOREIGN KEY (veiculo_id)
        REFERENCES veiculos (id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_movimentacoes_carreta
        FOREIGN KEY (carreta_id)
        REFERENCES veiculos (id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_movimentacoes_motorista
        FOREIGN KEY (motorista_id)
        REFERENCES funcionarios (id)
        ON DELETE RESTRICT,

    CONSTRAINT ck_movimentacoes_status
        CHECK (
            status IN (
                'ABERTA',
                'FINALIZADA',
                'CANCELADA'
            )
        ),

    CONSTRAINT ck_movimentacoes_indicadores
        CHECK (
            indicador_saida >= 0
            AND (
                indicador_retorno IS NULL
                OR indicador_retorno >= indicador_saida
            )
        ),

    CONSTRAINT ck_movimentacoes_retorno
        CHECK (
            (
                status = 'ABERTA'
                AND data_hora_retorno IS NULL
                AND indicador_retorno IS NULL
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
        )
    );