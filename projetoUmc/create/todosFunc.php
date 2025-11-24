<?php
include '../conexao.php';
header('Content-Type: application/json');

$result = select('funcionario',['*']);

if(mysqli_num_rows($result) < 0){
    echo json_encode(['code' => '404', 'res'=> 'nenhum funcionário registrado']);
    exit;
}

echo json_encode(['code' =>'200', 'res' => mysqli_fetch_all($result, MYSQLI_ASSOC)]);

?>