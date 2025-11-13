
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styleFuncionario.css">
</head>
<body>

    <nav>

    <a href="../index.php">Home</a>

    </nav>
    <main class="container">
        <h1>criacao cargo</h1>

        <form action="createCargo.php" method="post" class="form-box">

            <input type="text" id='cargo' name="nome" placeholder="cargo">

            <button type="submit">enviar</button>

        </form>
    </main>

</body>
</html>

<?php

    include '../conexao.php';

    if(!validaCampo('nome')){
        die('campos inválidos');
    }

    insert(['nome'], $_POST, "Cargo");

?>