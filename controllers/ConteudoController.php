<?php
// Arquivo: controllers/ConteudoController.php
// Lógica do CRUD de Conteúdos

require_once 'config.php';
require_once 'database.php';

function handle_conteudo_request($action, $id) {
    switch ($action) {
        case 'index':
            conteudo_index();
            break;
        case 'create':
            conteudo_create();
            break;
        case 'edit':
            conteudo_edit($id);
            break;
        case 'delete':
            conteudo_delete($id);
            break;
        default:
            http_response_code(404);
            echo "<h1>404 - Conteudo Action Not Found</h1>";
            break;
    }
}

function conteudo_index() {
    $conteudos = get_all_conteudos();

    $data = [
        'page_title' => 'Conteúdos',
        'active_menu' => 'conteudos',
        'conteudos' => $conteudos,
    ];
    
    $content_view = __DIR__ . '/../views/admin/conteudos/index.php';
    require_once __DIR__ . '/../views/layout/admin.php';
}

function conteudo_create() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Processar criação
        $nome = $_POST['nome'] ?? '';
        $tipo = $_POST['tipo'] ?? '';
        $duracao = intval($_POST['duracao'] ?? 10);
        $ativo = isset($_POST['ativo']) ? 1 : 0;
        $texto = $_POST['texto'] ?? null;
        $link = $_POST['link'] ?? null;
        $arquivo_url = null;
        
        // Validação básica
        if (empty($nome) || empty($tipo)) {
            $_SESSION['message'] = 'Nome e tipo são obrigatórios.';
            $_SESSION['message_type'] = 'danger';
            redirect(ADMIN_PATH . '/conteudos/create');
            return;
        }
        
        // Upload de arquivo (imagem ou vídeo)
        if (($tipo === 'imagem' || $tipo === 'video') && isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {
            $arquivo_url = handle_file_upload($_FILES['arquivo'], $tipo);
            if (!$arquivo_url) {
                $_SESSION['message'] = 'Erro ao fazer upload do arquivo.';
                $_SESSION['message_type'] = 'danger';
                redirect(ADMIN_PATH . '/conteudos/create');
                return;
            }
        } elseif ($tipo === 'link') {
            $arquivo_url = $link;
        }
        
        // Inserir no banco
        $sql = "INSERT INTO conteudos (nome, tipo, arquivo_url, texto, duracao, ativo) VALUES (?, ?, ?, ?, ?, ?)";
        $result = db_execute($sql, [$nome, $tipo, $arquivo_url, $texto, $duracao, $ativo]);
        
        if ($result) {
            $_SESSION['message'] = 'Conteúdo criado com sucesso!';
            $_SESSION['message_type'] = 'success';
            redirect(ADMIN_PATH . '/conteudos');
        } else {
            $_SESSION['message'] = 'Erro ao criar conteúdo.';
            $_SESSION['message_type'] = 'danger';
            redirect(ADMIN_PATH . '/conteudos/create');
        }
        return;
    }
    
    // Exibir formulário
    $data = [
        'page_title' => 'Novo Conteúdo',
        'active_menu' => 'conteudos',
    ];
    
    $content_view = __DIR__ . '/../views/admin/conteudos/create.php';
    require_once __DIR__ . '/../views/layout/admin.php';
}

function conteudo_edit($id) {
    $conteudo = get_conteudo($id);
    
    if (!$conteudo) {
        http_response_code(404);
        echo "<h1>404 - Conteúdo não encontrado</h1>";
        return;
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Processar edição
        $nome = $_POST['nome'] ?? '';
        $duracao = intval($_POST['duracao'] ?? 10);
        $ativo = isset($_POST['ativo']) ? 1 : 0;
        $texto = $_POST['texto'] ?? null;
        $link = $_POST['link'] ?? null;
        $arquivo_url = $conteudo['arquivo_url']; // Manter o arquivo atual por padrão
        
        // Upload de novo arquivo (se fornecido)
        if (($conteudo['tipo'] === 'imagem' || $conteudo['tipo'] === 'video') && 
            isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {
            $new_arquivo_url = handle_file_upload($_FILES['arquivo'], $conteudo['tipo']);
            if ($new_arquivo_url) {
                // Deletar arquivo antigo
                delete_upload_file($arquivo_url);
                $arquivo_url = $new_arquivo_url;
            }
        } elseif ($conteudo['tipo'] === 'link') {
            $arquivo_url = $link;
        }
        
        // Atualizar no banco
        $sql = "UPDATE conteudos SET nome = ?, arquivo_url = ?, texto = ?, duracao = ?, ativo = ? WHERE id = ?";
        $result = db_execute($sql, [$nome, $arquivo_url, $texto, $duracao, $ativo, $id]);
        
        $_SESSION['message'] = 'Conteúdo atualizado com sucesso!';
        $_SESSION['message_type'] = 'success';
        redirect(ADMIN_PATH . '/conteudos');
        return;
    }
    
    // Exibir formulário
    $data = [
        'page_title' => 'Editar Conteúdo',
        'active_menu' => 'conteudos',
        'conteudo' => $conteudo,
    ];
    
    $content_view = __DIR__ . '/../views/admin/conteudos/edit.php';
    require_once __DIR__ . '/../views/layout/admin.php';
}

function conteudo_delete($id) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo "<h1>405 - Method Not Allowed</h1>";
        return;
    }
    
    $conteudo = get_conteudo($id);
    
    if (!$conteudo) {
        $_SESSION['message'] = 'Conteúdo não encontrado.';
        $_SESSION['message_type'] = 'danger';
        redirect(ADMIN_PATH . '/conteudos');
        return;
    }
    
    // Deletar arquivo se existir
    if ($conteudo['arquivo_url'] && ($conteudo['tipo'] === 'imagem' || $conteudo['tipo'] === 'video')) {
        delete_upload_file($conteudo['arquivo_url']);
    }
    
    // Deletar do banco
    $sql = "DELETE FROM conteudos WHERE id = ?";
    $result = db_execute($sql, [$id]);
    
    $_SESSION['message'] = 'Conteúdo excluído com sucesso!';
    $_SESSION['message_type'] = 'success';
    redirect(ADMIN_PATH . '/conteudos');
}

// Função auxiliar para upload de arquivos
function handle_file_upload($file, $tipo) {
    $upload_dir = __DIR__ . '/../public/uploads/';
    
    // Criar diretório se não existir
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Validar tipo de arquivo
    $allowed_types = [
        'imagem' => ['image/jpeg', 'image/png', 'image/gif'],
        'video' => ['video/mp4', 'video/webm']
    ];
    
    if (!in_array($file['type'], $allowed_types[$tipo])) {
        return false;
    }
    
    // Validar tamanho
    $max_size = $tipo === 'imagem' ? 10 * 1024 * 1024 : 100 * 1024 * 1024; // 10MB para imagem, 100MB para vídeo
    if ($file['size'] > $max_size) {
        return false;
    }
    
    // Gerar nome único
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $filepath = $upload_dir . $filename;
    
    // Mover arquivo
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return $filename;
    }
    
    return false;
}

// Função auxiliar para deletar arquivo
function delete_upload_file($filename) {
    if (empty($filename)) {
        return;
    }
    
    $filepath = __DIR__ . '/../public/uploads/' . $filename;
    if (file_exists($filepath)) {
        unlink($filepath);
    }
}
