CREATE TABLE checklists (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    movimentacao_id BIGINT NOT NULL,
    checklist_modelo_id BIGINT NOT NULL,

    status VARCHAR(20) NOT NULL DEFAULT 'EM_ANDAMENTO',

    iniciado_em TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    finalizado_em TIMESTAMPTZ,

    criado_em TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    atualizado_em TIMESTAMPTZ NOT NULL DEFAULT NOW(),

    CONSTRAINT uq_checklists_movimentacao
        UNIQUE (movimentacao_id),

    CONSTRAINT fk_checklists_movimentacao
        FOREIGN KEY (movimentacao_id)
        REFERENCES movimentacoes_frota (id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_checklists_modelo
        FOREIGN KEY (checklist_modelo_id)
        REFERENCES checklist_modelos (id)
        ON DELETE RESTRICT,

    CONSTRAINT ck_checklists_status
        CHECK (
            status IN (
                'EM_ANDAMENTO',
                'CONCLUIDO',
                'CANCELADO'
            )
        )
);