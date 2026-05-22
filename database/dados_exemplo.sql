-- Inserir categorias de exemplo
INSERT INTO categorias (nome) VALUES ('Roupas Masculinas');
INSERT INTO categorias (nome) VALUES ('Roupas Femininas');
INSERT INTO categorias (nome) VALUES ('Acessórios');

-- Inserir produtos de exemplo
INSERT INTO produtos (nome, descricao, preco, categoria_id, imagem, estoque) VALUES
('Terno Clássico Azul Marinho', 'Terno de lã premium em azul marinho clássico, com corte moderno e ajuste perfeito.', 1299.90, 1, 'https://via.placeholder.com/400x400?text=Terno+Clássico+Azul+Marinho', 5),
('Camisa Social Branca', 'Camisa de algodão egípcio com gola francesa e punhos duplos.', 299.90, 1, 'https://via.placeholder.com/400x400?text=Camisa+Social+Branca', 15),
('Calça Social Preta', 'Calça de vestir em lã cinza com acabamento impecável.', 499.90, 1, 'https://via.placeholder.com/400x400?text=Calça+Social+Preta', 8),
('Vestido de Seda Vermelho', 'Vestido longo de seda pura com decote em V e cintura marcada.', 899.90, 2, 'https://via.placeholder.com/400x400?text=Vestido+de+Seda+Vermelho', 3),
('Saia Lápis Preta', 'Saia lápis de lã com fenda posterior e fechamento em zíper invisível.', 399.90, 2, 'https://via.placeholder.com/400x400?text=Saia+Lápis+Preta', 12),
('Blusa de Renda Branca', 'Blusa delicada de renda com manga três quartos e detalhes em pérolas.', 249.90, 2, 'https://via.placeholder.com/400x400?text=Blusa+de+Renda+Branca', 7),
('Cinto de Couro Marrom', 'Cinto de couro legítimo com fivela em metal envelhecido.', 199.90, 3, 'https://via.placeholder.com/400x400?text=Cinto+de+Couro+Marrom', 20),
('Relógio de Pulso Clássico', 'Relógio de pulso com pulseira de couro e mostrador analógico.', 599.90, 3, 'https://via.placeholder.com/400x400?text=Relógio+de+Pulso+Clássico', 6),
('Lenço de Seda Estampado', 'Lenço de seda com estampa exclusiva Lupière.', 149.90, 3, 'https://via.placeholder.com/400x400?text=Lenço+de+Seda+Estampado', 25);