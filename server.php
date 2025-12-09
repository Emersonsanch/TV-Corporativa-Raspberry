<?php
// server.php
// Arquivo de roteamento para o servidor embutido do PHP

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = urldecode($uri);

// Se a URI for um arquivo estático (CSS, JS, imagem, etc.),
// o servidor embutido deve servi-lo diretamente.
// Isso simula o comportamento do .htaccess que ignora arquivos e diretórios existentes.
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

// Caso contrário, todas as requisições são roteadas para o index.php
require_once 'index.php';
