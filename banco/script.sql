CREATE DATABASE IF NOT EXISTS RGames CHARACTER SET utf8mb4;
USE RGames;

CREATE TABLE usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    login      VARCHAR(50)  NOT NULL UNIQUE,
    senha      VARCHAR(255) NOT NULL,          -- guarda o HASH, nunca a senha pura
    nome       VARCHAR(100) NOT NULL,
    ativo      CHAR(1) NOT NULL DEFAULT 'S'
);

CREATE TABLE plataforma (
    id_plataforma INT AUTO_INCREMENT PRIMARY KEY,
    nome          VARCHAR(60) NOT NULL,
    ativo         CHAR(1) NOT NULL DEFAULT 'S'
);

CREATE TABLE tipo_jogo (
    id_tipo_jogo INT AUTO_INCREMENT PRIMARY KEY,
    nome         VARCHAR(60) NOT NULL,
    ativo        CHAR(1) NOT NULL DEFAULT 'S'
);

CREATE TABLE jogo (
    id_jogo        INT AUTO_INCREMENT PRIMARY KEY,
    titulo         VARCHAR(120) NOT NULL,
    id_plataforma  INT NOT NULL,
    id_tipo_jogo   INT NOT NULL,
    tempo_jogado   DECIMAL(7,1) NOT NULL DEFAULT 0,   -- em horas (ex.: 12.5)
    valor_original DECIMAL(10,2) NOT NULL DEFAULT 0,  -- preço "cheio"
    valor_pago     DECIMAL(10,2) NOT NULL DEFAULT 0,  -- quanto realmente custou (promoção)
    dt_compra      DATE NULL,
    completou      CHAR(1) NOT NULL DEFAULT 'N',      -- S = zerou o jogo
    nota           TINYINT NULL,                      -- 1 a 5 (opcional)
    observacao     TEXT,
    ativo          CHAR(1) NOT NULL DEFAULT 'S',
    FOREIGN KEY (id_plataforma) REFERENCES plataforma(id_plataforma),
    FOREIGN KEY (id_tipo_jogo)  REFERENCES tipo_jogo(id_tipo_jogo)
);

-- Usuário inicial: login = admin | senha = password
INSERT INTO usuario (login, senha, nome) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador');

INSERT INTO plataforma (nome) VALUES
('PC'), ('PlayStation 5'), ('Xbox Series'), ('Nintendo Switch'), ('Mobile');

INSERT INTO tipo_jogo (nome) VALUES
('RPG'), ('Ação'), ('Aventura'), ('Estratégia'), ('Esportes'),
('Corrida'), ('Puzzle'), ('Plataforma'), ('Terror'), ('Simulação');

-- Alguns jogos de exemplo (pode apagar)
INSERT INTO jogo (titulo, id_plataforma, id_tipo_jogo, tempo_jogado, valor_original, valor_pago, dt_compra, completou, nota, observacao) VALUES
('The Witcher 3', 1, 1, 120.5, 129.99, 29.99, '2025-06-20', 'S', 5, 'Comprei na promoção de verão.'),
('Celeste',        4, 8,  9.0,  36.99, 18.50, '2025-09-10', 'S', 5, NULL),
('Hades',          1, 2, 35.0,  73.99, 36.99, '2026-01-05', 'N', 4, 'Falta a última ascensão.'),
('Stardew Valley', 5, 10, 60.0, 24.99, 24.99, '2026-03-02', 'N', NULL, NULL);
