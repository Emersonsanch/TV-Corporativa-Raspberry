<?php
// Arquivo: config.php
// Configurações globais do sistema

// --- Configurações do Banco de Dados ---
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'digital_signage'); // Usaremos o mesmo nome de banco do Laravel
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// --- Configurações da Aplicação ---
define('APP_NAME', 'TV Corporativa INGAFLEX');
define('APP_URL', 'http://localhost');
define('ADMIN_PATH', '/admin');
define('API_PATH', '/api');
define('TV_PATH', '/tv');

// --- Configurações de Segurança ---
// Chave para hashing de senhas e tokens (simulando APP_KEY do Laravel)
define('SECRET_KEY', 'UmaChaveSecretaParaHashingETokens');

// --- Configurações de Upload ---
define('UPLOAD_DIR', __DIR__ . '/public/uploads/');
define('UPLOAD_URL', '/public/uploads/');

// --- Configurações de Autenticação ---
define('ADMIN_EMAIL', 'admin@portal.com');
define('ADMIN_PASSWORD_HASH', '$2y$10$xabPUukxkNAbIvmT.wqR3.v6TpYHCZP/iUBegixeU.XJYZSAT6upi'); // Senha: admin123

// --- Inicialização de Sessão ---
session_start();

// --- Funções de Ajuda ---
function redirect($url) {
    header("Location: " . $url);
    exit();
}

function dd($data) {
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
    die();
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function get_user_id() {
    return $_SESSION['user_id'] ?? null;
}

function get_user_name() {
    return $_SESSION['user_name'] ?? 'Admin';
}

function get_upload_url($filename) {
    return APP_URL . UPLOAD_URL . $filename;
}

function get_upload_path($filename) {
    return UPLOAD_DIR . $filename;
}
