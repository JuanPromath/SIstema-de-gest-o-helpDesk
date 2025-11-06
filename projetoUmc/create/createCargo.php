
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav>

    <a href="../index.php">Home</a>

    </nav>

    <h1>criacao cargo</h1>

    <form action="createCargo.php" method="post">

        <input type="text"  id='cargo' name="cargo" placeholder="cargo">

        <button type="submit">enviar</button>

    </form>

</body>
</html>

<?php

    include '../conexao.php';

    if(!validaCampo('cargo')){
        die('campos inválidos');
    }

    insert(['nome'], $_POST, "Cargo");

?>