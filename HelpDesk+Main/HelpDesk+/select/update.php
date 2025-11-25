
$protect = true;
if ($protect) require_once '../require_login.php';
<?php
require_once '../conexao.php';
header('Content-Type: application/json');
$dados = json_decode(file_get_contents('php://input'), true);
if (!isset($dados['table']) || !isset($dados['payload']['codigo'])) {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Requisição inválida']);
    exit;
}
$table = preg_replace('/[^a-zA-Z0-9_]/', '', $dados['table']);
$id = (int)$dados['payload']['codigo'];
$alvo = selectWhere($table, ["*"], "codigo = $id");
if (!$alvo || mysqli_num_rows($alvo) === 0) {
    http_response_code(404);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Registro não encontrado']);
    exit;
}
$alvo = mysqli_fetch_assoc($alvo);
$alteracoes = [];
foreach ($alvo as $key => $value) {
    if (isset($dados['payload'][$key]) && $dados['payload'][$key] != $value) {
        $alteracoes[$key] = $dados['payload'][$key];
    }
}
if (empty($alteracoes)) {
    echo json_encode(['sucesso' => true, 'mensagem' => 'Nenhuma alteração necessária.']);
    exit;
}
$ok = update($table, $alteracoes, "codigo = $id");
if ($ok) {
    echo json_encode(['sucesso' => true, 'mensagem' => 'Registro atualizado com sucesso.']);
} else {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao atualizar registro.']);
}
?>