<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <nav>
        <a href="../index.php" rel="prev">Home</a>
    </nav>

    <h1>form criação cliente</h1>

    <form action="createCliente.php" method="post">

        <input type="text" sizeof='50' id='nome' name="nome" placeholder="nome">
        <input type="text" sizeof='50' id='cpf' name="cpf" placeholder="cpf">
        <input type="email" placeholder="email" id="email" name="email">

        <button type="submit">enviar</button>

    </form>

</body>
</html>

<?php

    include '../conexao.php';

    if(!validaCampo('nome') && !validaCampo('email') && !validaCampo('cpf')){
        die('campos inválidos');
    }

    insert(['nome', 'cpf', 'email'], $_POST, "Cliente");

?>