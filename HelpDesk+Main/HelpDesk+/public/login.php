<?php
session_start();
require_once __DIR__ . '/../app/config/conexao.php';

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

... (file continues)
