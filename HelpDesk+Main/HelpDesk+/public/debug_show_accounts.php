<?php
// debug_show_accounts.php
// Lista contas (Conta_Sistema) com dados do funcionário relacionado.
require_once __DIR__ . '/../app/config/conexao.php';

?><!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Debug - Contas</title>
    <style>body{font-family:Arial,Helvetica,sans-serif;padding:20px}table{border-collapse:collapse;width:100%}th,td{border:1px solid #ddd;padding:8px}th{background:#f4f4f4}</style>
</head>
<body>
<h2>Contas no banco</h2>
<p>Esta página mostra as contas existentes (hashes visíveis). Remova/exclua este arquivo após debugar.</p>
<?php
if (!$conn) {
    echo '<div style="color:red">Erro de conexão: verifique conexao.php</div>';
    exit;
}

$sql = "SELECT c.codigo AS conta_codigo, c.Id_funcionario, f.nome, f.email, c.senha AS senha_hash FROM Conta_Sistema c LEFT JOIN Funcionario f ON c.Id_funcionario = f.codigo ORDER BY c.codigo DESC";
$res = mysqli_query($conn, $sql);
if (!$res) {
    echo '<div style="color:red">Erro na query: ' . htmlspecialchars(mysqli_error($conn)) . '</div>';
    exit;
}

echo '<table><thead><tr><th>conta_codigo</th><th>Id_funcionario</th><th>nome</th><th>email</th><th>senha_hash</th></tr></thead><tbody>';
while ($row = mysqli_fetch_assoc($res)) {
    echo '<tr>';
    echo '<td>' . htmlspecialchars($row['conta_codigo']) . '</td>';
    echo '<td>' . htmlspecialchars($row['Id_funcionario']) . '</td>';
    echo '<td>' . htmlspecialchars($row['nome']) . '</td>';
    echo '<td>' . htmlspecialchars($row['email']) . '</td>';
    echo '<td><code>' . htmlspecialchars($row['senha_hash']) . '</code></td>';
    echo '</tr>';
}
echo '</tbody></table>';

?>

<h3>Testar login rápido</h3>
<form method="post" action="debug_test_login.php">
    <label>Usuário (ID ou email ou 'admin'):<br><input name="usuario" style="width:400px"></label><br><br>
    <label>Senha:<br><input name="senha" type="password" style="width:400px"></label><br><br>
    <button type="submit">Testar</button>
</form>

</body>
</html>
