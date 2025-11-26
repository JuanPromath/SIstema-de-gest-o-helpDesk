<?php
    session_start();
    require_once 'conexao.php';
    
    if(!validaCampo('email') || !validaCampo('senha')){
        header('location: login.php');
        exit;
    }

    // Buscar conta do administrador
    // Administrador é identificado por ter um cargo específico ou por ter acesso especial
    // Vamos verificar se o funcionário tem cargo de "Administrador" ou similar
    /*$result = selectInnerWhere(
        ["Conta_Sistema", 'funcionario', 'cargo'], 
        [
            'Conta_Sistema.codigo', 
            'Id_funcionario as funcionarioID', 
            'funcionario.email as email', 
            'senha',
            'cargo.nome as cargo_nome'
        ], 
        'email = "' . $_POST['email'] . '" and senha = "' . $_POST['senha'] . '"'
    );*/

    $result = selectInnerWhere(
        ["Conta_Sistema", 'funcionario'], 
        ['Conta_Sistema.codigo', 
        'Id_funcionario as funcionarioID', 
        'funcionario.email as email', 
        'senha', 
        'nivel_acesso'], 
        'email = "' . $_POST['email'] . '" and ' . 'senha = "' . $_POST['senha'] . '"'
    );

    if(mysqli_num_rows($result) < 1){
        foreach($conta as $campo => $valor){
            unset($_SESSION[$campo]);    
        }
        header('location: login.php?erro=1');
        exit;
    }

    $conta = mysqli_fetch_assoc($result);
    
    // Verificar se é administrador (cargo contém "admin" ou "administrador" ou é um cargo específico)
    /*$cargoNome = strtolower($conta['cargo_nome']);
    $isAdmin = (
        strpos($cargoNome, 'admin') !== false || 
        strpos($cargoNome, 'administrador') !== false ||
        strpos($cargoNome, 'gerente') !== false ||
        strpos($cargoNome, 'diretor') !== false
    );*/
    $isAdmin = $conta['nivel_acesso'] == '1';
    
    // Se não for admin, redirecionar para login normal
    if(!$isAdmin) {
        foreach($conta as $campo => $valor){
            unset($_SESSION[$campo]);    
        }
        header('location: login.php?erro=2');
        exit;
    }

    // Salvar dados na sessão
    foreach($conta as $campo => $valor){
        $_SESSION[$campo] = $valor;    
    }
    $_SESSION['is_admin'] = true;
    $_SESSION['tipo_usuario'] = 'administrador';
    
    //header('location: index.php');
    exit;
?>

