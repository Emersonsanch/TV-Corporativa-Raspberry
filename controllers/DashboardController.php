<?php
// Arquivo: controllers/DashboardController.php
// Lógica do Dashboard

require_once 'config.php';
require_once 'database.php';

function handle_dashboard_request($action) {
    if ($action === 'index') {
        dashboard_index();
    } else {
        http_response_code(404);
        echo "<h1>404 - Dashboard Action Not Found</h1>";
    }
}

function dashboard_index() {
    // Dados para o Dashboard
    $total_conteudos = db_select("SELECT COUNT(*) as count FROM conteudos")[0]['count'];
    $total_playlists = db_select("SELECT COUNT(*) as count FROM playlists")[0]['count'];
    $total_tvs = db_select("SELECT COUNT(*) as count FROM tvs")[0]['count'];
    $tvs_online = db_select("SELECT COUNT(*) as count FROM tvs WHERE ultimo_ping >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)")[0]['count'];

    // Renderiza a view
    $data = [
        'page_title' => 'Dashboard',
        'active_menu' => 'dashboard',
        'total_conteudos' => $total_conteudos,
        'total_playlists' => $total_playlists,
        'total_tvs' => $total_tvs,
        'tvs_online' => $tvs_online,
    ];
    
    // O layout será incluído na view principal
    $content_view = __DIR__ . '/../views/admin/dashboard.php';
    require_once __DIR__ . '/../views/layout/admin.php';
}
