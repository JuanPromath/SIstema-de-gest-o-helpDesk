<?php
require_once __DIR__ . '/../models/Cargo.php';
require_once __DIR__ . '/../admin_functions.php';

class CargoController {
    
    public static function listar() {
        require_once __DIR__ . '/../require_login.php';
        require_once __DIR__ . '/../conexao.php';
        require_once __DIR__ . '/../admin_functions.php';
        
        $cargos = Cargo::buscarTodos();
        $isAdmin = isAdmin();
        
        // Mensagens de feedback
        $successMsg = '';
        $errorMsg = '';
        if (isset($_GET['success'])) {
            if ($_GET['success'] === 'created') {
                $successMsg = '<div class="alert alert-success fade-in"><i class="bi bi-check-circle"></i> Cargo cadastrado com sucesso!</div>';
            } elseif ($_GET['success'] === 'updated') {
                $successMsg = '<div class="alert alert-success fade-in"><i class="bi bi-check-circle"></i> Cargo atualizado com sucesso!</div>';
            } elseif ($_GET['success'] === 'deleted') {
                $successMsg = '<div class="alert alert-success fade-in"><i class="bi bi-check-circle"></i> Cargo excluído com sucesso!</div>';
            }
        }
        if (isset($_GET['error'])) {
            if ($_GET['error'] === 'not_found') {
                $errorMsg = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Cargo não encontrado.</div>';
            } elseif ($_GET['error'] === 'permission_denied') {
                $errorMsg = '<div class="alert alert-danger fade-in"><i class="bi bi-shield-x"></i> Você não tem permissão para acessar esta página.</div>';
            }
        }
        
        include __DIR__ . '/../views/cargo/listar.php';
    }
    
    public static function criar() {
        require_once __DIR__ . '/../require_login.php';
        require_once __DIR__ . '/../admin_functions.php';
        require_once __DIR__ . '/../conexao.php';
        
        $admin = requireAdmin();
        $feedback = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cargo = new Cargo();
            $cargo->nome = trim($_POST['nome'] ?? '');
            
            if (empty($cargo->nome)) {
                $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> O nome do cargo é obrigatório.</div>';
            } else {
                // Verificar duplicatas
                if (Cargo::buscarPorNome($cargo->nome)) {
                    $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Já existe um cargo com este nome.</div>';
                } else {
                    if ($cargo->salvar()) {
                        header('Location: ?controller=CargoController&action=listar&success=created');
                        exit;
                    } else {
                        $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-x-circle"></i> Erro ao cadastrar cargo.</div>';
                    }
                }
            }
        }
        
        include __DIR__ . '/../views/cargo/criar.php';
    }
    
    public static function editar() {
        require_once __DIR__ . '/../require_login.php';
        require_once __DIR__ . '/../admin_functions.php';
        require_once __DIR__ . '/../conexao.php';
        
        $admin = requireAdmin();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $cargo = Cargo::buscarPorId($id);
        
        if (!$cargo) {
            header('Location: ?controller=CargoController&action=listar&error=not_found');
            exit;
        }
        
        $feedback = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cargo->nome = trim($_POST['nome'] ?? '');
            
            if (empty($cargo->nome)) {
                $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> O nome do cargo é obrigatório.</div>';
            } else {
                // Verificar duplicatas em outros cargos
                $existente = Cargo::buscarPorNome($cargo->nome);
                if ($existente && $existente->codigo != $cargo->codigo) {
                    $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Já existe um cargo com este nome.</div>';
                } else {
                    if ($cargo->atualizar()) {
                        header('Location: ?controller=CargoController&action=listar&success=updated');
                        exit;
                    } else {
                        $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-x-circle"></i> Erro ao atualizar cargo.</div>';
                    }
                }
            }
        }
        
        include __DIR__ . '/../views/cargo/editar.php';
    }
    
    public static function excluir() {
        require_once __DIR__ . '/../require_login.php';
        require_once __DIR__ . '/../admin_functions.php';
        require_once __DIR__ . '/../conexao.php';
        
        $admin = requireAdmin();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $cargo = Cargo::buscarPorId($id);
        
        if (!$cargo) {
            header('Location: ?controller=CargoController&action=listar&error=not_found');
            exit;
        }
        
        $feedback = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar']) && $_POST['confirmar'] === 'sim') {
            if ($cargo->temFuncionarios()) {
                $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Não é possível excluir este cargo pois existem funcionários vinculados a ele.</div>';
            } else {
                if ($cargo->excluir()) {
                    header('Location: ?controller=CargoController&action=listar&success=deleted');
                    exit;
                } else {
                    $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-x-circle"></i> Erro ao excluir cargo.</div>';
                }
            }
        }
        
        include __DIR__ . '/../views/cargo/excluir.php';
    }
}

