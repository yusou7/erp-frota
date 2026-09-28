CREATE TABLE perfil_permissoes (
    perfil_id BIGINT NOT NULL,
    permissao_id BIGINT NOT NULL,
    criado_em TIMESTAMPTZ NOT NULL DEFAULT NOW(),

    PRIMARY KEY (perfil_id, permissao_id),

    CONSTRAINT fk_perfil_permissoes_perfil
        FOREIGN KEY (perfil_id)
        REFERENCES perfis (id)
        ON DELETE CASCADE,

    CONSTRAINT fk_perfil_permissoes_permissao
        FOREIGN KEY (permissao_id)
        REFERENCES permissoes (id)
        ON DELETE CASCADE
);