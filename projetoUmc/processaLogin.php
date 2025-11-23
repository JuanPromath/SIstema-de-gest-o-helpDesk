<?php
    require_once 'conexao.php';
    if(!validaCampo('email') && !validaCampo('senha')){

        header('location: login.php');

    };

    $result = selectInner(["Conta_Sistema", 'funcionario'], ['Conta_Sistema.codigo', 'Id_funcionario as funcionarioID', 'funcionario.email', 'senha']);



?>