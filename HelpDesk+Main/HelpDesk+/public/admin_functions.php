<?php
/**
 * Funções de Administrador
 * Verifica permissões e identifica administradores
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../app/config/conexao.php';

/**
 * Verifica se o usuário logado é administrador
 * @return bool|array Retorna false se não for admin, ou array com dados do funcionário se for
 */
function isAdmin() {
    if (!isset($_SESSION['usuario'])) {
        return false;
    }
    
    global $conn;
    $funcionarioId = $_SESSION['usuario'];
    
    // Buscar funcionário com seu cargo
    $query = "SELECT f.codigo, f.nome, f.email, c.nome as cargo_nome, c.codigo as cargo_id 
              FROM Funcionario f 
              INNER JOIN Cargo c ON f.id_cargo = c.codigo 
              WHERE f.codigo = " . (int)$funcionarioId;
    
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $funcionario = mysqli_fetch_assoc($result);
        // Verificar se o cargo é "Administrador" (case insensitive)
        if (strtolower(trim($funcionario['cargo_nome'])) === 'administrador') {
            return $funcionario;
        }
    }
    
    return false;
}

/**
 * Requer que o usuário seja administrador
 * Redireciona para login se não for
 */
function requireAdmin() {
    if (!isset($_SESSION['usuario'])) {
        header('Location: login.php?redirect=admin');
        exit;
    }
    
    $admin = isAdmin();
    if (!$admin) {
        header('Location: index.php?error=permission_denied');
        exit;
    }
    
    return $admin;
}

/**
 * Verifica se o usuário tem permissão para gerenciar cargos
 */
function canManageCargos() {
    return isAdmin() !== false;
}

/**
 * Verifica se o usuário tem permissão para gerenciar funcionários
 */
function canManageFuncionarios() {
    return isAdmin() !== false;
}
