CREATE DATABASE IF NOT EXISTS gestao_estoque_mercado;
USE gestao_estoque_mercado;

CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL,
    quantidade_estoque INT NOT NULL DEFAULT 0,
    data_validade DATE NOT NULL
);

INSERT INTO produtos (nome, categoria, descricao, preco, quantidade_estoque, data_validade) VALUES
('Arroz 5kg', 'Grãos', 'Arroz branco tipo 1', 25.90, 40, '2027-03-10'),
('Leite Integral 1L', 'Laticínios', 'Leite integral UHT', 5.49, 60, '2026-12-01'),
('Detergente 500ml', 'Limpeza', 'Detergente neutro', 2.99, 80, '2028-01-15');