<?php
require_once '../conexao.php';
header('Content-Type: application/json');
$dados = json_decode(file_get_contents('php://input'), true);
$payload = $dados['payload'];
//validação de dados
//if(!$dados || !isset($dados['id'])){ http_response_code(400); echo json_encode(['sucesso'=>false,'mensagem'=>'Requisição inválida']); exit; }
$id = (int)$payload['codigo'];
$alvo = selectWhere($dados['table'],["*"], "codigo = " . $id);
$alvo = mysqli_fetch_all($alvo, MYSQLI_ASSOC)[0]; 

$alteracoes = [];

foreach($alvo as $key => $value){
    if(isset($payload[$key])){
            
        if($payload[$key] != $value){
            
            $alteracoes[$key] = $payload[$key];
            
        }
        
    }
}

$teste = update($dados['table'], $alteracoes, 'codigo = ' . $id);

// fazer o select que encontra o registro a ser alterado
//fazer o update de forma que só mude partes que mudaram
//if(!$found){ http_response_code(404); echo json_encode(['sucesso'=>false,'mensagem'=>'ID não encontrado']); exit; }
//writeData($all);
//mysqli_fetch_all($alvo, MYSQLI_ASSOC)
echo json_encode(['sucesso'=>true,'mensagem'=> $teste]); ?>