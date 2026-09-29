CREATE TABLE checklist_respostas (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    checklist_id BIGINT NOT NULL,
    checklist_item_id BIGINT NOT NULL,

    status VARCHAR(20) NOT NULL,

    observacao TEXT,

    respondido_em TIMESTAMPTZ NOT NULL DEFAULT NOW(),

    CONSTRAINT uq_checklist_respostas_item
        UNIQUE (checklist_id, checklist_item_id),

    CONSTRAINT fk_checklist_respostas_checklist
        FOREIGN KEY (checklist_id)
        REFERENCES checklists (id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_checklist_respostas_item
        FOREIGN KEY (checklist_item_id)
        REFERENCES checklist_itens (id)
        ON DELETE RESTRICT,

    CONSTRAINT ck_checklist_respostas_status
        CHECK (
            status IN (
                'OK',
                'DEFEITO',
                'NAO_APLICA'
            )
        )
);