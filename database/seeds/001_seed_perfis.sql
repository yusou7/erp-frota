INSERT INTO perfis (nome, descricao)
VALUES
    ('Administrador', 'Acesso administrativo ao sistema.'),
    ('Gestor', 'Acesso às funcionalidades de gestão da frota e oficina.'),
    ('Operacional', 'Acesso às funcionalidades operacionais da frota.'),
    ('Consulta', 'Acesso somente para consulta de informações.')
ON CONFLICT (nome) DO NOTHING;