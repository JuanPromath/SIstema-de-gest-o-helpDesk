<?php
require_once '../conexao.php';
header('Content-Type: application/json');
$dados = json_decode(file_get_contents('php://input'), true);
if(!isset($dados['id'])){ 
    http_response_code(400); echo json_encode(['sucesso'=>false,'mensagem'=>'ID não informado']); 
    exit; 
}

$result = selectWhere("cargo", ["*"], ["campo" => "codigo", "valor" => $dados['id']]);
$teste = delete("cargo","codigo = " . $dados['id']);
$result = mysqli_fetch_all($result, MYSQLI_ASSOC);

/*$id = (int)$dados['id'];
$all = readData();
$found = false;
foreach($all as $k=>$v){ 
    if(isset($v['id']) && (int)$v['id'] === $id){
        unset($all[$k]); $found=true; break;
    } 
}
if(!$found){
    http_response_code(404); 
    echo json_encode(['sucesso'=>false,'mensagem'=>'ID não encontrado']); 
    exit;
}*/
//writeData($all);
echo json_encode(['sucesso'=>true,'mensagem'=> $result]); ?>