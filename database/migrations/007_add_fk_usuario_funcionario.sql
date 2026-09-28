ALTER TABLE usuarios
    ADD CONSTRAINT fk_usuarios_funcionario
        FOREIGN KEY (funcionario_id)
        REFERENCES funcionarios (id)
        ON DELETE RESTRICT;