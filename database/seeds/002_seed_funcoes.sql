INSERT INTO funcoes (nome, descricao)
VALUES
    ('Motorista', 'Responsável pela condução e operação dos veículos.'),
    ('Fiscal de Frota', 'Responsável pelo controle operacional de entrada, saída e conferência da frota.'),
    ('Supervisor de Frota', 'Responsável pela supervisão e gestão da operação da frota.'),
    ('Supervisor de Oficina', 'Responsável pela supervisão das atividades da oficina e manutenção.')
ON CONFLICT (nome) DO NOTHING;