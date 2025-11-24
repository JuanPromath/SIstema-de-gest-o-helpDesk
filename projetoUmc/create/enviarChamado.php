<?php
    session_start();
    include_once '../conexao.php';
    header('Content-Type: application/json');
    $dados = json_decode(file_get_contents('php://input'), true);
    //print_r($dados);

    $conta = ['id_conta' => $_SESSION['codigo'], 'status' => 'aberto'];

    $cliente = selectWhere('cliente',["codigo"], 'cpf = "' . $dados['cliente'] . '"');

    $dados['cliente'] = mysqli_fetch_assoc($cliente)["codigo"];

    $result = array_merge($dados, $conta);
    echo json_encode($result);

    $result = insert(['bo', 'Id_cliente', 'Id_cargo','Id_funcionario', 'Id_conta','status'], $result, "Chamado");

?>