<?php
// Arquivo: controllers/TvController.php
// Lógica do CRUD de TVs

require_once 'config.php';
require_once 'database.php';

function handle_tv_request($action, $id) {
    switch ($action) {
        case 'index':
            tv_index();
            break;
        case 'create':
            tv_create();
            break;
        case 'edit':
            tv_edit($id);
            break;
        case 'delete':
            tv_delete($id);
            break;
        default:
            http_response_code(404);
            echo "<h1>404 - TV Action Not Found</h1>";
            break;
    }
}

function tv_index() {
    $tvs = get_all_tvs();

    $data = [
        'page_title' => 'TVs (Displays)',
        'active_menu' => 'tvs',
        'tvs' => $tvs,
    ];
    
    $content_view = __DIR__ . '/../views/admin/tvs/index.php';
    require_once __DIR__ . '/../views/layout/admin.php';
}

function tv_create() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nome'] ?? '';
        $token = $_POST['token'] ?? '';
        $playlist_id = !empty($_POST['playlist_id']) ? intval($_POST['playlist_id']) : null;
        
        if (empty($nome) || empty($token)) {
            $_SESSION['message'] = 'Nome e token são obrigatórios.';
            $_SESSION['message_type'] = 'danger';
            redirect(ADMIN_PATH . '/tvs/create');
            return;
        }
        
        // Verificar se token já existe
        $existing = get_tv_by_token($token);
        if ($existing) {
            $_SESSION['message'] = 'Este token já está em uso. Gere um novo token.';
            $_SESSION['message_type'] = 'danger';
            redirect(ADMIN_PATH . '/tvs/create');
            return;
        }
        
        $sql = "INSERT INTO tvs (nome, token, playlist_id) VALUES (?, ?, ?)";
        $result = db_execute($sql, [$nome, $token, $playlist_id]);
        
        if ($result) {
            $_SESSION['message'] = 'TV criada com sucesso!';
            $_SESSION['message_type'] = 'success';
            redirect(ADMIN_PATH . '/tvs');
        } else {
            $_SESSION['message'] = 'Erro ao criar TV.';
            $_SESSION['message_type'] = 'danger';
            redirect(ADMIN_PATH . '/tvs/create');
        }
        return;
    }
    
    $playlists = get_all_playlists();
    
    $data = [
        'page_title' => 'Nova TV',
        'active_menu' => 'tvs',
        'playlists' => $playlists,
    ];
    
    $content_view = __DIR__ . '/../views/admin/tvs/create.php';
    require_once __DIR__ . '/../views/layout/admin.php';
}

function tv_edit($id) {
    $tv = get_tv($id);
    
    if (!$tv) {
        http_response_code(404);
        echo "<h1>404 - TV não encontrada</h1>";
        return;
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nome'] ?? '';
        $playlist_id = !empty($_POST['playlist_id']) ? intval($_POST['playlist_id']) : null;
        
        $sql = "UPDATE tvs SET nome = ?, playlist_id = ? WHERE id = ?";
        $result = db_execute($sql, [$nome, $playlist_id, $id]);
        
        $_SESSION['message'] = 'TV atualizada com sucesso!';
        $_SESSION['message_type'] = 'success';
        redirect(ADMIN_PATH . '/tvs');
        return;
    }
    
    $playlists = get_all_playlists();
    
    $data = [
        'page_title' => 'Editar TV',
        'active_menu' => 'tvs',
        'tv' => $tv,
        'playlists' => $playlists,
    ];
    
    $content_view = __DIR__ . '/../views/admin/tvs/edit.php';
    require_once __DIR__ . '/../views/layout/admin.php';
}

function tv_delete($id) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo "<h1>405 - Method Not Allowed</h1>";
        return;
    }
    
    $sql = "DELETE FROM tvs WHERE id = ?";
    $result = db_execute($sql, [$id]);
    
    $_SESSION['message'] = 'TV excluída com sucesso!';
    $_SESSION['message_type'] = 'success';
    redirect(ADMIN_PATH . '/tvs');
}
