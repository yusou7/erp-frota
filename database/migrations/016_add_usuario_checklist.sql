ALTER TABLE checklists
ADD COLUMN usuario_id BIGINT;

ALTER TABLE checklists
ADD CONSTRAINT fk_checklists_usuario
    FOREIGN KEY (usuario_id)
    REFERENCES usuarios (id)
    ON DELETE RESTRICT;