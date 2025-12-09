<?php
if (!is_logged_in()) redirect('/login');
$page_title = $page_title ?? 'Dashboard';
$user_name  = get_user_name();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $page_title ?> | <?= APP_NAME ?></title>

    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- AdminLTE + Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <style>
        :root {
            --primary: #4361ee;
            --primary-light: #e0e7ff;
            --dark: #1e293b;
            --gray: #64748b;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }

        /* Navbar Topo - Estilo Premium */
        .main-header {
            background: linear-gradient(90deg, #1e293b 0%, #334155 100%);
            backdrop-filter: blur(10px);
            border-bottom: none;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            height: 70px;
            z-index: 1000;
        }

        .navbar-nav.top-menu .nav-link {
            color: #e2e8f0 !important;
            font-weight: 500;
            padding: 0.6rem 1.2rem !important;
            border-radius: 12px;
            transition: all 0.3s ease;
            margin: 0 6px;
            position: relative;
        }

        .navbar-nav.top-menu .nav-link:hover {
            background: rgba(255,255,255,0.15);
            color: white !important;
            transform: translateY(-2px);
        }

        .navbar-nav.top-menu .nav-link.active {
            background: var(--primary);
            color: white !important;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.4);
            font-weight: 600;
        }

        .navbar-nav.top-menu .nav-link i {
            margin-right: 8px;
            font-size: 1.1rem;
        }

        /* User Dropdown */
        .user-menu .nav-link {
            color: white !important;
            font-weight: 600;
        }

        .dropdown-menu {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            margin-top: 10px;
        }

        /* Content */
        .content-wrapper {
            background: transparent;
            margin-top: 20px;
        }

        .content-header h1 {
            font-weight: 700;
            color: var(--dark);
            font-size: 2rem;
        }

        /* Cards mais bonitos */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary), #5b7bff);
            color: white;
            border-bottom: none;
            font-weight: 600;
        }

        /* Responsivo */
        @media (max-width: 768px) {
            .navbar-nav.top-menu {
                flex-wrap: nowrap;
                overflow-x: auto;
                padding: 10px 0;
                -webkit-overflow-scrolling: touch;
            }
            .navbar-nav.top-menu .nav-link {
                white-space: nowrap;
                font-size: 0.9rem;
                padding: 0.5rem 0.8rem !important;
            }
            .main-header { height: auto; }
        }
    </style>
</head>

<body class="hold-transition sidebar-collapse layout-navbar-fixed">

<div class="wrapper">

    <!-- Navbar Premium -->
    <nav class="main-header navbar navbar-expand navbar-dark">
      

        <!-- Menu Principal Centralizado -->
        <ul class="navbar-nav top-menu mx-auto order-1 order-md-2">
            <li class="nav-item">
                <a href="<?= ADMIN_PATH ?>/dashboard" class="nav-link <?= ($active_menu ?? '') == 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= ADMIN_PATH ?>/conteudos" class="nav-link <?= ($active_menu ?? '') == 'conteudos' ? 'active' : '' ?>">
                    <i class="fas fa-images"></i> Conteúdos
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= ADMIN_PATH ?>/playlists" class="nav-link <?= ($active_menu ?? '') == 'playlists' ? 'active' : '' ?>">
                    <i class="fas fa-list-ul"></i> Playlists
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= ADMIN_PATH ?>/tvs" class="nav-link <?= ($active_menu ?? '') == 'tvs' ? 'active' : '' ?>">
                    <i class="fas fa-tv"></i> TVs
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-cog"></i> Configurações
                </a>
            </li>
        </ul>

        <!-- Usuário + Sair -->
       <!--  <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown user-menu">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-toggle="dropdown">
                    <img src="https://via.placeholder.com/160x160/4361ee/ffffff?text=<?= substr($user_name,0,2) ?>" 
                         class="img-circle elevation-2 mr-2" width="35" height="35" alt="User">
                    <span class="d-none d-md-inline"><?= $user_name ?></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="/perfil" class="dropdown-item">
                        <i class="fas fa-user mr-2"></i> Meu Perfil
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="/logout" class="dropdown-item text-danger">
                        <i class="fas fa-sign-out-alt mr-2"></i> Sair
                    </a>
                </div>
            </li>
        </ul> -->
    </nav>

    <!-- Conteúdo Principal -->
    <div class="content-wrapper pb-5">
        <div class="content-header">
            <div class="container-fluid">
                <h1 class="m-0 text-dark">INGAFLEX</h1>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">

                <?php if (isset($message) && $message): ?>
                <div class="alert alert-<?= $message_type ?? 'success' ?> alert-dismissible fade show rounded-lg shadow-sm">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <?= htmlspecialchars($message) ?>
                </div>
                <?php endif; ?>

                <?php require_once $content_view; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="main-footer text-center text-sm">
        <strong>TV Corporativa INGAFLEX</strong> © <?= date('Y') ?> • Todos os direitos reservados
    </footer>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
<?php unset($content_view); ?>