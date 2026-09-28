CREATE TABLE veiculos (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    numero VARCHAR(20) NOT NULL,

    placa VARCHAR(10),
    renavam VARCHAR(20),

    marca VARCHAR(100) NOT NULL,
    modelo VARCHAR(100) NOT NULL,

    ano_fabricacao SMALLINT,
    ano_modelo SMALLINT,

    cor VARCHAR(50),

    tipo_veiculo_id BIGINT NOT NULL,

    combustivel VARCHAR(30),

    indicador_tipo VARCHAR(20) NOT NULL,
    indicador_atual NUMERIC(12,2),

    observacoes TEXT,

    ativo BOOLEAN NOT NULL DEFAULT TRUE,

    criado_em TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    atualizado_em TIMESTAMPTZ NOT NULL DEFAULT NOW(),

    CONSTRAINT uq_veiculos_numero
        UNIQUE (numero),

    CONSTRAINT uq_veiculos_placa
        UNIQUE (placa),

    CONSTRAINT fk_veiculos_tipo
        FOREIGN KEY (tipo_veiculo_id)
        REFERENCES tipos_veiculos (id)
        ON DELETE RESTRICT,

    CONSTRAINT ck_veiculos_indicador_tipo
        CHECK (
            indicador_tipo IN (
                'KM',
                'HORIMETRO',
                'NAO_APLICAVEL'
            )
        ),

    CONSTRAINT ck_veiculos_indicador_atual
        CHECK (
            indicador_tipo = 'NAO_APLICAVEL'
            OR indicador_atual IS NOT NULL
        )
);