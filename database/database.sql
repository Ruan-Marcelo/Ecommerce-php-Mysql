-- Banco de dados: `lupiere`
-- Estrutura das tabelas

-- Tabela de usuários
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `admin` tinyint(1) DEFAULT 0,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Identidades externas usadas pelo login social
CREATE TABLE IF NOT EXISTS `oauth_identidades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `provedor` varchar(20) NOT NULL,
  `provedor_usuario_id` varchar(255) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `oauth_provedor_usuario` (`provedor`, `provedor_usuario_id`),
  KEY `oauth_usuario_id` (`usuario_id`),
  CONSTRAINT `oauth_identidades_usuario_fk` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de categorias
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `descricao` text,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de produtos
CREATE TABLE IF NOT EXISTS `produtos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) NOT NULL,
  `descricao` text,
  `preco` decimal(10,2) NOT NULL,
  `estoque` int(11) NOT NULL DEFAULT 0,
  `categoria_id` int(11) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `categoria_id` (`categoria_id`),
  CONSTRAINT `produtos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de pedidos
CREATE TABLE IF NOT EXISTS `pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `endereco_entrega` text NOT NULL,
  `status` enum('pendente','processando','enviado','entregue','cancelado') DEFAULT 'pendente',
  `forma_pagamento` varchar(30) DEFAULT 'pix',
  `status_pagamento` varchar(30) DEFAULT 'aguardando_pagamento',
  `pagamento_id` varchar(80) DEFAULT NULL,
  `pix_copia_cola` text DEFAULT NULL,
  `pix_qr_code` text DEFAULT NULL,
  `checkout_url` text DEFAULT NULL,
  `gateway` varchar(40) DEFAULT 'interno',
  `data_pagamento` timestamp NULL DEFAULT NULL,
  `data_pedido` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de itens_pedido
CREATE TABLE IF NOT EXISTS `itens_pedido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pedido_id` (`pedido_id`),
  KEY `produto_id` (`produto_id`),
  CONSTRAINT `itens_pedido_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `itens_pedido_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de banners da página inicial
CREATE TABLE IF NOT EXISTS `banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(180) NOT NULL,
  `subtitulo` text,
  `imagem` varchar(255) NOT NULL,
  `link_url` varchar(255) DEFAULT 'produtos.php',
  `texto_botao` varchar(80) DEFAULT 'Explorar coleção',
  `ativo` tinyint(1) DEFAULT 1,
  `ordem` int(11) DEFAULT 0,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `comentarios_produto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `produto_id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `nome` varchar(100) NOT NULL,
  `comentario` text NOT NULL,
  `aprovado` tinyint(1) DEFAULT 1,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `produto_id` (`produto_id`),
  KEY `usuario_id` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `avaliacoes_produto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `produto_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `nota` tinyint(1) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `produto_usuario` (`produto_id`,`usuario_id`),
  KEY `produto_id` (`produto_id`),
  KEY `usuario_id` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `lista_desejos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario_produto` (`usuario_id`,`produto_id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `produto_id` (`produto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `email_inscritos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `nome` varchar(120) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `origem` varchar(40) DEFAULT 'manual',
  `ativo` tinyint(1) DEFAULT 1,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `usuario_id` (`usuario_id`),
  KEY `ativo` (`ativo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `email_campanhas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(180) NOT NULL,
  `assunto` varchar(180) NOT NULL,
  `tipo` varchar(40) DEFAULT 'promocao',
  `publico` varchar(40) DEFAULT 'inscritos',
  `conteudo_html` mediumtext NOT NULL,
  `status` varchar(30) DEFAULT 'rascunho',
  `criador_id` int(11) DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_envio` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tipo` (`tipo`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `email_fila` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campanha_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `nome` varchar(120) DEFAULT NULL,
  `assunto` varchar(180) NOT NULL,
  `conteudo_html` mediumtext NOT NULL,
  `status` varchar(30) DEFAULT 'pendente',
  `tentativas` int(11) DEFAULT 0,
  `erro` text DEFAULT NULL,
  `agendado_para` timestamp NOT NULL DEFAULT current_timestamp(),
  `enviado_em` timestamp NULL DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `status_agendado` (`status`,`agendado_para`),
  KEY `campanha_id` (`campanha_id`),
  KEY `usuario_id` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `email_automacoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(120) NOT NULL,
  `tipo` varchar(40) NOT NULL,
  `assunto` varchar(180) NOT NULL,
  `conteudo_html` mediumtext NOT NULL,
  `intervalo_minutos` int(11) NOT NULL DEFAULT 1440,
  `ativo` tinyint(1) DEFAULT 1,
  `ultima_execucao` timestamp NULL DEFAULT NULL,
  `proxima_execucao` timestamp NULL DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `ativo_proxima` (`ativo`,`proxima_execucao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `carrinhos_abandonados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `itens_json` mediumtext NOT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT 0,
  `ativo` tinyint(1) DEFAULT 1,
  `ultimo_email_em` timestamp NULL DEFAULT NULL,
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario_id` (`usuario_id`),
  KEY `ativo_atualizacao` (`ativo`,`data_atualizacao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `email_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `host` varchar(180) DEFAULT NULL,
  `porta` int(11) DEFAULT 587,
  `criptografia` varchar(20) DEFAULT 'tls',
  `usuario` varchar(180) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `remetente_email` varchar(180) DEFAULT NULL,
  `remetente_nome` varchar(120) DEFAULT 'LUPIERE',
  `ativo` tinyint(1) DEFAULT 0,
  `data_atualizacao` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserir algumas categorias de exemplo
INSERT INTO `categorias` (`nome`, `descricao`) VALUES
('Roupas', 'Peças de vestuário'),
('Acessórios', 'Complementos para o visual'),
('Calçados', 'Sapatos e tênis');

-- Inserir um usuário admin de exemplo.
-- Para criar/atualizar o admin padrão com senha hasheada, execute:
-- php database/setup_admin.php
-- Email padrão: admin@lupiere.com
-- Senha padrão: Admin@123
INSERT INTO `usuarios` (`nome`, `email`, `senha`, `admin`) VALUES
('Administrador Lupiere', 'admin@lupiere.com', '$2y$10$Si0N0uK.nRI8tHHIFweB.OdLSEVdEfG4OEYX7FS0GkuTpeeV.olj2', 1);
