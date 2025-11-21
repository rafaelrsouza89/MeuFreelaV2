CREATE DATABASE meufreela_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE meufreela_db;

-- Tabela de Usuários
CREATE TABLE usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    tipo_usuario ENUM('freelancer', 'contratante', 'ambos') NOT NULL,
    data_cadastro DATETIME NOT NULL,
    biografia TEXT NULL,
    especialidades VARCHAR(255) NULL,
    portfolio_url VARCHAR(255) NULL,
    reset_token_hash VARCHAR(255) NULL,
    reset_token_expires_at DATETIME NULL
);

-- Tabela de Vagas
CREATE TABLE vaga (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    descricao TEXT NOT NULL,
    tipo_vaga ENUM('remunerado', 'voluntario') NOT NULL,
    remuneracao DECIMAL(10, 2),
    local VARCHAR(255) NOT NULL,
    data_publicacao DATETIME NOT NULL,
    data_limite DATE NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id) ON DELETE CASCADE
);

-- Tabela de Candidaturas
CREATE TABLE candidatura (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_vaga INT NOT NULL,
    data_candidatura DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pendente','aceita','recusada') DEFAULT 'pendente',
    UNIQUE KEY (id_usuario, id_vaga)
);