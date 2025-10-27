
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

    <h1>form criação funcionario</h1>

    <h1>form criação chamado</h1>

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

    $consulta = "INSERT INTO Cliente (nome,cpf,email) VALUES('" . $_POST['nome'] . "','" . $_POST['cpf'] . "','" . $_POST['email'] . "')";
    echo $consulta;

    $resposta = mysqli_query($conn,$consulta);

    if($resposta){
        $consulta2 = 'SELECT * FROM Cliente';
        $resposta2 = mysqli_query($conn,$consulta2);
        var_dump($resposta2);
    }else{
        echo mysqli_error($conn);
    }


?>