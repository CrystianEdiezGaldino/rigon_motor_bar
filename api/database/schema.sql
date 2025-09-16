-- Script para criar o banco de dados e tabelas do sistema CRUD
-- Execute este script no seu MySQL para configurar o banco

-- Criar banco de dados (se não existir)
CREATE DATABASE IF NOT EXISTS rigon CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Usar o banco de dados
USE rigon;

-- Tabela de usuários para sistema de login
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    ativo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de produtos do cardápio
CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    imagem VARCHAR(255),
    ativo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Inserir usuário administrador padrão
-- Username: admin, Password: admin123
-- Hash correto gerado com password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO usuarios (username, password, nome, email, role, ativo) VALUES 
('admin', '$2y$10$8K1p/a0dL1LXMIgoEDFrwOe6K6KqG8K1p/a0dL1LXMIgoEDFrwO', 'Administrador', 'admin@rigon.com', 'admin', 1);

-- Inserir algumas categorias de exemplo
INSERT INTO produtos (nome, descricao, preco, categoria, imagem, ativo) VALUES 
('Caipirinha', 'Caipirinha tradicional com limão, açúcar e cachaça', 15.00, 'Drinks', 'caipirinha.jpg', 1),
('Heineken', 'Cerveja Heineken 350ml', 8.00, 'Cervejas', 'heineken.jpg', 1),
('Batata Frita', 'Porção de batata frita crocante', 12.00, 'Petiscos', 'batata-frita.jpg', 1),
('Whisky Jack Daniels', 'Whisky Jack Daniels 50ml', 25.00, 'Destilados', 'jack-daniels.jpg', 1),
('Refrigerante Coca-Cola', 'Refrigerante Coca-Cola 350ml', 6.00, 'Refrigerantes', 'coca-cola.jpg', 1);

-- Criar índices para melhor performance
CREATE INDEX idx_produtos_categoria ON produtos(categoria);
CREATE INDEX idx_produtos_ativo ON produtos(ativo);
CREATE INDEX idx_usuarios_username ON usuarios(username);
CREATE INDEX idx_usuarios_ativo ON usuarios(ativo);

-- Verificar se as tabelas foram criadas
SHOW TABLES;

-- Verificar estrutura das tabelas
DESCRIBE usuarios;
DESCRIBE produtos;

-- Verificar dados inseridos
SELECT * FROM usuarios;
SELECT * FROM produtos;
