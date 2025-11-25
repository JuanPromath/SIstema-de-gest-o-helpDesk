<?php
// Router melhorado para MVC
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Determinar controller e action
$controller = $_GET['controller'] ?? null;
$action = $_GET['action'] ?? 'listar';

// Mapear rotas antigas para novas (backward compatibility)
$routeMap = [
    'selectCliente.php' => ['controller' => 'ClienteController', 'action' => 'listar'],
    'createCliente.php' => ['controller' => 'ClienteController', 'action' => 'criar'],
    'editCliente.php' => ['controller' => 'ClienteController', 'action' => 'editar'],
    'deleteCliente.php' => ['controller' => 'ClienteController', 'action' => 'excluir'],
    
    'selectFuncionario.php' => ['controller' => 'FuncionarioController', 'action' => 'listar'],
    'createFuncionario.php' => ['controller' => 'FuncionarioController', 'action' => 'criar'],
    'editFuncionario.php' => ['controller' => 'FuncionarioController', 'action' => 'editar'],
    'deleteFuncionario.php' => ['controller' => 'FuncionarioController', 'action' => 'excluir'],
    
    'selectCargo.php' => ['controller' => 'CargoController', 'action' => 'listar'],
    'createCargo.php' => ['controller' => 'CargoController', 'action' => 'criar'],
    'editCargo.php' => ['controller' => 'CargoController', 'action' => 'editar'],
    'deleteCargo.php' => ['controller' => 'CargoController', 'action' => 'excluir'],
    
    'selectChamado.php' => ['controller' => 'ChamadoController', 'action' => 'listar'],
    'createChamado.php' => ['controller' => 'ChamadoController', 'action' => 'criar'],
    'editChamado.php' => ['controller' => 'ChamadoController', 'action' => 'editar'],
    'deleteChamado.php' => ['controller' => 'ChamadoController', 'action' => 'excluir'],
    
    'selectConta.php' => ['controller' => 'ContaController', 'action' => 'listar'],
    'createConta.php' => ['controller' => 'ContaController', 'action' => 'criar'],
    
    'perfil.php' => ['controller' => 'PerfilController', 'action' => 'index'],
];

// Verificar se é uma rota antiga
$scriptName = basename($_SERVER['PHP_SELF']);
if (!$controller && isset($routeMap[$scriptName])) {
    $controller = $routeMap[$scriptName]['controller'];
    $action = $routeMap[$scriptName]['action'];
}

// Se não tiver controller, não é uma rota MVC válida
if (!$controller) {
    http_response_code(404);
    die("Página não encontrada. Use o formato: index.php?controller=XController&action=Y");
}

// Carregar controller
$controllerFile = __DIR__ . '/controllers/' . $controller . '.php';
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    
    if (class_exists($controller) && method_exists($controller, $action)) {
        $controller::$action();
    } else {
        http_response_code(404);
        die("Action não encontrado: $controller::$action");
    }
} else {
    http_response_code(404);
    die("Controller não encontrado: $controller");
}
