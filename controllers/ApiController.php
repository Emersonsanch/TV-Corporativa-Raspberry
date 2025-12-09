<?php
// Arquivo: controllers/ApiController.php
// Lógica da API RESTful

require_once 'config.php';
require_once 'database.php';

// --- Funções de Ajuda da API ---

function json_response($data, $status = 200) {
    header('Content-Type: application/json');
    http_response_code($status);
    echo json_encode($data);
    exit();
}

function handle_api_request($segments) {
    $route = $segments[1] ?? '';
    $id = $segments[2] ?? null;
    $action = $segments[3] ?? null;

    switch ($route) {
        case 'tv':
            if ($id && $action === 'playlist') {
                get_tv_playlist($id);
            } elseif ($id && $action === 'ping' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                post_tv_ping($id);
            } else {
                json_response(['error' => 'Rota de API inválida'], 404);
            }
            break;
        default:
            json_response(['error' => 'Rota de API não encontrada'], 404);
            break;
    }
}

// --- Lógica da API ---

function get_tv_playlist($tv_id) {
    // 1. Buscar TV
    $tv = get_tv($tv_id);
    if (!$tv) {
        json_response(['error' => 'TV não encontrada'], 404);
    }

    // 2. Buscar Playlist
    $playlist_id = $tv['playlist_id'];
    $playlist = get_playlist($playlist_id);
    
    if (!$playlist || $playlist['ativa'] == 0) {
        json_response(['error' => 'Nenhuma playlist ativa associada a esta TV'], 404);
    }

    // 3. Buscar Itens da Playlist
    $items = get_playlist_items($playlist_id);

    // 4. Formatar resposta
    $formatted_items = [];
    foreach ($items as $item) {
        // Usar a duração do item da playlist, se definida, senão usar a do conteúdo
        $duration = $item['duracao'] ?? $item['conteudo_duracao'];
        
        $formatted_items[] = [
            'id' => $item['conteudo_id'],
            'tipo' => $item['tipo'],
            'arquivo_url' => $item['arquivo_url'] ? get_upload_url($item['arquivo_url']) : null,
            'texto' => $item['texto'],
            'duracao' => (int)$duration,
            'ordem' => (int)$item['ordem'],
        ];
    }

    $response = [
        'tv_id' => (int)$tv_id,
        'tv_nome' => $tv['nome'],
        'playlist_id' => (int)$playlist_id,
        'playlist_nome' => $playlist['nome'],
        'items' => $formatted_items,
        'total_items' => count($formatted_items),
    ];

    // 5. Retornar JSON
    json_response($response);
}

function post_tv_ping($tv_id) {
    // Aceitar tanto via URL quanto via JSON body
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    // Se tv_id vier no body, usar ele
    if (isset($data['tv_id'])) {
        $tv_id = $data['tv_id'];
    }
    
    // 1. Buscar TV
    $tv = get_tv($tv_id);
    if (!$tv) {
        json_response(['error' => 'TV não encontrada'], 404);
    }

    // 2. Atualizar último ping
    $success = update_tv_ping($tv_id);

    if ($success) {
        json_response([
            'success' => true,
            'timestamp' => date('c'),
        ]);
    } else {
        json_response(['error' => 'Falha ao atualizar o ping'], 500);
    }
}
