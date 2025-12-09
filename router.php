<?php
// Arquivo: router.php
// Sistema de Roteamento e Autenticação

require_once 'config.php';
require_once 'database.php';
require_once 'controllers/AuthController.php';

// --- Funções de Roteamento ---

/**
 * Inclui a view solicitada.
 * @param string $view_name
 * @param array $data
 */
function view($view_name, $data = []) {
    extract($data); // Extrai o array $data para variáveis
    $file = __DIR__ . "/views/{$view_name}.php";
    if (file_exists($file)) {
        require $file;
    } else {
        http_response_code(404);
        echo "<h1>404 - View Not Found: {$view_name}</h1>";
    }
}

/**
 * Lida com a requisição e chama o controller/função apropriada.
 */
function handle_request() {
    $request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    // Define o caminho base se o projeto estiver em um subdiretório.
    // Isso é crucial para que o roteamento funcione corretamente quando o projeto não está na raiz do servidor.
    // O caminho base é o diretório onde o index.php está.
    $base_path = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
    if ($base_path !== '/') {
        // Remove o caminho base do REQUEST_URI
        $request_uri = substr($request_uri, strlen($base_path));
    }

    $request_uri = trim($request_uri, '/');
    $segments = explode('/', $request_uri);
    
    // Rota padrão para a raiz
    if (empty($segments[0])) {
        redirect(ADMIN_PATH . '/dashboard');
    }

    // --- Roteamento Público ---
    
    // Rota /tv/{id}
    if ($segments[0] === 'tv' && isset($segments[1])) {
        require_once 'controllers/TvPlayerController.php';
        show_tv_player($segments[1]);
        return;
    }

    // Rota /api/...
    if ($segments[0] === 'api') {
        // A API será tratada no próximo passo (Fase 4)
        require_once 'controllers/ApiController.php';
        handle_api_request($segments);
        return;
    }

    // Rota /login
    if ($segments[0] === 'login') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            login_post();
        } else {
            login_get();
        }
        return;
    }

    // Rota /logout
    if ($segments[0] === 'logout') {
        logout();
        return;
    }

    // --- Roteamento Administrativo (Protegido) ---
    
    // Rota /admin/...
    if ($segments[0] === 'admin') {
        if (!is_logged_in()) {
            redirect('/login');
        }
        
        $controller_name = $segments[1] ?? 'dashboard';
        $action = $segments[2] ?? 'index';
        $id = $segments[3] ?? null;
        
        // Mapeamento de Controllers
        switch ($controller_name) {
            case 'dashboard':
                require_once 'controllers/DashboardController.php';
                handle_dashboard_request($action);
                break;
            case 'conteudos':
                require_once 'controllers/ConteudoController.php';
                handle_conteudo_request($action, $id);
                break;
            case 'playlists':
                require_once 'controllers/PlaylistController.php';
                handle_playlist_request($action, $id);
                break;
            case 'tvs':
                require_once 'controllers/TvController.php';
                handle_tv_request($action, $id);
                break;
            default:
                http_response_code(404);
                echo "<h1>404 - Admin Controller Not Found</h1>";
                break;
        }
        return;
    }

    // --- 404 Not Found ---
    http_response_code(404);
    echo "<h1>404 - Página Não Encontrada</h1>";
}
