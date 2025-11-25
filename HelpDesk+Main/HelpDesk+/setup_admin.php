<?php
/**
 * Script de Configuração do Administrador
 * Cria automaticamente um cargo Administrador, funcionário e conta de admin
 * 
 * Este script cria automaticamente o admin ao ser acessado
 */

require_once 'conexao.php';

$mensagem = '';
$sucesso = false;
$adminInfo = null;

// Executar criação automática
try {
    // 1. Criar cargo "Administrador" se não existir
    $cargoResult = selectWhere('Cargo', ['codigo'], "LOWER(nome) = 'administrador'");
    
    if (!$cargoResult || mysqli_num_rows($cargoResult) === 0) {
        $cargoAdmin = insert(['nome'], ['nome' => 'Administrador'], 'Cargo');
        if ($cargoAdmin) {
            $cargoResult = selectWhere('Cargo', ['codigo'], "LOWER(nome) = 'administrador'");
        }
    }
    
    if ($cargoResult && mysqli_num_rows($cargoResult) > 0) {
        $cargo = mysqli_fetch_assoc($cargoResult);
        $cargoId = $cargo['codigo'];
        
        // 2. Verificar se já existe funcionário admin
        $funcAdmin = selectWhere('Funcionario', ['codigo'], "LOWER(email) = 'admin@helpdesk.com'");
        
        $funcionarioId = null;
        
        if ($funcAdmin && mysqli_num_rows($funcAdmin) > 0) {
            $func = mysqli_fetch_assoc($funcAdmin);
            $funcionarioId = $func['codigo'];
            // Atualizar cargo se necessário
            update('Funcionario', ['id_cargo' => $cargoId], "codigo = $funcionarioId");
            $mensagem .= 'Funcionário administrador já existe. ';
        } else {
            // Criar funcionário administrador
            $dadosFunc = [
                'nome' => 'Administrador',
                'cpf' => '00000000000',
                'email' => 'admin@helpdesk.com',
                'id_cargo' => $cargoId
            ];
            
            $funcCriado = insert(['nome', 'cpf', 'email', 'id_cargo'], $dadosFunc, 'Funcionario');
            
            if ($funcCriado) {
                $funcResult = selectWhere('Funcionario', ['codigo'], "email = 'admin@helpdesk.com'");
                if ($funcResult && mysqli_num_rows($funcResult) > 0) {
                    $func = mysqli_fetch_assoc($funcResult);
                    $funcionarioId = $func['codigo'];
                    $mensagem .= 'Funcionário administrador criado. ';
                }
            }
        }
        
        // 3. Criar ou atualizar conta com senha "0000"
        if ($funcionarioId) {
            // Verificar se já existe conta
            $contaExist = selectWhere('Conta_Sistema', ['codigo'], "Id_funcionario = $funcionarioId");
            
            $senhaHash = password_hash('0000', PASSWORD_DEFAULT);
            
            if ($contaExist && mysqli_num_rows($contaExist) > 0) {
                // Atualizar senha
                $conta = mysqli_fetch_assoc($contaExist);
                update('Conta_Sistema', ['senha' => $senhaHash], "codigo = " . $conta['codigo']);
                $mensagem .= 'Conta atualizada com senha padrão. ';
            } else {
                // Criar nova conta
                $dadosConta = [
                    'senha' => $senhaHash,
                    'Id_funcionario' => $funcionarioId
                ];
                
                $contaCriada = insert(['senha', 'Id_funcionario'], $dadosConta, 'Conta_Sistema');
                
                if ($contaCriada) {
                    $mensagem .= 'Conta de administrador criada. ';
                }
            }
            
            $sucesso = true;
            $mensagem = '<div class="alert alert-success"><i class="bi bi-check-circle"></i> <strong>Sucesso!</strong> ' . $mensagem . '<br><br><strong>Credenciais:</strong><br>Username: <strong>admin</strong> (ou ID: ' . $funcionarioId . ')<br>Senha: <strong>0000</strong><br><br><a href="admin_login.php" class="btn-modern btn-modern-primary mt-2">Ir para Login Administrativo</a></div>';
        }
    } else {
        $mensagem = '<div class="alert alert-danger"><i class="bi bi-x-circle"></i> Erro ao criar cargo Administrador.</div>';
    }
} catch (Exception $e) {
    $mensagem = '<div class="alert alert-danger"><i class="bi bi-x-circle"></i> Erro: ' . htmlspecialchars($e->getMessage()) . '</div>';
}

// Buscar informações atuais do admin
$funcAdmin = selectWhere('Funcionario', ['codigo', 'nome', 'email'], "LOWER(email) = 'admin@helpdesk.com'");
if ($funcAdmin && mysqli_num_rows($funcAdmin) > 0) {
    $adminInfo = mysqli_fetch_assoc($funcAdmin);
    $contaAdmin = selectWhere('Conta_Sistema', ['codigo'], "Id_funcionario = " . $adminInfo['codigo']);
    $adminInfo['tem_conta'] = ($contaAdmin && mysqli_num_rows($contaAdmin) > 0);
    
    // Buscar cargo
    $cargoAdmin = selectWhere('Cargo', ['codigo', 'nome'], "codigo = (SELECT id_cargo FROM Funcionario WHERE codigo = " . $adminInfo['codigo'] . ")");
    if ($cargoAdmin && mysqli_num_rows($cargoAdmin) > 0) {
        $cargo = mysqli_fetch_assoc($cargoAdmin);
        $adminInfo['cargo'] = $cargo['nome'];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurar Administrador - HelpDesk+</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/animations.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box scale-in" style="max-width: 600px;">
            <div class="login-logo">
                <h1>
                    <i class="bi bi-gear"></i>
                    Configurar Admin
                </h1>
                <p>HelpDesk+</p>
            </div>
            
            <?php if ($mensagem): ?>
                <div class="fade-in">
                    <?php echo $mensagem; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($adminInfo): ?>
                <div class="card-modern mb-4 fade-in">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="bi bi-info-circle text-primary"></i>
                            Status do Administrador
                        </h5>
                        <div class="mb-2">
                            <strong>ID do Funcionário:</strong> #<?php echo $adminInfo['codigo']; ?>
                        </div>
                        <div class="mb-2">
                            <strong>Nome:</strong> <?php echo htmlspecialchars($adminInfo['nome']); ?>
                        </div>
                        <div class="mb-2">
                            <strong>Email:</strong> <?php echo htmlspecialchars($adminInfo['email']); ?>
                        </div>
                        <div class="mb-2">
                            <strong>Cargo:</strong> 
                            <span class="badge bg-primary"><?php echo htmlspecialchars($adminInfo['cargo'] ?? 'N/A'); ?></span>
                        </div>
                        <div class="mb-2">
                            <strong>Conta:</strong> 
                            <?php if ($adminInfo['tem_conta']): ?>
                                <span class="badge bg-success">Criada</span>
                            <?php else: ?>
                                <span class="badge bg-warning">Não criada</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="card-modern mb-4 fade-in">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-shield-check text-primary"></i>
                        Credenciais do Administrador
                    </h5>
                    <div class="alert alert-info">
                        <strong>Username:</strong> admin<br>
                        <strong>Senha:</strong> 0000<br>
                        <small class="text-muted">Use "admin" ou o ID do funcionário (#<?php echo $adminInfo['codigo'] ?? '?'; ?>) como usuário</small>
                    </div>
                </div>
            </div>
            
            <div class="d-grid gap-2">
                <a href="admin_login.php" class="btn-modern btn-modern-primary">
                    <i class="bi bi-shield-lock"></i>
                    Ir para Login Administrativo
                </a>
                <a href="login.php" class="btn-modern btn-modern-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Voltar para Login Normal
                </a>
            </div>
            
            <div class="alert alert-warning mt-4" style="font-size: 0.85rem;">
                <i class="bi bi-exclamation-triangle"></i>
                <strong>Segurança:</strong> Após confirmar que o login funciona, considere deletar este arquivo (setup_admin.php) por segurança.
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
