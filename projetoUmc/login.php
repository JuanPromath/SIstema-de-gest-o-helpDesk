<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HelpDesk+</title>
    <link rel="stylesheet" href="assets/css/global.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .login-container {
            background: var(--background-light);
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header .logo {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .login-header .logo::after {
            content: '+';
            color: var(--accent);
            margin-left: 2px;
        }
        
        .login-header p {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
            font-weight: 600;
        }
        
        .form-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border);
            border-radius: 6px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        
        .btn-login {
            width: 100%;
            padding: 0.875rem;
            background: var(--accent);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
        }
        
        .btn-login:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }
        
        .login-tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid var(--border);
        }
        
        .tab-btn {
            flex: 1;
            padding: 0.75rem;
            background: transparent;
            border: none;
            border-bottom: 3px solid transparent;
            color: var(--text-secondary);
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .tab-btn:hover {
            color: var(--accent);
        }
        
        .tab-btn.active {
            color: var(--accent);
            border-bottom-color: var(--accent);
        }
        
        .login-form {
            display: none;
        }
        
        .login-form.active {
            display: block;
        }
        
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
        }
        
        .login-link p {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }
        
        .login-link a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="logo">HelpDesk</div>
            <p>Sistema de Gestão de Chamados</p>
        </div>
        
        <?php
        if (isset($_GET['erro'])) {
            $erroMsg = '';
            if ($_GET['erro'] == '1') {
                $erroMsg = 'E-mail ou senha incorretos!';
            } elseif ($_GET['erro'] == '2') {
                $erroMsg = 'Você não tem permissão de administrador!';
            }elseif($_GET['erro'] == '3'){
                $erroMsg = 'Faça Login no login de administrador!';
            }
            if ($erroMsg) {
                echo '<div class="alert alert-error" style="margin-bottom: 1.5rem; padding: 1rem; border-radius: 6px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">' . htmlspecialchars($erroMsg) . '</div>';
            }
        }
        
        if (isset($_GET['sucesso'])) {
            echo '<div class="alert alert-success" style="margin-bottom: 1.5rem; padding: 1rem; border-radius: 6px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb;">Funcionário cadastrado com sucesso! Um administrador precisa criar sua conta para você fazer login.</div>';
        }
        ?>
        
        <div class="login-tabs">
            <button type="button" class="tab-btn active" onclick="switchTab('funcionario')">Funcionário</button>
            <button type="button" class="tab-btn" onclick="switchTab('admin')">Administrador</button>
        </div>
        
        <!-- Formulário de Login Funcionário -->
        <form id="form-funcionario" action="processaLogin.php" method="post" class="login-form active">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="seu@email.com" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
            </div>
            
            <button type="submit" class="btn-login">Entrar como Funcionário</button>
        </form>
        
        <!-- Formulário de Login Administrador -->
        <form id="form-admin" action="processaLoginAdmin.php" method="post" class="login-form">
            <div class="form-group">
                <label for="email-admin">E-mail</label>
                <input type="email" id="email-admin" name="email" placeholder="admin@email.com" required>
            </div>
            
            <div class="form-group">
                <label for="senha-admin">Senha</label>
                <input type="password" id="senha-admin" name="senha" placeholder="Digite sua senha" required>
            </div>
            
            <button type="submit" class="btn-login">Entrar como Administrador</button>
        </form>
        
        <div class="login-link">
            <p>Não possui uma conta? <a href="registro.php">Cadastre-se como funcionário</a></p>
        </div>
    </div>
    
    <script>
        function switchTab(tipo) {
            // Atualizar botões
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            // Atualizar formulários
            document.getElementById('form-funcionario').classList.remove('active');
            document.getElementById('form-admin').classList.remove('active');
            
            if (tipo === 'funcionario') {
                document.getElementById('form-funcionario').classList.add('active');
                document.getElementById('email').focus();
            } else {
                document.getElementById('form-admin').classList.add('active');
                document.getElementById('email-admin').focus();
            }
        }
    </script>
</body>
</html>