<?php
session_start();
require_once __DIR__ . '/../app/config/conexao.php';

// Se já estiver logado como admin, redireciona
if (isset($_SESSION['usuario'])) {
    require_once 'admin_functions.php';
    if (isAdmin()) {
        header('Location: index.php');
        exit;
    }
}

$feedback = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $senha = $_POST['senha'] ?? '';
    
    if ($usuario && $senha) {
        // Se o usuário for "admin", buscar pelo email do funcionário admin
        if (strtolower(trim($usuario)) === 'admin') {
            $funcAdmin = selectWhere('Funcionario', ['codigo'], "LOWER(email) = 'admin@helpdesk.com'");
            if ($funcAdmin && mysqli_num_rows($funcAdmin) > 0) {
                $func = mysqli_fetch_assoc($funcAdmin);
                $usuario = $func['codigo'];
            }
        }
        
        // Buscar conta pelo ID do funcionário
        $result = selectWhere('Conta_Sistema', ['*'], "Id_funcionario = '".mysqli_real_escape_string($conn, $usuario)."'");
        
        if ($result && mysqli_num_rows($result) > 0) {
            $conta = mysqli_fetch_assoc($result);
            
            if (password_verify($senha, $conta['senha'])) {
                // Verificar se é administrador
                $query = "SELECT f.codigo, f.nome, c.nome as cargo_nome 
                         FROM Funcionario f 
                         INNER JOIN Cargo c ON f.id_cargo = c.codigo 
                         WHERE f.codigo = " . (int)$conta['Id_funcionario'];
                
                $funcResult = mysqli_query($conn, $query);
                
                if ($funcResult && mysqli_num_rows($funcResult) > 0) {
                    $funcionario = mysqli_fetch_assoc($funcResult);
                    
                    if (strtolower(trim($funcionario['cargo_nome'])) === 'administrador') {
                        $_SESSION['usuario'] = $conta['Id_funcionario'];
                        $_SESSION['conta_id'] = $conta['codigo'];
                        $_SESSION['is_admin'] = true;
                        header('Location: index.php');
                        exit;
                    } else {
                        $feedback = '<div class="alert alert-warning mt-3 fade-in"><i class="bi bi-exclamation-triangle"></i> Esta conta não possui permissões de administrador.</div>';
                    }
                } else {
                    $feedback = '<div class="alert alert-danger mt-3 fade-in"><i class="bi bi-x-circle"></i> Funcionário não encontrado.</div>';
                }
            } else {
                $feedback = '<div class="alert alert-danger mt-3 fade-in"><i class="bi bi-x-circle"></i> Senha incorreta.</div>';
            }
        } else {
            $feedback = '<div class="alert alert-danger mt-3 fade-in"><i class="bi bi-x-circle"></i> Usuário não encontrado.</div>';
        }
    } else {
        $feedback = '<div class="alert alert-warning mt-3 fade-in"><i class="bi bi-exclamation-triangle"></i> Preencha todos os campos.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrativo - HelpDesk+</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/animations.css">
    
    <style>
        .admin-badge {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box scale-in">
            <div class="login-logo">
                <div class="admin-badge">
                    <i class="bi bi-shield-check"></i>
                    Acesso Administrativo
                </div>
                <h1>
                    <i class="bi bi-headset"></i>
                    HelpDesk+
                </h1>
                <p>Painel de Administração</p>
            </div>
            
            <form action="admin_login.php" method="post" class="fade-in">
                <div class="input-group-modern mb-4">
                    <i class="bi bi-person input-icon"></i>
                    <input 
                        type="text" 
                        class="form-control-modern" 
                        id="usuario" 
                        name="usuario" 
                        placeholder="ID do Funcionário ou 'admin'" 
                        required
                        autofocus
                    >
                    <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Use "admin" ou o ID numérico do funcionário</small>
                </div>
                
                <div class="input-group-modern mb-4">
                    <i class="bi bi-key input-icon"></i>
                    <input 
                        type="password" 
                        class="form-control-modern" 
                        id="senha" 
                        name="senha" 
                        placeholder="Senha" 
                        required
                    >
                </div>
                
                <button type="submit" class="btn-modern btn-modern-primary w-100">
                    <i class="bi bi-shield-lock"></i>
                    Entrar como Administrador
                </button>
                
                <?php echo $feedback; ?>
            </form>
            
            <div class="text-center mt-4">
                <a href="login.php" class="text-muted" style="text-decoration: none; font-size: 0.9rem;">
                    <i class="bi bi-arrow-left"></i> Voltar para login normal
                </a>
            </div>
            
            <div class="text-center mt-3">
                <small class="text-muted">
                    <i class="bi bi-shield-check"></i>
                    Apenas administradores podem acessar
                </small>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
