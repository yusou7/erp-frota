CREATE TABLE checklist_modelos (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    nome VARCHAR(100) NOT NULL,
    descricao VARCHAR(255),

    tipo_veiculo_id BIGINT NOT NULL,

    ativo BOOLEAN NOT NULL DEFAULT TRUE,

    criado_em TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    atualizado_em TIMESTAMPTZ NOT NULL DEFAULT NOW(),

    CONSTRAINT uq_checklist_modelos_nome
        UNIQUE (nome),

    CONSTRAINT fk_checklist_modelos_tipo_veiculo
        FOREIGN KEY (tipo_veiculo_id)
        REFERENCES tipos_veiculos (id)
        ON DELETE RESTRICT
);