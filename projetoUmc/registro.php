<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Funcionário - HelpDesk+</title>
    <link rel="stylesheet" href="assets/css/global.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .register-container {
            background: var(--background-light);
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
        }
        
        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .register-header .logo {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .register-header .logo::after {
            content: '+';
            color: var(--accent);
            margin-left: 2px;
        }
        
        .register-header p {
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
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border);
            border-radius: 6px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        
        .btn-register {
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
        
        .btn-register:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }
        
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
        }
        
        .login-link a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <div class="logo">HelpDesk</div>
            <p>Cadastro de Funcionário</p>
        </div>
        
        <?php
        session_start();
        require_once 'conexao.php';
        
        $mensagem = '';
        $tipoMensagem = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar campos
            if (validaCampo('nome') && validaCampo('cpf') && validaCampo('email') && validaCampo('cargo')) {
                // Verificar se o email já existe
                $emailCheck = selectWhere("funcionario", ["codigo"], "email = '" . $_POST['email'] . "'");
                
                if (mysqli_num_rows($emailCheck) > 0) {
                    $mensagem = 'Este e-mail já está cadastrado!';
                    $tipoMensagem = 'error';
                } else {
                    // Verificar se o CPF já existe
                    $cpfCheck = selectWhere("funcionario", ["codigo"], "cpf = '" . $_POST['cpf'] . "'");
                    
                    if (mysqli_num_rows($cpfCheck) > 0) {
                        $mensagem = 'Este CPF já está cadastrado!';
                        $tipoMensagem = 'error';
                    } else {
                        // Cadastrar funcionário
                        // A função insert não retorna valor, então vamos verificar se o registro foi criado
                        ob_start(); // Capturar qualquer output da função insert
                        insert(['nome', 'cpf', 'email', 'id_cargo'], $_POST, "funcionario");
                        ob_end_clean(); // Limpar o output
                        
                        // Verificar se o funcionário foi cadastrado
                        $verificar = selectWhere("funcionario", ["codigo"], "email = '" . $_POST['email'] . "' AND cpf = '" . $_POST['cpf'] . "'");
                        
                        if (mysqli_num_rows($verificar) > 0) {
                            // Redirecionar para login com mensagem de sucesso
                            header('location: login.php?sucesso=1');
                            exit;
                        } else {
                            $mensagem = 'Erro ao cadastrar funcionário. Verifique os dados e tente novamente.';
                            $tipoMensagem = 'error';
                        }
                    }
                }
            } else {
                $mensagem = 'Por favor, preencha todos os campos!';
                $tipoMensagem = 'error';
            }
        }
        ?>
        
        <?php if ($mensagem): ?>
            <div class="alert alert-<?php echo $tipoMensagem; ?>">
                <?php echo htmlspecialchars($mensagem); ?>
            </div>
        <?php endif; ?>
        
        <form action="registro.php" method="post">
            <div class="form-group">
                <label for="nome">Nome Completo</label>
                <input type="text" id="nome" name="nome" placeholder="Digite seu nome completo" required 
                       value="<?php echo isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="cpf">CPF</label>
                <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" maxlength="14" required
                       value="<?php echo isset($_POST['cpf']) ? htmlspecialchars($_POST['cpf']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="seu@email.com" required
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="cargo">Cargo</label>
                <select id="cargo" name="cargo" required>
                    <option value="">Selecione o cargo</option>
                    <?php
                    $result = select("Cargo");
                    
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $selected = (isset($_POST['cargo']) && $_POST['cargo'] == $row['codigo']) ? 'selected' : '';
                            echo "<option value='" . $row['codigo'] . "' $selected>" . $row['nome'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>Nenhum cargo disponível</option>";
                    }
                    ?>
                </select>
            </div>
            
            <button type="submit" class="btn-register">Cadastrar</button>
        </form>
        
        <div class="login-link">
            <p>Já possui uma conta? <a href="login.php">Fazer login</a></p>
        </div>
    </div>
</body>
</html>

