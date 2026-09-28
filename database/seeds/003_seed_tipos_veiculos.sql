INSERT INTO tipos_veiculos (nome, descricao)
VALUES
    ('Caminhão', 'Veículo destinado ao transporte de cargas.'),
    ('Carro', 'Veículo de passeio ou serviço.'),
    ('Van', 'Veículo utilitário para transporte de pessoas ou cargas.'),
    ('Cavalo mecânico', 'Veículo destinado a tracionar semirreboques e carretas.'),
    ('Carreta', 'Semirreboque destinado ao transporte de cargas.'),
    ('Empilhadeira', 'Equipamento utilizado para movimentação interna de cargas.')
ON CONFLICT (nome) DO NOTHING;