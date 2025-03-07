CREATE DATABASE IF NOT EXISTS restaurante_universitario;
USE restaurante_universitario;

-- Tabela de usuários (estudantes, funcionários, etc.)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matricula VARCHAR(100) UNIQUE NOT NULL,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('estudante', 'funcionario', 'admin') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de tickets de refeição
CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    codigo VARCHAR(50) UNIQUE NOT NULL,
    usado BOOLEAN DEFAULT FALSE,
    data_validade DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabela de transações de compra de tickets
CREATE TABLE transacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    tipo ENUM('compra', 'recarga') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Inserindo dados na tabela de usuários
INSERT INTO usuarios (matricula, nome, email, senha, tipo, ) VALUES
('2021SI001', 'João Silva', 'joao@email.com', MD5('senha123'), 'estudante'),
('2021SI002', 'Maria Oliveira', 'maria@email.com', MD5('senha456'), 'estudante'),
('admin001', 'Admin', 'admin@email.com', MD5('admin123'), 'admin');
('2021SI003', 'Gustavo', 'gustavo@email.com', MD5('senha678'), 'estudante'),

-- Inserindo tickets para usuários
INSERT INTO tickets (usuario_id, codigo, data_validade) VALUES
(1, 'TICKET12345', '2025-03-22'),
(2, 'TICKET67890', '2025-03-22'),
(4, 'TICKET46372', '2025-03-23');

-- Inserindo transações de compra de tickets
INSERT INTO transacoes (usuario_id, valor, tipo) VALUES
(1, 3, 'compra'),
(2, 3, 'compra'),
(4, 3, 'compra');
