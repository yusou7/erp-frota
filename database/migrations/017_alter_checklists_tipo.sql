ALTER TABLE checklists
DROP CONSTRAINT uq_checklists_movimentacao;

ALTER TABLE checklists
ADD COLUMN tipo VARCHAR(10) NOT NULL DEFAULT 'SAIDA';

ALTER TABLE checklists
ADD CONSTRAINT ck_checklists_tipo
CHECK (tipo IN ('SAIDA', 'RETORNO'));

CREATE UNIQUE INDEX uq_checklists_movimentacao_tipo
ON checklists (movimentacao_id, tipo);