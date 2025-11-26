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
    <title>Criar Conta - HelpDesk+</title>
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
                <a href="createConta.php">Nova Conta</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h1>Criar Nova Conta</h1>
            <p>Cadastre uma nova conta de acesso ao sistema</p>
        </div>

        <div class="form-container">
            <form action="createConta.php" method="post">
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" id='senha' name="senha" placeholder="Digite a senha" required>
                    <label for="senha">Nivel de acesso</label>
                    <select name="nivel_acesso" id="nivel_acesso">
                        <option value="1">Administrador</option>
                        <option value="2">atendente</option>
                        <option value="3">funcionario</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="funcionario">Funcionário</label>
                    <select name="funcionario" id="funcionario" required>
                        <option value="">Selecione o funcionário</option>
                        <?php
                            include '../conexao.php';
                            $result = select("funcionario", ['funcionario.codigo', 'funcionario.nome']);

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='" . $row['codigo']."'>" . $row['nome'] . "</option>";
                                }
                            } else {
                                echo "<option value=''>Nenhum funcionário disponível</option>";
                            }
                        ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Cadastrar Conta</button>
                <a href="../index.php" class="btn btn-secondary" style="margin-left: 1rem;">Cancelar</a>
            </form>
        </div>
    </div>
</body>
</html>

<?php

    if(!validaCampo('senha') && !validaCampo('funcionario')){
        die('campos inválidos');
    }

    insert(['senha', 'nivel_acesso','Id_funcionario'], $_POST, "Conta_Sistema");

?>