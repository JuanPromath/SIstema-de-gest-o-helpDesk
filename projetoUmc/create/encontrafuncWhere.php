<?php
require '../conexao.php';
header('Content-Type: application/json');
$dados = json_decode(file_get_contents('php://input'), true);

$result = selectWhere('funcionario',["*"], 'id_cargo = "' . $dados['idCargo']. '"');

if(mysqli_num_rows($result) < 1){
    echo json_encode(['code' => '404','res' => 'não tem funcionários nesse cargo']);
    exit;
}

echo json_encode(['code' => '200', 'res' => mysqli_fetch_all($result,MYSQLI_ASSOC)]);

?>