DROP DATABASE IF EXISTS campofacilv2;
CREATE DATABASE campofacilv2 CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE campofacilv2;


CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vendedor_id INT NOT NULL,
    nome_produto VARCHAR(255) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    ciclo_consumo INT NOT NULL,
    FOREIGN KEY (vendedor_id) REFERENCES usuarios(id)
);

CREATE TABLE vendedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    senha VARCHAR(255)
);

CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vendedor_id INT NOT NULL,
    nome_fazenda VARCHAR(150) NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    uf VARCHAR(2) NOT NULL,
    cpf_cnpj VARCHAR(20) NOT NULL,
    email VARCHAR(150),
    telefone VARCHAR(20) NOT NULL,

    tipo_animal ENUM('Gado','Aves','Suinos','Cavalos','Outros') NOT NULL DEFAULT 'Gado',

    tipo_gado ENUM('Corte','Leite') DEFAULT NULL,

    quantidade_animais INT NOT NULL,
    forma_pagamento VARCHAR(100),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_cliente_vendedor
        FOREIGN KEY (vendedor_id) REFERENCES vendedores(id)
        ON DELETE CASCADE
);

CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vendedor_id INT NOT NULL,
    nome VARCHAR(150) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    dias_consumo INT NOT NULL,
    estoque INT NOT NULL DEFAULT 0,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_produto_vendedor
        FOREIGN KEY (vendedor_id) REFERENCES vendedores(id)
        ON DELETE CASCADE
);

CREATE TABLE vendas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vendedor_id INT NOT NULL,
    cliente_id INT NOT NULL,
    produto_id INT NOT NULL,
    quantidade INT NOT NULL,
    data_venda DATE NOT NULL,
    data_alerta DATE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_venda_vendedor
        FOREIGN KEY (vendedor_id) REFERENCES vendedores(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_venda_cliente
        FOREIGN KEY (cliente_id) REFERENCES clientes(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_venda_produto
        FOREIGN KEY (produto_id) REFERENCES produtos(id)
        ON DELETE CASCADE
);

INSERT INTO vendedores (nome, email, senha)
VALUES (
    'Administrador',
    'admin@campo.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9rLZ9J8s6F9hZl8C5Q8x5G'
);