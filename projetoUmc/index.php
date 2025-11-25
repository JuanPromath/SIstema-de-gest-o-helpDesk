<?php
    session_start();
    require_once 'conexao.php';
    
    // Verificar se o usuário está logado
    if(!isset($_SESSION['codigo']) || !isset($_SESSION['email'])) {
        header('location: login.php');
        exit;
    }
    
    // Contar registros para o dashboard
    $totalChamados = mysqli_num_rows(select("chamado"));
    $totalClientes = mysqli_num_rows(select("cliente"));
    $totalFuncionarios = mysqli_num_rows(select("funcionario"));
    $totalCargos = mysqli_num_rows(select("cargo"));
    
    // Obter nome do funcionário logado
    $nomeUsuario = isset($_SESSION['funcionarioID']) ? '' : 'Usuário';
    if(isset($_SESSION['funcionarioID'])) {
        $funcResult = selectWhere("funcionario", ["nome"], "codigo = " . $_SESSION['funcionarioID']);
        if($funcResult && mysqli_num_rows($funcResult) > 0) {
            $funcData = mysqli_fetch_assoc($funcResult);
            $nomeUsuario = $funcData['nome'];
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - HelpDesk+</title>
    <link rel="stylesheet" href="assets/css/global.css">
</head>
<body>
    <nav>
        <div class="nav-container">
            <a href="index.php" class="logo">HelpDesk</a>
            <div class="nav-links">
                <a href="index.php">Dashboard</a>
                <a href="create/createChamado.php">Novo Chamado</a>
                <a href="create/createCliente.php">Novo Cliente</a>
                <a href="create/createFuncionario.php">Novo Funcionário</a>
                <a href="select/selectChamado.php">Chamados</a>
                <a href="select/selectCliente.php">Clientes</a>
                <a href="select/selectFuncionario.php">Funcionários</a>
                <a href="select/selectCargo.php">Cargos</a>
            </div>
            <div class="user-info"><?php echo htmlspecialchars($nomeUsuario); ?></div>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h1>Dashboard</h1>
            <p>Bem-vindo ao sistema HelpDesk+</p>
        </div>

        <div class="dashboard">
            <div class="dashboard-card">
                <h3>Total de Chamados</h3>
                <div class="number"><?php echo $totalChamados; ?></div>
                <a href="select/selectChamado.php" class="link">Ver todos →</a>
            </div>
            
            <div class="dashboard-card">
                <h3>Total de Clientes</h3>
                <div class="number"><?php echo $totalClientes; ?></div>
                <a href="select/selectCliente.php" class="link">Ver todos →</a>
            </div>
            
            <div class="dashboard-card">
                <h3>Total de Funcionários</h3>
                <div class="number"><?php echo $totalFuncionarios; ?></div>
                <a href="select/selectFuncionario.php" class="link">Ver todos →</a>
            </div>
            
            <div class="dashboard-card">
                <h3>Total de Cargos</h3>
                <div class="number"><?php echo $totalCargos; ?></div>
                <a href="select/selectCargo.php" class="link">Ver todos →</a>
            </div>
        </div>

        <div class="card">
            <h2>Ações Rápidas</h2>
            <div class="grid">
                <a href="create/createChamado.php" class="btn btn-primary" style="text-align: center; display: block;">Criar Chamado</a>
                <a href="create/createCliente.php" class="btn btn-primary" style="text-align: center; display: block;">Criar Cliente</a>
                <a href="create/createFuncionario.php" class="btn btn-primary" style="text-align: center; display: block;">Criar Funcionário</a>
                <a href="create/createCargo.php" class="btn btn-primary" style="text-align: center; display: block;">Criar Cargo</a>
                <a href="create/createConta.php" class="btn btn-primary" style="text-align: center; display: block;">Criar Conta</a>
                <a href="create/createChamadoAdm.php" class="btn btn-secondary" style="text-align: center; display: block;">Criar Chamado (Admin)</a>
            </div>
        </div>
    </div>
</body>
</html>