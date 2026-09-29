CREATE TABLE checklist_itens (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    checklist_modelo_id BIGINT NOT NULL,

    descricao VARCHAR(255) NOT NULL,
    ordem INTEGER NOT NULL DEFAULT 1,
    obrigatorio BOOLEAN NOT NULL DEFAULT TRUE,
    ativo BOOLEAN NOT NULL DEFAULT TRUE,

    criado_em TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    atualizado_em TIMESTAMPTZ NOT NULL DEFAULT NOW(),

    CONSTRAINT fk_checklist_itens_modelo
        FOREIGN KEY (checklist_modelo_id)
        REFERENCES checklist_modelos (id)
        ON DELETE RESTRICT,

    CONSTRAINT ck_checklist_itens_ordem
        CHECK (ordem > 0)
);