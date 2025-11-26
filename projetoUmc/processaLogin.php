<?php
    session_start();
    require_once 'conexao.php';
    if(!validaCampo('email') || !validaCampo('senha')){

        header('location: login.php');
        exit;

    }

    $result = selectInnerWhere(["Conta_Sistema", 'funcionario'], ['Conta_Sistema.codigo', 'Id_funcionario as funcionarioID', 'funcionario.email as email', 'senha', 'nivel_acesso'], 'email = "' . $_POST['email'] . '" and ' . 'senha = "' . $_POST['senha'] . '"');

    $conta = mysqli_fetch_assoc($result);

    if(mysqli_num_rows($result) < 1){

        foreach($conta as $campo => $valor){
            unset($_SESSION[$campo]);    
        }

        header('location: login.php?erro=1');
        exit;
    }

    foreach($conta as $campo => $valor){
        $_SESSION[$campo] = $valor;    
    }
    $_SESSION['tipo_usuario'] = 'funcionario';
    if($_SESSION['nivel_acesso'] == '1'){
        header('location: login.php?erro=3');
        exit;
    }

    if($_SESSION['nivel_acesso'] == '2'){
        header('location: create/createChamado.php');
    }else{
        header('location: create/ChamadosFunc.php');
    }
?>