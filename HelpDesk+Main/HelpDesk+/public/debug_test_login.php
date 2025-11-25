<?php
// debug_test_login.php - testa login usando a lógica do login.php
require_once __DIR__ . '/../app/config/conexao.php';

function findAccount($usuario) {
    global $conn;
    $usuario = trim($usuario);
    if (strtolower($usuario) === 'admin') $usuario = 'admin@helpdesk.com';

    if (ctype_digit($usuario)) {
        $sql = "SELECT c.*, f.nome, f.email FROM Conta_Sistema c LEFT JOIN Funcionario f ON c.Id_funcionario = f.codigo WHERE c.Id_funcionario = '" . mysqli_real_escape_string($conn,$usuario) . "' LIMIT 1";
    } else {
        $email = mysqli_real_escape_string($conn, $usuario);
        $sql = "SELECT c.*, f.nome, f.email FROM Conta_Sistema c LEFT JOIN Funcionario f ON c.Id_funcionario = f.codigo WHERE LOWER(f.email) = LOWER('" . $email . "') LIMIT 1";
    }
    $res = mysqli_query($conn, $sql);
    if (!$res) return ['error' => mysqli_error($conn)];
    return mysqli_fetch_assoc($res);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $acc = findAccount($usuario);
    header('Content-Type: text/html; charset=utf-8');
    echo '<!doctype html><html><head><meta charset="utf-8"><title>Resultado Teste Login</title></head><body style="font-family:Arial,Helvetica,sans-serif;padding:20px">';
    if (!$acc) {
        echo '<div style="color:red">Conta não encontrada para: ' . htmlspecialchars($usuario) . '</div>';
        echo '<p><a href="debug_show_accounts.php">Voltar</a></p>';
        exit;
    }
    echo '<h3>Conta encontrada</h3>';
    echo '<pre>' . htmlspecialchars(print_r($acc, true)) . '</pre>';

    // verificar senha
    if (isset($acc['senha'])) {
        $hash = $acc['senha'];
        if (password_verify($senha, $hash)) {
            echo '<div style="color:green;font-weight:bold">Senha correta — login OK</div>';
        } else {
            echo '<div style="color:red;font-weight:bold">Senha INCORRETA</div>';
            // detectar se parece ser hash do PHP
            if (strlen($hash) === 60 && preg_match('/^\$2[axy]\$\d{2}\$[.\/A-Za-z0-9]{53}$/', $hash)) {
                echo '<div>Hash parece ser do PHP (bcrypt).</div>';
            } else {
                echo '<div>Hash NÃO parece ser bcrypt do PHP. Pode ser texto simples ou outro algoritmo.</div>';
            }
        }
    } else {
        echo '<div style="color:red">Registro não tem campo senha.</div>';
    }
    echo '<p><a href="debug_show_accounts.php">Voltar</a></p>';
    echo '</body></html>';
    exit;
}

header('Location: debug_show_accounts.php');
