<?php
// Arquivo: database.php
// Conexão PDO e funções de manipulação de dados

require_once 'config.php';

// Variável global para a conexão PDO
$pdo = null;

/**
 * Estabelece a conexão com o banco de dados usando PDO.
 * @return PDO
 */
function get_pdo() {
    global $pdo;
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (\PDOException $e) {
            die("Erro de Conexão com o Banco de Dados: " . $e->getMessage());
        }
    }
    return $pdo;
}

/**
 * Executa uma query de seleção.
 * @param string $sql
 * @param array $params
 * @return array
 */
function db_select($sql, $params = []) {
    $pdo = get_pdo();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * Executa uma query de inserção, atualização ou exclusão.
 * @param string $sql
 * @param array $params
 * @return int|bool Retorna o ID da última inserção ou true/false para update/delete.
 */
function db_execute($sql, $params = []) {
    $pdo = get_pdo();
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute($params);
    
    if (strpos(strtolower($sql), 'insert') === 0) {
        return $pdo->lastInsertId();
    }
    
    return $result;
}

// --- Funções CRUD Específicas (Simulando Models) ---

// Conteudo
function get_conteudo($id) {
    $sql = "SELECT * FROM conteudos WHERE id = ?";
    $result = db_select($sql, [$id]);
    return $result ? $result[0] : null;
}

function get_all_conteudos() {
    $sql = "SELECT * FROM conteudos ORDER BY created_at DESC";
    return db_select($sql);
}

// Playlist
function get_playlist($id) {
    $sql = "SELECT * FROM playlists WHERE id = ?";
    $result = db_select($sql, [$id]);
    return $result ? $result[0] : null;
}

function get_all_playlists() {
    $sql = "SELECT * FROM playlists ORDER BY nome ASC";
    return db_select($sql);
}

// PlaylistItem (com join para Conteudo)
function get_playlist_items($playlist_id) {
    $sql = "
        SELECT 
            pi.*, 
            c.nome, c.tipo, c.arquivo_url, c.texto, c.duracao as conteudo_duracao
        FROM playlist_itens pi
        JOIN conteudos c ON pi.conteudo_id = c.id
        WHERE pi.playlist_id = ? AND c.ativo = 1
        ORDER BY pi.ordem ASC
    ";
    return db_select($sql, [$playlist_id]);
}

// Tv
function get_tv($id) {
    $sql = "SELECT * FROM tvs WHERE id = ?";
    $result = db_select($sql, [$id]);
    return $result ? $result[0] : null;
}

function get_tv_by_token($token) {
    $sql = "SELECT * FROM tvs WHERE token = ?";
    $result = db_select($sql, [$token]);
    return $result ? $result[0] : null;
}

function get_all_tvs() {
    $sql = "SELECT t.*, p.nome as playlist_nome FROM tvs t LEFT JOIN playlists p ON t.playlist_id = p.id ORDER BY t.nome ASC";
    return db_select($sql);
}

function update_tv_ping($id) {
    $sql = "UPDATE tvs SET ultimo_ping = NOW() WHERE id = ?";
    return db_execute($sql, [$id]);
}

// User (para autenticação)
function get_user_by_email($email) {
    $sql = "SELECT * FROM users WHERE email = ?";
    $result = db_select($sql, [$email]);
    return $result ? $result[0] : null;
}

// Funções de Inserção/Atualização serão criadas conforme a necessidade no Controller
