<?php
// debug_db_check.php
// Verifica conexão e presença de tabelas/contagens úteis.
require_once 'conexao.php';
header('Content-Type: text/plain; charset=utf-8');

echo "Debug DB Check\n";

// Verificar conexão
if (!$conn) {
    echo "Conexão inválida. Verifique credenciais em conexao.php\n";
    exit;
}

echo "Conectado ao DB.\n";

$expectedTables = [
    'Cargo', 'Funcionario', 'Conta_Sistema', 'Cliente', 'Chamado'
];

foreach ($expectedTables as $t) {
    $tEsc = mysqli_real_escape_string($conn, $t);
    $res = mysqli_query($conn, "SHOW TABLES LIKE '" . $tEsc . "'");
    $exists = ($res && mysqli_num_rows($res) > 0) ? 'FOUND' : 'MISSING';
    echo "Tabela $t: $exists\n";
}

// Contagens rápidas
$tablesToCount = ['Cargo','Funcionario','Conta_Sistema','Cliente','Chamado'];
foreach ($tablesToCount as $t) {
    $q = @mysqli_query($conn, "SELECT COUNT(*) AS c FROM $t");
    if ($q) {
        $r = mysqli_fetch_assoc($q);
        echo "Count $t: " . ($r['c'] ?? 0) . "\n";
    } else {
        echo "Count $t: ERROR (" . mysqli_error($conn) . ")\n";
    }
}

// Mostrar estrutura mínima de Conta_Sistema (colunas)
$colsQ = mysqli_query($conn, "SHOW COLUMNS FROM Conta_Sistema");
if ($colsQ) {
    echo "\nColunas da tabela Conta_Sistema:\n";
    while ($col = mysqli_fetch_assoc($colsQ)) {
        echo " - " . $col['Field'] . " (" . $col['Type'] . ")" . ($col['Null'] === 'NO' ? ' NOT NULL' : '') . "\n";
    }
} else {
    echo "\nNão foi possível obter colunas de Conta_Sistema: " . mysqli_error($conn) . "\n";
}

// Exibir versão do MySQL e lower_case_table_names
$res = mysqli_query($conn, "SELECT @@lower_case_table_names as lct, VERSION() as v");
if ($res) {
    $row = mysqli_fetch_assoc($res);
    echo "\nMySQL version: " . $row['v'] . "\n";
    echo "lower_case_table_names: " . $row['lct'] . "\n";
}

echo "\nScript debug finalizado.\n";
