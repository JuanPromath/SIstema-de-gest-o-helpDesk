<?php
    require_once '../conexao.php';
    header('Content-Type: application/json');
    $dados = json_decode(file_get_contents('php://input'), true);

    $result = '';

    if($dados['table'] == 'funcionario'){
        $result = select($dados['table'], ['funcionario.nome','funcionario.codigo','cargo.nome as cargo', 'id_cargo as cargoID','cpf', 'email']);
    }else if($dados['table'] == 'Conta_Sistema'){
        $result = selectInner(["Conta_Sistema", 'funcionario'], ['Conta_Sistema.codigo', 'funcionario.nome', 'funcionario.cpf', 'Id_funcionario as funcionarioID', 'funcionario.email', 'senha']);
    }else{
        $result = select($dados['table'], ["*"]);
    }

    $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $row = json_encode($row);
    echo $row;
?>