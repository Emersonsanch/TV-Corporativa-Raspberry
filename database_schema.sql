-- Estrutura da Tabela users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Estrutura da Tabela conteudos
CREATE TABLE IF NOT EXISTS conteudos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    tipo ENUM('imagem', 'video', 'texto', 'link') NOT NULL,
    arquivo_url VARCHAR(500) NULL,
    texto TEXT NULL,
    duracao INT NOT NULL DEFAULT 10, -- Duração em segundos
    ativo BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Estrutura da Tabela playlists
CREATE TABLE IF NOT EXISTS playlists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT NULL,
    ativo BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Estrutura da Tabela playlist_itens
CREATE TABLE IF NOT EXISTS playlist_itens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    playlist_id INT NOT NULL,
    conteudo_id INT NOT NULL,
    ordem INT NOT NULL,
    duracao INT NULL, -- Duração específica para este item na playlist (sobrescreve a duração do conteúdo)
    FOREIGN KEY (playlist_id) REFERENCES playlists(id) ON DELETE CASCADE,
    FOREIGN KEY (conteudo_id) REFERENCES conteudos(id) ON DELETE CASCADE,
    UNIQUE KEY unique_playlist_item (playlist_id, conteudo_id)
);

-- Estrutura da Tabela tvs
CREATE TABLE IF NOT EXISTS tvs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    token VARCHAR(64) NOT NULL UNIQUE,
    playlist_id INT NULL,
    ultimo_ping TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (playlist_id) REFERENCES playlists(id) ON DELETE SET NULL
);

-- Dados iniciais para a tabela users (Usuário Padrão: admin@ingaflex.com / admin123)
INSERT INTO users (name, email, password) VALUES 
('Administrador', 'admin@ingaflex.com', '$2y$10$xabPUukxkNAbIvmT.wqR3.v6TpYHCZP/iUBegixeU.XJYZSAT6upi') 
ON DUPLICATE KEY UPDATE email=email;

-- Dados iniciais para a tabela playlists
INSERT INTO playlists (nome, descricao) VALUES 
('Playlist Padrão', 'Conteúdos básicos para demonstração.')
ON DUPLICATE KEY UPDATE nome=nome;

-- Dados iniciais para a tabela conteudos
INSERT INTO conteudos (nome, tipo, texto, duracao) VALUES 
('Mensagem de Boas-Vindas', 'texto', 'Bem-vindo à sua nova TV Corporativa em PHP Puro!', 15),
('Aviso Importante', 'texto', 'Lembre-se de configurar suas playlists e TVs.', 10)
ON DUPLICATE KEY UPDATE nome=nome;

-- Dados iniciais para a tabela playlist_itens
INSERT INTO playlist_itens (playlist_id, conteudo_id, ordem) VALUES 
(1, 1, 1),
(1, 2, 2)
ON DUPLICATE KEY UPDATE ordem=ordem;

-- Dados iniciais para a tabela tvs
INSERT INTO tvs (nome, token, playlist_id) VALUES 
('TV Sala de Espera', 'tv-token-12345', 1)
ON DUPLICATE KEY UPDATE nome=nome;
