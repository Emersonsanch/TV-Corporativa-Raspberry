<?php
// Arquivo: controllers/AuthController.php
// Lógica de Autenticação (Login/Logout)

require_once 'config.php';
require_once 'database.php';

function login_get() {
    // Se já estiver logado, redireciona para o dashboard
    if (is_logged_in()) {
        redirect(ADMIN_PATH . '/dashboard');
    }
    view('auth/login');
}

function login_post() {
    // Se já estiver logado, redireciona para o dashboard
    if (is_logged_in()) {
        redirect(ADMIN_PATH . '/dashboard');
    }

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $error = '';

    // 1. Tenta autenticar com o usuário do banco de dados (users)
    $user = get_user_by_email($email);

    if ($user && password_verify($password, $user['password'])) {
        // Autenticação bem-sucedida
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        redirect(ADMIN_PATH . '/dashboard');
    } 
    
    // 2. Tenta autenticar com o usuário padrão (fallback)
    // Isso é um fallback de segurança, mas o ideal é usar o banco
    if ($email === ADMIN_EMAIL && password_verify($password, ADMIN_PASSWORD_HASH)) {
        // Autenticação bem-sucedida (usuário padrão)
    
        $_SESSION['user_id'] = 1; // ID 1 para o admin padrão
        $_SESSION['user_name'] = 'Administrador Padrão';
        redirect(ADMIN_PATH . '/dashboard');
    }

    // Falha na autenticação
    $error = 'Email ou senha inválidos.';
    view('auth/login', ['error' => $error, 'email' => $email]);
}

function logout() {
    session_destroy();
    redirect('/login');
}
