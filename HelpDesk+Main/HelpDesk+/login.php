<?php
session_start();
require_once 'conexao.php';

// Se já estiver logado, redireciona
if (isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

$feedback = '';
$activeTab = 'login'; // Tab padrão
$formData = []; // Para preservar dados do formulário

// Processar login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $usuario = trim($_POST['usuario'] ?? '');
    $senha = $_POST['senha'] ?? '';
    
    if ($usuario && $senha) {
        // Suporta: número do ID do funcionário, e-mail do funcionário ou a palavra 'admin'
        $usuarioRaw = strtolower(trim($usuario));

        // Se pedirem 'admin', mapear para o email admin@helpdesk.com
        if ($usuarioRaw === 'admin') {
            $lookupEmail = 'admin@helpdesk.com';
        } else {
            $lookupEmail = $usuarioRaw;
        }

        // Se for número, usar diretamente como ID; caso contrário, tentar buscar funcionário por e-mail
        $usuarioId = is_numeric($usuario) ? (int)$usuario : 0;
        if ($usuarioId === 0) {
            // tratar lookupEmail como e-mail (já em minúsculas)
            $emailEscaped = mysqli_real_escape_string($conn, $lookupEmail);
            $funcRes = selectWhere('Funcionario', ['codigo'], "LOWER(email) = '" . $emailEscaped . "'");
            if ($funcRes && mysqli_num_rows($funcRes) > 0) {
                $f = mysqli_fetch_assoc($funcRes);
                $usuarioId = (int)$f['codigo'];
            } else {
                $feedback = '<div class="alert alert-danger mt-3 fade-in"><i class="bi bi-x-circle"></i> Usuário não encontrado pelo e-mail informado.</div>';
            }
        }

        if ($usuarioId > 0 && empty($feedback)) {
            $result = selectWhere('Conta_Sistema', ['*'], "Id_funcionario = $usuarioId");
            if ($result && mysqli_num_rows($result) > 0) {
                $conta = mysqli_fetch_assoc($result);
                if (password_verify($senha, $conta['senha'])) {
                    $_SESSION['usuario'] = $conta['Id_funcionario'];
                    $_SESSION['conta_id'] = $conta['codigo'];
                    header('Location: index.php');
                    exit;
                } else {
                    $feedback = '<div class="alert alert-danger mt-3 fade-in"><i class="bi bi-x-circle"></i> Senha incorreta.</div>';
                }
            } else {
                $feedback = '<div class="alert alert-danger mt-3 fade-in"><i class="bi bi-x-circle"></i> Conta não encontrada para este funcionário. Contate o administrador.</div>';
            }
        }
    } else {
        $feedback = '<div class="alert alert-warning mt-3 fade-in"><i class="bi bi-exclamation-triangle"></i> Preencha todos os campos.</div>';
    }
}

// Processar criação de usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    $activeTab = 'register';
    
    // Preservar dados do formulário
    $formData = [
        'nome' => $_POST['nome'] ?? '',
        'cpf' => $_POST['cpf'] ?? '',
        'email' => $_POST['email'] ?? '',
        'cargo' => $_POST['cargo'] ?? ''
    ];
    
    // Validar campos do funcionário
    if (empty(trim($_POST['nome'] ?? '')) || empty(trim($_POST['cpf'] ?? '')) || empty(trim($_POST['email'] ?? '')) || empty(trim($_POST['cargo'] ?? '')) || empty(trim($_POST['senha'] ?? '')) || empty(trim($_POST['confirmar_senha'] ?? ''))) {
        $feedback = '<div class="alert alert-danger mt-3 fade-in"><i class="bi bi-exclamation-triangle"></i> Preencha todos os campos obrigatórios.</div>';
    } elseif ($_POST['senha'] !== $_POST['confirmar_senha']) {
        $feedback = '<div class="alert alert-danger mt-3 fade-in"><i class="bi bi-exclamation-triangle"></i> As senhas não coincidem.</div>';
    } elseif (strlen($_POST['cpf']) !== 11) {
        $feedback = '<div class="alert alert-danger mt-3 fade-in"><i class="bi bi-exclamation-triangle"></i> CPF deve conter 11 dígitos.</div>';
    } else {
        // Verificar se CPF ou email já existem
        $cpfExists = selectWhere('Funcionario', ['codigo'], "cpf = '".mysqli_real_escape_string($conn, $_POST['cpf'])."'");
        $emailExists = selectWhere('Funcionario', ['codigo'], "email = '".mysqli_real_escape_string($conn, $_POST['email'])."'");
        
        if ($cpfExists && mysqli_num_rows($cpfExists) > 0) {
            $feedback = '<div class="alert alert-danger mt-3 fade-in"><i class="bi bi-exclamation-triangle"></i> CPF já cadastrado no sistema.</div>';
        } elseif ($emailExists && mysqli_num_rows($emailExists) > 0) {
            $feedback = '<div class="alert alert-danger mt-3 fade-in"><i class="bi bi-exclamation-triangle"></i> Email já cadastrado no sistema.</div>';
        } else {
            // Preparar dados do funcionário (mapear 'cargo' para 'id_cargo')
            $dadosFuncionario = [
                'nome' => trim($_POST['nome']),
                'cpf' => trim($_POST['cpf']),
                'email' => trim($_POST['email']),
                'id_cargo' => $_POST['cargo'] // O campo no banco é id_cargo
            ];
            
            // Criar funcionário
            $resultFuncionario = insert(['nome', 'cpf', 'email', 'id_cargo'], $dadosFuncionario, 'Funcionario');
            
            if ($resultFuncionario) {
                // Buscar o ID do funcionário recém-criado
                $funcionarioResult = selectWhere('Funcionario', ['codigo'], "cpf = '".mysqli_real_escape_string($conn, $_POST['cpf'])."'");
                if ($funcionarioResult && mysqli_num_rows($funcionarioResult) > 0) {
                    $funcionario = mysqli_fetch_assoc($funcionarioResult);
                    $funcionarioId = $funcionario['codigo'];
                    
                    // Criar conta
                    $senhaHash = password_hash($_POST['senha'], PASSWORD_DEFAULT);
                    $dadosConta = [
                        'senha' => $senhaHash,
                        'Id_funcionario' => $funcionarioId
                    ];
                    
                    $resultConta = insert(['senha', 'Id_funcionario'], $dadosConta, 'Conta_Sistema');
                    
                    if ($resultConta) {
                        $feedback = '<div class="alert alert-success mt-3 fade-in"><i class="bi bi-check-circle"></i> Usuário criado com sucesso! Seu ID de funcionário é <strong>#' . $funcionarioId . '</strong>. Você pode fazer login agora.</div>';
                        // Limpar dados do formulário após sucesso
                        $formData = [];
                    } else {
                        $errorMsg = mysqli_error($conn);
                        $feedback = '<div class="alert alert-danger mt-3 fade-in"><i class="bi bi-exclamation-triangle"></i> Erro ao criar conta. Funcionário foi criado (ID: #' . $funcionarioId . '), mas a conta não foi criada. ' . ($errorMsg ? htmlspecialchars($errorMsg) : '') . '</div>';
                    }
                } else {
                    $feedback = '<div class="alert alert-danger mt-3 fade-in"><i class="bi bi-exclamation-triangle"></i> Funcionário criado, mas não foi possível recuperar o ID. Tente fazer login com o CPF.</div>';
                }
            } else {
                $errorMsg = mysqli_error($conn);
                $feedback = '<div class="alert alert-danger mt-3 fade-in"><i class="bi bi-exclamation-triangle"></i> Erro ao criar funcionário. ' . ($errorMsg ? htmlspecialchars($errorMsg) : 'Verifique os dados e tente novamente.') . '</div>';
            }
        }
    }
}

// Buscar cargos para o formulário de registro
$cargoOptions = '';
$result = select("Cargo");
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $selected = (isset($formData['cargo']) && $formData['cargo'] == $row['codigo']) ? 'selected' : '';
        $cargoOptions .= "<option value='" . $row['codigo'] . "' $selected>" . htmlspecialchars($row['nome']) . "</option>";
    }
} else {
    $cargoOptions = '<option value="">Nenhum cargo cadastrado. <a href="setup_admin.php">Configure o administrador primeiro</a></option>';
}

// Verificar se há contas no sistema
$totalContas = 0;
$contasResult = select('Conta_Sistema', ['codigo']);
if ($contasResult) {
    $totalContas = mysqli_num_rows($contasResult);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HelpDesk+</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/animations.css">
    
    <style>
        .tabs-container {
            display: flex;
            justify-content: center;
            gap: 0;
            border-bottom: 2px solid var(--bg-tertiary);
            margin-bottom: 2rem;
            padding: 0;
        }
        
        .tab-button {
            flex: 1;
            border: none;
            background: transparent;
            color: var(--text-secondary);
            font-weight: 600;
            padding: 1rem 1.5rem;
            cursor: pointer;
            transition: all var(--transition-fast);
            position: relative;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .tab-button:hover {
            color: var(--primary);
            background: rgba(52, 152, 219, 0.05);
        }
        
        .tab-button.active {
            color: var(--accent);
            font-weight: 700;
        }
        
        .tab-button.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--accent);
            border-radius: 3px 3px 0 0;
        }
        
        .tab-button:focus {
            outline: none;
            box-shadow: none;
        }
        
        .tab-content {
            min-height: 400px;
        }
        
        .tab-pane {
            display: none;
        }
        
        .tab-pane.active {
            display: block;
            animation: fadeIn var(--transition-normal) ease-out;
        }
        
        .form-section {
            margin-bottom: 1.5rem;
        }
        
        .form-section-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--bg-tertiary);
        }
        
        .login-box {
            max-width: 500px;
        }
        
        .setup-hint {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box scale-in">
            <div class="login-logo">
                <h1>
                    <i class="bi bi-headset"></i>
                    HelpDesk+
                </h1>
                <p>Sistema de Gestão</p>
            </div>
            
            <?php if ($totalContas === 0): ?>
            <div class="setup-hint fade-in">
                <i class="bi bi-info-circle text-warning"></i>
                <strong>Primeira vez?</strong> Nenhuma conta encontrada no sistema. 
                <a href="setup_admin.php" class="alert-link">Configure o administrador primeiro</a> ou crie um usuário na aba "Criar Usuário".
            </div>
            <?php endif; ?>
            
            <!-- Tabs -->
            <div class="tabs-container">
                <button class="tab-button <?php echo $activeTab === 'login' ? 'active' : ''; ?>" onclick="switchTab('login')" type="button">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </button>
                <button class="tab-button <?php echo $activeTab === 'register' ? 'active' : ''; ?>" onclick="switchTab('register')" type="button">
                    <i class="bi bi-person-plus"></i> Criar Usuário
                </button>
            </div>
            
            <!-- Tab Content -->
            <div class="tab-content" id="loginTabsContent">
                <!-- Tab Login -->
                <div class="tab-pane <?php echo $activeTab === 'login' ? 'active' : ''; ?>" id="login" role="tabpanel">
                    <form action="login.php" method="post" class="fade-in">
                        <input type="hidden" name="action" value="login">
                        
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
                            <i class="bi bi-box-arrow-in-right"></i>
                            Entrar
                        </button>
                        
                        <?php if ($activeTab === 'login') echo $feedback; ?>
                    </form>
                </div>
                
                <!-- Tab Criar Usuário -->
                <div class="tab-pane <?php echo $activeTab === 'register' ? 'active' : ''; ?>" id="register" role="tabpanel">
                    <form action="login.php" method="post" class="fade-in" id="registerForm">
                        <input type="hidden" name="action" value="register">
                        
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="bi bi-person-badge"></i> Dados do Funcionário
                            </div>
                            
                            <div class="input-group-modern mb-3">
                                <i class="bi bi-person input-icon"></i>
                                <input 
                                    type="text" 
                                    class="form-control-modern" 
                                    id="nome" 
                                    name="nome" 
                                    placeholder="Nome Completo" 
                                    required
                                    value="<?php echo htmlspecialchars($formData['nome'] ?? ''); ?>"
                                >
                            </div>
                            
                            <div class="input-group-modern mb-3">
                                <i class="bi bi-credit-card input-icon"></i>
                                <input 
                                    type="text" 
                                    class="form-control-modern" 
                                    id="cpf" 
                                    name="cpf" 
                                    placeholder="CPF (apenas números)" 
                                    maxlength="11"
                                    required
                                    value="<?php echo htmlspecialchars($formData['cpf'] ?? ''); ?>"
                                >
                                <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">11 dígitos</small>
                            </div>
                            
                            <div class="input-group-modern mb-3">
                                <i class="bi bi-envelope input-icon"></i>
                                <input 
                                    type="email" 
                                    class="form-control-modern" 
                                    id="email" 
                                    name="email" 
                                    placeholder="Email" 
                                    required
                                    value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>"
                                >
                            </div>
                            
                            <div class="input-group-modern mb-3">
                                <i class="bi bi-briefcase input-icon"></i>
                                <select class="form-control-modern" id="cargo" name="cargo" required>
                                    <option value="">Selecione o Cargo</option>
                                    <?php echo $cargoOptions; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="bi bi-shield-lock"></i> Dados de Acesso
                            </div>
                            
                            <div class="input-group-modern mb-3">
                                <i class="bi bi-key input-icon"></i>
                                <input 
                                    type="password" 
                                    class="form-control-modern" 
                                    id="senha_register" 
                                    name="senha" 
                                    placeholder="Senha" 
                                    required
                                    minlength="6"
                                >
                            </div>
                            
                            <div class="input-group-modern mb-4">
                                <i class="bi bi-key-fill input-icon"></i>
                                <input 
                                    type="password" 
                                    class="form-control-modern" 
                                    id="confirmar_senha" 
                                    name="confirmar_senha" 
                                    placeholder="Confirmar Senha" 
                                    required
                                    minlength="6"
                                >
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-modern btn-modern-primary w-100">
                            <i class="bi bi-person-plus"></i>
                            Criar Usuário
                        </button>
                        
                        <?php if ($activeTab === 'register') echo $feedback; ?>
                    </form>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <a href="setup_admin.php" class="text-muted me-3" style="text-decoration: none; font-size: 0.9rem;">
                    <i class="bi bi-gear"></i> Configurar Admin
                </a>
                <a href="admin_login.php" class="text-muted" style="text-decoration: none; font-size: 0.9rem;">
                    <i class="bi bi-shield-lock"></i> Login Admin
                </a>
            </div>
            
            <div class="text-center mt-3">
                <small class="text-muted">
                    <i class="bi bi-shield-check"></i>
                    Sistema seguro de gestão
                </small>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Validação de CPF (apenas números)
        const cpfInput = document.getElementById('cpf');
        if (cpfInput) {
            cpfInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/\D/g, '');
                if (this.value.length > 11) {
                    this.value = this.value.substring(0, 11);
                }
            });
        }
        
        // Validação de confirmação de senha
        const senhaInput = document.getElementById('senha_register');
        const confirmarSenhaInput = document.getElementById('confirmar_senha');
        
        if (senhaInput && confirmarSenhaInput) {
            function validatePassword() {
                const senha = senhaInput.value;
                const confirmar = confirmarSenhaInput.value;
                
                if (confirmar && senha !== confirmar) {
                    confirmarSenhaInput.setCustomValidity('As senhas não coincidem');
                    confirmarSenhaInput.classList.add('is-invalid');
                } else {
                    confirmarSenhaInput.setCustomValidity('');
                    confirmarSenhaInput.classList.remove('is-invalid');
                }
            }
            
            senhaInput.addEventListener('input', validatePassword);
            confirmarSenhaInput.addEventListener('input', validatePassword);
        }
        
        // Função para trocar de aba
        function switchTab(tabName) {
            // Esconder todas as abas
            document.querySelectorAll('.tab-pane').forEach(pane => {
                pane.classList.remove('active');
            });
            
            // Remover active de todos os botões
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Mostrar aba selecionada
            const targetPane = document.getElementById(tabName);
            const targetButton = document.querySelector(`.tab-button[onclick*="${tabName}"]`);
            
            if (targetPane) {
                targetPane.classList.add('active');
            }
            
            if (targetButton) {
                targetButton.classList.add('active');
            }
        }
        
        // Ativar tab correta ao carregar
        <?php if ($activeTab === 'register'): ?>
        document.addEventListener('DOMContentLoaded', function() {
            switchTab('register');
        });
        <?php endif; ?>
        
        // Validar CPF antes de enviar
        const registerForm = document.getElementById('registerForm');
        if (registerForm) {
            registerForm.addEventListener('submit', function(e) {
                const cpf = cpfInput.value;
                if (cpf.length !== 11) {
                    e.preventDefault();
                    alert('CPF deve conter exatamente 11 dígitos');
                    cpfInput.focus();
                    return false;
                }
            });
        }
    </script>
</body>
</html>
