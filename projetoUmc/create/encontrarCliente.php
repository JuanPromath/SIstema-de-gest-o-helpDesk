<?php
    require_once '../conexao.php';
    header('Content-Type: application/json');
    $dados = json_decode(file_get_contents('php://input'), true);

    $result = selectWhere('cliente',["*"], 'cpf = "' . $dados['cpf'] . '"');

    if(mysqli_num_rows($result) < 1){
        echo json_encode(['code' => '404','res' => 'Cliente não encontrado']);
        exit;
    }

    echo json_encode(['code' => '200', 'res' => mysqli_fetch_assoc($result)]);


?>