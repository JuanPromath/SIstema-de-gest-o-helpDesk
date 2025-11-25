<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HelpDesk+ - Sistema de Gestão</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/animations.css">
    
    <style>
        /* Override para garantir que o body tenha o fundo correto */
        body {
            background: var(--bg-primary) !important;
        }
    </style>
</head>
<body>
    <?php if (isset($_SESSION['usuario'])): ?>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-headset"></i>
                <span>HelpDesk+</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="bi bi-house-door"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?controller=ChamadoController&action=listar">
                            <i class="bi bi-ticket-perforated"></i> Chamados
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?controller=ClienteController&action=listar">
                            <i class="bi bi-people"></i> Clientes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?controller=FuncionarioController&action=listar">
                            <i class="bi bi-person-badge"></i> Funcionários
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?controller=CargoController&action=listar">
                            <i class="bi bi-briefcase"></i> Cargos
                        </a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center gap-3">
                    <?php 
                    if (file_exists('admin_functions.php')) {
                        require_once 'admin_functions.php';
                        $isAdmin = isAdmin();
                        if ($isAdmin) {
                            echo '<span class="badge bg-danger text-white px-3 py-2" style="font-size: 0.85rem;">
                                <i class="bi bi-shield-check"></i> Administrador
                            </span>';
                        }
                    }
                    ?>
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://ui-avatars.com/api/?name=Admin&background=3498db&color=fff" alt="Avatar" class="rounded-circle" style="width: 36px; height: 36px; border: 2px solid rgba(255,255,255,0.3);">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> Meu Perfil</a></li>
                            <?php if (isset($isAdmin) && $isAdmin): ?>
                            <li><a class="dropdown-item" href="?controller=CargoController&action=listar"><i class="bi bi-briefcase"></i> Gerenciar Cargos</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i> Sair</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <?php endif; ?>
    
    <!-- Main Content -->
    <div class="container-fluid px-4" style="margin-top: <?php echo isset($_SESSION['usuario']) ? '80px' : '0'; ?>; min-height: calc(100vh - 80px);">
        <?php if (isset($content)) { echo $content; } ?>
    </div>
    
    <?php if (isset($_SESSION['usuario'])): ?>
    <!-- Footer -->
    <footer class="footer-modern mt-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <span class="fw-bold">HelpDesk+</span> &copy; <?php echo date('Y'); ?> - Todos os direitos reservados
                </div>
                <div class="col-12 col-md-6 text-center text-md-end">
                    <a href="#" class="me-3"><i class="bi bi-github fs-5"></i></a>
                    <a href="#" class="me-3"><i class="bi bi-linkedin fs-5"></i></a>
                    <a href="#"><i class="bi bi-envelope fs-5"></i></a>
                </div>
            </div>
        </div>
    </footer>
    <?php endif; ?>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS para animações da navbar -->
    <script>
        // Efeito de scroll na navbar
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-custom');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
        
        // Adicionar classe active ao link atual
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath.split('/').pop()) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
