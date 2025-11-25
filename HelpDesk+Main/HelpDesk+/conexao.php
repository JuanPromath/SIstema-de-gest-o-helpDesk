
<?php

$servername = getenv('DB_HOST') ?: 'localhost';
$database   = getenv('DB_NAME') ?: 'HelpDeskMais';
$username   = getenv('DB_USER') ?: 'root';
$password   = getenv('DB_PASS') ?: '';

$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
    error_log("Erro ao conectar ao banco de dados: " . mysqli_connect_error());
    http_response_code(500);
    die("Erro ao conectar ao banco de dados. Por favor, tente novamente mais tarde.");
}

mysqli_set_charset($conn, 'utf8mb4');


function validaCampo($campo) {
    return isset($_POST[$campo]) && !empty(trim($_POST[$campo]));
}


function update($table, $alteracoes, $condition) {
    global $conn;
    $set = [];
    foreach ($alteracoes as $key => $value) {
        $set[] = $key . " = '" . mysqli_real_escape_string($conn, $value) . "'";
    }
    $query = "UPDATE $table SET " . implode(", ", $set) . " WHERE $condition";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        error_log("Erro no update: " . mysqli_error($conn));
    }
    return $result;
}


function select($table, $campos = ["*"]) {
    global $conn;
    $select = "SELECT " . implode(", ", $campos) . " FROM $table";
    $result = mysqli_query($conn, $select);
    if (!$result) {
        error_log("Erro no select: " . mysqli_error($conn));
    }
    return $result;
}


function selectWhere($table, $campos = ["*"], $condition) {
    global $conn;
    $select = "SELECT " . implode(", ", $campos) . " FROM $table WHERE $condition";
    $result = mysqli_query($conn, $select);
    if (!$result) {
        error_log("Erro no selectWhere: " . mysqli_error($conn));
    }
    return $result;
}



function delete($table, $condition) {
    global $conn;
    $query = "DELETE FROM $table WHERE $condition";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        error_log("Erro no delete: " . mysqli_error($conn));
    }
    return $result;
}


function selectInner($tables, $campos = ["*"]) {
    global $conn;
    if (empty($tables) || !is_array($tables) || count($tables) < 2) {
        return false;
    }
    $select = "SELECT " . implode(", ", $campos) . " FROM " . $tables[0];
    for ($i = 0; $i < count($tables) - 1; $i++) {
        $next = $i + 1;
        $select .= ' INNER JOIN ' . $tables[$next] . ' ON ' . $tables[$next] . '.codigo = ' . $tables[$i] . '.id_' . strtolower($tables[$next]);
    }
    $result = mysqli_query($conn, $select);
    if (!$result) {
        error_log("Erro no selectInner: " . mysqli_error($conn));
    }
    return $result;
}


function insert($campos, $valores, $table) {
    global $conn;
    $cols = [];
    $vals = [];
    foreach ($campos as $campo) {
        $cols[] = $campo;
        $valor = isset($valores[$campo]) ? $valores[$campo] : '';
        $vals[] = "'" . mysqli_real_escape_string($conn, $valor) . "'";
    }
    $insert = "INSERT INTO $table (" . implode(", ", $cols) . ") VALUES (" . implode(", ", $vals) . ")";
    $resposta = mysqli_query($conn, $insert);
    if (!$resposta) {
        error_log("Erro no insert: " . mysqli_error($conn));
    }
    return $resposta;
}

?>