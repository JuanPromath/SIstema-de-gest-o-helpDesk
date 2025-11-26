<?php
    require '../verificaLogado.php';
    irparalogin('../login.php');
    verificaPermissao(['2','3'], '../forbbiden.php');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Cargo - HelpDesk+</title>
    <link rel="stylesheet" href="../assets/css/global.css">
</head>
<body>
    <nav>
        <div class="nav-container">
            <a href="../index.php" class="logo">HelpDesk</a>
            <div class="nav-links">
                <a href="../index.php">Dashboard</a>
                <a href="createChamado.php">Novo Chamado</a>
                <a href="createCliente.php">Novo Cliente</a>
                <a href="createFuncionario.php">Novo Funcionário</a>
                <a href="createCargo.php">Novo Cargo</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h1>Criar Novo Cargo</h1>
            <p>Adicione um novo cargo ao sistema</p>
        </div>

        <div class="form-container">
            <form action="createCargo.php" method="post">
                <div class="form-group">
                    <label for="cargo">Nome do Cargo</label>
                    <input type="text" id='cargo' name="nome" placeholder="Digite o nome do cargo" required>
                </div>

                <button type="submit" class="btn btn-primary">Cadastrar Cargo</button>
                <a href="../index.php" class="btn btn-secondary" style="margin-left: 1rem;">Cancelar</a>
            </form>
        </div>
    </div>
</body>
</html>

<?php

    include '../conexao.php';

    if(!validaCampo('nome')){
        die('campos inválidos');
    }

    insert(['nome'], $_POST, "Cargo");

?>