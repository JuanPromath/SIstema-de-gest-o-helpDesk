
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <nav>

    <a href="create/createCargo.php">Cargo</a>
    <a href="create/createCliente.php">Cliente</a>
    <a href="create/createChamado.php">Chamado</a>
    <a href="create/createFuncionario.php">Funcionario</a>

    </nav>

    <h1>conta</h1>
    <form action="index.php" method="post">

        <input type="text" sizeof='50' id='senha' name="senha" placeholder="senha">
        <input type="text" placeholder="funcionario" id="funcionario" name="funcionario">

        <button type="submit">enviar</button>

    </form>

    <h1>form criação chamado</h1>

    <form action="index.php" method="post">

        <input type="text" sizeof='50' id='bo' name="bo" placeholder="bo">
        <input type="text" sizeof='50' id='status' name="status" placeholder="status">
        <input type="text" id="cliente" name="cliente" placeholder="cliente">
        <input type="text" id="func" name="func" placeholder="func">
        <input type="text" id="conta" name="conta" placeholder="conta">
        <input type="text" id="cargo" name="cargo" placeholder="cargo">

        <button type="submit">enviar</button>

    </form>

</body>
</html>