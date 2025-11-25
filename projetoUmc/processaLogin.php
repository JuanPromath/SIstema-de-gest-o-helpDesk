<?php
    session_start();
    require_once 'conexao.php';
    if(!validaCampo('email') || !validaCampo('senha')){

        header('location: login.php');
        exit;

    }

    $result = selectInnerWhere(["Conta_Sistema", 'funcionario'], ['Conta_Sistema.codigo', 'Id_funcionario as funcionarioID', 'funcionario.email as email', 'senha'], 'email = "' . $_POST['email'] . '" and ' . 'senha = "' . $_POST['senha'] . '"');

    if(mysqli_num_rows($result) < 1){
        unset($_SESSION['user']);
        header('location: login.php?erro=1');
        exit;
    }

    $conta = mysqli_fetch_assoc($result);

    foreach($conta as $campo => $valor){
        $_SESSION[$campo] = $valor;    
    }
    $_SESSION['tipo_usuario'] = 'funcionario';
    header('location: index.php');
    exit;

?>