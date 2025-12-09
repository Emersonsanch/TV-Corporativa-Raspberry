<?php
// Arquivo: index.php
// Ponto de entrada principal do sistema

require_once 'config.php';
require_once 'database.php';
require_once 'router.php';

// O roteador irá lidar com a requisição
handle_request();
