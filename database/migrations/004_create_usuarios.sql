CREATE TABLE usuarios (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    auth_user_id UUID NOT NULL,
    funcionario_id BIGINT,
    perfil_id BIGINT NOT NULL,

    ativo BOOLEAN NOT NULL DEFAULT TRUE,

    criado_em TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    atualizado_em TIMESTAMPTZ NOT NULL DEFAULT NOW(),

    CONSTRAINT uq_usuarios_auth_user_id
        UNIQUE (auth_user_id),

    CONSTRAINT fk_usuarios_perfil
        FOREIGN KEY (perfil_id)
        REFERENCES perfis (id)
        ON DELETE RESTRICT
);