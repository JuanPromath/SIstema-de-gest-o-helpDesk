<?php
$protect = true;
if ($protect) require_once __DIR__ . '/../require_login.php';
require_once __DIR__ . '/../app/config/conexao.php';
header('Content-Type: application/json');

$dados = json_decode(file_get_contents('php://input'), true);

// Validação básica
if (!isset($dados['id']) || !isset($dados['table'])) {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'mensagem' => 'ID ou tabela não informados']);
    exit;
}

$id = (int)$dados['id'];
$table = preg_replace('/[^a-zA-Z0-9_]/', '', $dados['table']); // Evita SQL Injection no nome da tabela

// Verifica se o registro existe antes de deletar
$result = selectWhere($table, ["*"], "codigo = $id");
if (!$result || mysqli_num_rows($result) === 0) {
    http_response_code(404);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Registro não encontrado']);
    exit;
}
$registro = mysqli_fetch_assoc($result);

$delete = delete($table, "codigo = $id");
if ($delete) {
    echo json_encode(['sucesso' => true, 'mensagem' => 'Registro excluído com sucesso', 'registro' => $registro]);
} else {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao excluir registro']);
}
?>