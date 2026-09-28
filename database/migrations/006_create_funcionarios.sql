CREATE TABLE funcionarios (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    nome VARCHAR(150) NOT NULL,
    cpf VARCHAR(14) NOT NULL,
    matricula VARCHAR(50) NOT NULL,

    telefone VARCHAR(20),
    email VARCHAR(150),

    funcao_id BIGINT NOT NULL,
    ativo BOOLEAN NOT NULL DEFAULT TRUE,

    criado_em TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    atualizado_em TIMESTAMPTZ NOT NULL DEFAULT NOW(),

    CONSTRAINT uq_funcionarios_cpf UNIQUE (cpf),

    CONSTRAINT uq_funcionarios_matricula UNIQUE (matricula),

    CONSTRAINT fk_funcionarios_funcao
        FOREIGN KEY (funcao_id)
        REFERENCES funcoes (id)
        ON DELETE RESTRICT
);