<?php
// Arquivo: controllers/PlaylistController.php
// Lógica do CRUD de Playlists

require_once 'config.php';
require_once 'database.php';

function handle_playlist_request($action, $id) {
    switch ($action) {
        case 'index':
            playlist_index();
            break;
        case 'create':
            playlist_create();
            break;
        case 'edit':
            playlist_edit($id);
            break;
        case 'delete':
            playlist_delete($id);
            break;
        case 'manage':
            playlist_manage($id);
            break;
        case 'add-item':
            playlist_add_item($id);
            break;
        case 'remove-item':
            playlist_remove_item($id);
            break;
        case 'update-duracao':
            playlist_update_duracao($id);
            break;
        case 'reorder':
            playlist_reorder($id);
            break;
        default:
            http_response_code(404);
            echo "<h1>404 - Playlist Action Not Found</h1>";
            break;
    }
}

function playlist_index() {
    $playlists = get_all_playlists_with_count();

    $data = [
        'page_title' => 'Playlists',
        'active_menu' => 'playlists',
        'playlists' => $playlists,
    ];
    
    $content_view = __DIR__ . '/../views/admin/playlists/index.php';
    require_once __DIR__ . '/../views/layout/admin.php';
}

function playlist_create() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nome'] ?? '';
        $descricao = $_POST['descricao'] ?? null;
        $ativo = isset($_POST['ativo']) ? 1 : 0;
        
        if (empty($nome)) {
            $_SESSION['message'] = 'Nome é obrigatório.';
            $_SESSION['message_type'] = 'danger';
            redirect(ADMIN_PATH . '/playlists/create');
            return;
        }
        
        $sql = "INSERT INTO playlists (nome, descricao, ativo) VALUES (?, ?, ?)";
        $result = db_execute($sql, [$nome, $descricao, $ativo]);
        
        if ($result) {
            $_SESSION['message'] = 'Playlist criada com sucesso!';
            $_SESSION['message_type'] = 'success';
            redirect(ADMIN_PATH . '/playlists');
        } else {
            $_SESSION['message'] = 'Erro ao criar playlist.';
            $_SESSION['message_type'] = 'danger';
            redirect(ADMIN_PATH . '/playlists/create');
        }
        return;
    }
    
    $data = [
        'page_title' => 'Nova Playlist',
        'active_menu' => 'playlists',
    ];
    
    $content_view = __DIR__ . '/../views/admin/playlists/create.php';
    require_once __DIR__ . '/../views/layout/admin.php';
}

function playlist_edit($id) {
    $playlist = get_playlist($id);
    
    if (!$playlist) {
        http_response_code(404);
        echo "<h1>404 - Playlist não encontrada</h1>";
        return;
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nome'] ?? '';
        $descricao = $_POST['descricao'] ?? null;
        $ativo = isset($_POST['ativo']) ? 1 : 0;
        
        $sql = "UPDATE playlists SET nome = ?, descricao = ?, ativo = ? WHERE id = ?";
        $result = db_execute($sql, [$nome, $descricao, $ativo, $id]);
        
        $_SESSION['message'] = 'Playlist atualizada com sucesso!';
        $_SESSION['message_type'] = 'success';
        redirect(ADMIN_PATH . '/playlists');
        return;
    }
    
    $data = [
        'page_title' => 'Editar Playlist',
        'active_menu' => 'playlists',
        'playlist' => $playlist,
    ];
    
    $content_view = __DIR__ . '/../views/admin/playlists/edit.php';
    require_once __DIR__ . '/../views/layout/admin.php';
}

function playlist_delete($id) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo "<h1>405 - Method Not Allowed</h1>";
        return;
    }
    
    $sql = "DELETE FROM playlists WHERE id = ?";
    $result = db_execute($sql, [$id]);
    
    $_SESSION['message'] = 'Playlist excluída com sucesso!';
    $_SESSION['message_type'] = 'success';
    redirect(ADMIN_PATH . '/playlists');
}

function playlist_manage($id) {
    $playlist = get_playlist($id);
    
    if (!$playlist) {
        http_response_code(404);
        echo "<h1>404 - Playlist não encontrada</h1>";
        return;
    }
    
    $playlist_itens = get_playlist_items_with_details($id);
    $conteudos_disponiveis = get_conteudos_ativos();
    
    $data = [
        'page_title' => 'Gerenciar Playlist',
        'active_menu' => 'playlists',
        'playlist' => $playlist,
        'playlist_itens' => $playlist_itens,
        'conteudos_disponiveis' => $conteudos_disponiveis,
    ];
    
    $content_view = __DIR__ . '/../views/admin/playlists/manage.php';
    require_once __DIR__ . '/../views/layout/admin.php';
}

function playlist_add_item($id) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        return;
    }
    
    $conteudo_id = intval($_POST['conteudo_id'] ?? 0);
    $duracao = !empty($_POST['duracao']) ? intval($_POST['duracao']) : null;
    
    // Verificar se o item já existe
    $sql = "SELECT id FROM playlist_itens WHERE playlist_id = ? AND conteudo_id = ?";
    $existing = db_select($sql, [$id, $conteudo_id]);
    
    if (!empty($existing)) {
        $_SESSION['message'] = 'Este conteúdo já está na playlist.';
        $_SESSION['message_type'] = 'warning';
        redirect(ADMIN_PATH . '/playlists/manage/' . $id);
        return;
    }
    
    // Obter próxima ordem
    $sql = "SELECT COALESCE(MAX(ordem), 0) + 1 as next_ordem FROM playlist_itens WHERE playlist_id = ?";
    $result = db_select($sql, [$id]);
    $next_ordem = $result[0]['next_ordem'];
    
    // Inserir item
    $sql = "INSERT INTO playlist_itens (playlist_id, conteudo_id, ordem, duracao) VALUES (?, ?, ?, ?)";
    db_execute($sql, [$id, $conteudo_id, $next_ordem, $duracao]);
    
    $_SESSION['message'] = 'Conteúdo adicionado à playlist!';
    $_SESSION['message_type'] = 'success';
    redirect(ADMIN_PATH . '/playlists/manage/' . $id);
}

function playlist_remove_item($id) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        return;
    }
    
    $item_id = intval($_POST['item_id'] ?? 0);
    
    // Deletar item
    $sql = "DELETE FROM playlist_itens WHERE id = ? AND playlist_id = ?";
    db_execute($sql, [$item_id, $id]);
    
    // Reordenar itens restantes
    $sql = "SELECT id FROM playlist_itens WHERE playlist_id = ? ORDER BY ordem ASC";
    $items = db_select($sql, [$id]);
    
    $ordem = 1;
    foreach ($items as $item) {
        $sql = "UPDATE playlist_itens SET ordem = ? WHERE id = ?";
        db_execute($sql, [$ordem, $item['id']]);
        $ordem++;
    }
    
    $_SESSION['message'] = 'Item removido da playlist!';
    $_SESSION['message_type'] = 'success';
    redirect(ADMIN_PATH . '/playlists/manage/' . $id);
}

function playlist_update_duracao($id) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        return;
    }
    
    $item_id = intval($_POST['item_id'] ?? 0);
    $duracao = intval($_POST['duracao'] ?? 0);
    
    $sql = "UPDATE playlist_itens SET duracao = ? WHERE id = ? AND playlist_id = ?";
    db_execute($sql, [$duracao, $item_id, $id]);
    
    $_SESSION['message'] = 'Duração atualizada!';
    $_SESSION['message_type'] = 'success';
    redirect(ADMIN_PATH . '/playlists/manage/' . $id);
}

function playlist_reorder($id) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        return;
    }
    
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (!isset($data['items'])) {
        echo json_encode(['success' => false]);
        return;
    }
    
    foreach ($data['items'] as $item) {
        $sql = "UPDATE playlist_itens SET ordem = ? WHERE id = ? AND playlist_id = ?";
        db_execute($sql, [$item['ordem'], $item['id'], $id]);
    }
    
    echo json_encode(['success' => true]);
}

// Funções auxiliares de banco de dados
function get_all_playlists_with_count() {
    $sql = "
        SELECT p.*, 
               COUNT(pi.id) as total_itens
        FROM playlists p
        LEFT JOIN playlist_itens pi ON p.id = pi.playlist_id
        GROUP BY p.id
        ORDER BY p.nome ASC
    ";
    return db_select($sql);
}

function get_playlist_items_with_details($playlist_id) {
    $sql = "
        SELECT 
            pi.*, 
            c.nome, c.tipo, c.arquivo_url, c.texto, c.duracao as conteudo_duracao
        FROM playlist_itens pi
        JOIN conteudos c ON pi.conteudo_id = c.id
        WHERE pi.playlist_id = ?
        ORDER BY pi.ordem ASC
    ";
    return db_select($sql, [$playlist_id]);
}

function get_conteudos_ativos() {
    $sql = "SELECT * FROM conteudos WHERE ativo = 1 ORDER BY nome ASC";
    return db_select($sql);
}
