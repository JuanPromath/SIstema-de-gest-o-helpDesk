
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <h1>form criação cliente</h1>

    <form action="index.php" method="post">

        <input type="text" sizeof='50' id='nome' name="nome" placeholder="nome">
        <input type="text" sizeof='50' id='cpf' name="cpf" placeholder="cpf">
        <input type="email" placeholder="email" id="email" name="email">

        <button type="submit">enviar</button>

    </form>

    <h1>criacao cargo</h1>

    <form action="index.php" method="post">

        <input type="text" sizeof='50' id='cargo' name="cargo" placeholder="cargo">

        <button type="submit">enviar</button>

    </form>

    <h1>form criação funcionario</h1>

    <form action="index.php" method="post">

        <input type="text" sizeof='50' id='nomeF' name="nomeF" placeholder="nomeF">
        <input type="text" sizeof='50' id='cpfF' name="cpfF" placeholder="cpfF">
        <input type="email" placeholder="emailF" id="emailF" name="emailF">
        <input type="text" placeholder="cargoF" id="cargoF" name="cargoF">

        <button type="submit">enviar</button>

    </form>

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


<?php

    include 'conexao.php';

    function validaCampo($campo){

        if(!isset($_POST[$campo]) || empty($_POST[$campo])){
            return false;
        }

        return true;

    };

    if(!validaCampo('nome') && !validaCampo('email') && !validaCampo('cpf')){
        die('campos inválidos');
    }

    function insert($colunas, $valores){

        var_dump($colunas);
        var_dump($valores);
        for($i = 0; $i < sizeof($valores);$i++){

            echo $valores[$i];

        }

    }

    $consulta = "INSERT INTO Cliente (nome,cpf,email) VALUES('" . $_POST['nome'] . "','" . $_POST['cpf'] . "','" . $_POST['email'] . "')";
    //echo $consulta;

    insert(['nome', 'cpf', 'email'], $_POST);

    $resposta = mysqli_query($conn,$consulta);


?>