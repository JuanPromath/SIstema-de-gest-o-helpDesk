<?php
$protect = true;
if ($protect) require_once __DIR__ . '/../require_login.php';
require_once __DIR__ . '/../app/config/conexao.php';
header('Content-Type: application/json');
$dados = json_decode(file_get_contents('php://input'), true);

if (!isset($dados['table'])) {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Tabela não informada']);
    exit;
}

$table = preg_replace('/[^a-zA-Z0-9_]/', '', $dados['table']); // Sanitiza nome da tabela
$result = null;
$extra = null;

switch (strtolower($table)) {
    case 'funcionario':
        $result = selectInner(['funcionario', 'cargo'], [
            'funcionario.nome',
            'funcionario.codigo',
            'cargo.nome as cargo',
            'id_cargo as cargoID',
            'cpf',
            'email'
        ]);
        break;
    case 'conta_sistema':
        $result = selectInner(['Conta_Sistema', 'funcionario'], [
            'Conta_Sistema.codigo',
            'funcionario.nome',
            'funcionario.cpf',
            'Id_funcionario as funcionarioID',
            'funcionario.email',
            'senha'
        ]);
        break;
    case 'chamado':
        $result = selectInner(['Chamado', 'Cliente', 'Conta_Sistema', 'Funcionario', 'Cargo'], [
            'Chamado.codigo',
            'Chamado.bo',
            'Chamado.status',
            'Chamado.data_abertura',
            'Cliente.nome as nome_cliente',
            'Cliente.cpf as cpf_cliente',
            'Funcionario.nome as nome_funcionario',
            'Cargo.nome as cargo',
            'Conta_Sistema.Id_funcionario as atendenteId'
        ]);
        break;
    default:
        $result = select($table, ["*"]);
        break;
}

if (!$result) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao buscar dados']);
    exit;
}

$dados_result = mysqli_fetch_all($result, MYSQLI_ASSOC);
echo json_encode(['sucesso' => true, 'dados' => $dados_result]);
?>