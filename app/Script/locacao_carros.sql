DROP DATABASE IF EXISTS locacao_carros;
CREATE DATABASE locacao_carros;
USE locacao_carros;


CREATE TABLE tipo_bens (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE marca (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tipo_bem_id BIGINT UNSIGNED NOT NULL,
    nome VARCHAR(100) NOT NULL,
    observacao TEXT,
    FOREIGN KEY (tipo_bem_id) REFERENCES tipo_bens(id) ON DELETE CASCADE
);

CREATE TABLE bens_locaveis (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    marca_id BIGINT UNSIGNED NOT NULL,
    modelo VARCHAR(100),
    registo_unico_publico VARCHAR(20),
    cor VARCHAR(20),
    numero_passageiros INT,
    combustivel ENUM('gasolina', 'diesel', 'elétrico', 'híbrido', 'outro') NOT NULL,
    numero_portas INT,
    transmissao ENUM('manual', 'automática'),
    ano INT,
    manutencao BOOLEAN DEFAULT TRUE,
    preco_diario DECIMAL(10,2),
    observacao VARCHAR(200),
    FOREIGN KEY (marca_id) REFERENCES marca(id) ON DELETE CASCADE
);

CREATE TABLE localizacoes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    bem_locavel_id BIGINT UNSIGNED,
    cidade VARCHAR(100) NOT NULL,
    filial VARCHAR(100),
    posicao VARCHAR(100) NOT NULL,
    FOREIGN KEY (bem_locavel_id) REFERENCES bens_locaveis(id) ON DELETE CASCADE,
    UNIQUE (filial, posicao)
);

CREATE TABLE caracteristicas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE bem_caracteristicas (
    bem_locavel_id BIGINT UNSIGNED,
    caracteristica_id BIGINT UNSIGNED,
    PRIMARY KEY (bem_locavel_id, caracteristica_id),
    FOREIGN KEY (bem_locavel_id) REFERENCES bens_locaveis(id) ON DELETE CASCADE,
    FOREIGN KEY (caracteristica_id) REFERENCES caracteristicas(id) ON DELETE CASCADE
);

-- Tabela reservas 
-- CREATE TABLE reservas (
--     id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
--     user_id BIGINT UNSIGNED NOT NULL,
--     bem_locavel_id BIGINT UNSIGNED NOT NULL,
--     data_inicio DATE NOT NULL,
--     data_fim DATE NOT NULL,
--     preco_total DECIMAL(10,2) NOT NULL,
--     status ENUM('pendente', 'confirmada', 'ativa', 'finalizada', 'cancelada') DEFAULT 'pendente',
--     observacoes TEXT,
--     FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
--     FOREIGN KEY (bem_locavel_id) REFERENCES bens_locaveis(id) ON DELETE CASCADE
-- );

CREATE TABLE pagamentos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reserva_id BIGINT UNSIGNED NOT NULL,
    metodo_pagamento ENUM('cartao_credito', 'mbway', 'paypal', 'transferencia', 'dinheiro') NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    status ENUM('pendente', 'pago', 'falhado', 'reembolsado') DEFAULT 'pendente',
    transaction_id VARCHAR(100),
    data_pagamento DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (reserva_id) REFERENCES reservas(id) ON DELETE CASCADE
);