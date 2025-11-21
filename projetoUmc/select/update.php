<?php
require_once '../conexao.php';
header('Content-Type: application/json');
$dados = json_decode(file_get_contents('php://input'), true);

//validação de dados
//if(!$dados || !isset($dados['id'])){ http_response_code(400); echo json_encode(['sucesso'=>false,'mensagem'=>'Requisição inválida']); exit; }
$id = (int)$dados['id'];
$alvo = selectWhere('cargo',["*"], "codigo = " . $id);


// fazer o select que encontra o registro a ser alterado
//fazer o update de forma que só mude partes que mudaram
//if(!$found){ http_response_code(404); echo json_encode(['sucesso'=>false,'mensagem'=>'ID não encontrado']); exit; }
//writeData($all);
echo json_encode(['sucesso'=>true,'mensagem'=> mysqli_fetch_all($alvo, MYSQLI_ASSOC)]); ?>