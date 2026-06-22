DROP DATABASE IF EXISTS campofacil;

CREATE DATABASE campofacil
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE campofacil;

CREATE TABLE vendedores (

    id INT AUTO_INCREMENT PRIMARY KEY,

    nome VARCHAR(150) NOT NULL,

    email VARCHAR(150) UNIQUE NOT NULL,

    senha VARCHAR(255) NOT NULL,

    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP

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

    tipo_animal ENUM(
        'Gado',
        'Aves',
        'Suinos',
        'Cavalos',
        'Outros'
    ) NOT NULL DEFAULT 'Gado',

    tipo_gado ENUM(
        'Corte',
        'Leite'
    ) DEFAULT NULL,

    quantidade_animais INT NOT NULL DEFAULT 1,

    forma_pagamento VARCHAR(100),

    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_cliente_vendedor
    FOREIGN KEY (vendedor_id)
    REFERENCES vendedores(id)
    ON DELETE CASCADE

);

CREATE TABLE produtos (

    id INT AUTO_INCREMENT PRIMARY KEY,

    vendedor_id INT NOT NULL,

    nome VARCHAR(150) NOT NULL,

    valor DECIMAL(10,2) NOT NULL,

    dias_consumo INT NOT NULL,

    estoque INT NOT NULL DEFAULT 0,

    consumo_medio DECIMAL(10,2) NOT NULL DEFAULT 1,

    estoque_minimo INT NOT NULL DEFAULT 5,

    prazo_seguranca INT NOT NULL DEFAULT 7,

    ativo TINYINT(1) NOT NULL DEFAULT 1,

    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_produto_vendedor
    FOREIGN KEY (vendedor_id)
    REFERENCES vendedores(id)
    ON DELETE CASCADE

);

CREATE TABLE vendas (

    id INT AUTO_INCREMENT PRIMARY KEY,

    vendedor_id INT NOT NULL,

    cliente_id INT NOT NULL,

    produto_id INT NOT NULL,

    quantidade INT NOT NULL,

    valor_unitario DECIMAL(10,2) NOT NULL DEFAULT 0,

    valor_total DECIMAL(10,2) NOT NULL DEFAULT 0,

    data_venda DATE NOT NULL,

    data_alerta DATE,

    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_venda_vendedor
    FOREIGN KEY (vendedor_id)
    REFERENCES vendedores(id)
    ON DELETE CASCADE,

    CONSTRAINT fk_venda_cliente
    FOREIGN KEY (cliente_id)
    REFERENCES clientes(id)
    ON DELETE CASCADE,

    CONSTRAINT fk_venda_produto
    FOREIGN KEY (produto_id)
    REFERENCES produtos(id)
    ON DELETE CASCADE

);

INSERT INTO vendedores (
    id,
    nome,
    email,
    senha
)

VALUES (

    2,

    'jorge',

    'jorgehenriqueavitorino@hotmail.com',

    '$2y$10$HukyAfDf91GfF/EBaORGL.Lcns58wPJUOfycUR/NYgo2M2W8K3l9W'

);