<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <nav>

    <a href="../index.php">Home</a>

    </nav>
    <h1>form criação chamado</h1>

    <form action="createChamado.php" method="post">

        <input type="text" id='bo' name="bo" placeholder="bo">
        <input type="text" id='status' name="status" placeholder="status">
        <input type="text" id="cliente" name="cliente" placeholder="cliente">
        <input type="text" id="func" name="func" placeholder="func">
        <input type="text" id="cargo" name="cargo" placeholder="cargo">

        <button type="submit">enviar</button>

    </form>


</body>
</html>

<?php

    if(!validaCampo('senha') && !validaCampo('funcionario')){
        die('campos inválidos');
    }

    insert(['senha', 'Id_funcionario'], $_POST, "Conta_Sistema");

?>