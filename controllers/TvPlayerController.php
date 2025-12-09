<?php
// Arquivo: controllers/TvPlayerController.php
// Lógica do Player Web (/tv/{token})

require_once 'config.php';
require_once 'database.php';

function show_tv_player($token) {
    $tv = get_tv_by_token($token);

    if (!$tv) {
        http_response_code(404);
        die("<h1>404 - TV não encontrada</h1><p>Token inválido ou TV não cadastrada.</p>");
    }

    // Atualizar último ping
    update_tv_ping($tv['id']);

    // Obter itens da playlist
    $playlist_itens = [];
    if ($tv['playlist_id']) {
        $playlist_itens = get_playlist_items($tv['playlist_id']);
    }

    // Renderizar view
    $tv_data = $tv;
    $playlist_itens_data = $playlist_itens;
    
    require_once __DIR__ . '/../views/tv/player.php';
}
