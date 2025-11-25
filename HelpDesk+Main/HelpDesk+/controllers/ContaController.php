<?php
require_once __DIR__ . '/../models/Conta.php';
require_once __DIR__ . '/../models/Funcionario.php';
require_once __DIR__ . '/../admin_functions.php';

class ContaController {
    
    public static function listar() {
        require_once __DIR__ . '/../require_login.php';
        require_once __DIR__ . '/../conexao.php';
        require_once __DIR__ . '/../admin_functions.php';
        
        // Apenas administradores podem gerenciar contas
        $admin = requireAdmin();
        
        $contas = Conta::buscarTodos();
        
        // Mensagens de feedback
        $successMsg = '';
        $errorMsg = '';
        if (isset($_GET['success'])) {
            if ($_GET['success'] === 'created') {
                $successMsg = '<div class="alert alert-success fade-in"><i class="bi bi-check-circle"></i> Conta criada com sucesso!</div>';
            } elseif ($_GET['success'] === 'updated') {
                $successMsg = '<div class="alert alert-success fade-in"><i class="bi bi-check-circle"></i> Conta atualizada com sucesso!</div>';
            } elseif ($_GET['success'] === 'deleted') {
                $successMsg = '<div class="alert alert-success fade-in"><i class="bi bi-check-circle"></i> Conta excluída com sucesso!</div>';
            }
        }
        if (isset($_GET['error'])) {
            if ($_GET['error'] === 'not_found') {
                $errorMsg = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Conta não encontrada.</div>';
            } elseif ($_GET['error'] === 'permission_denied') {
                $errorMsg = '<div class="alert alert-danger fade-in"><i class="bi bi-shield-x"></i> Você não tem permissão para acessar esta página.</div>';
            }
        }
        
        include __DIR__ . '/../views/conta/listar.php';
    }
    
    public static function criar() {
        require_once __DIR__ . '/../require_login.php';
        require_once __DIR__ . '/../admin_functions.php';
        require_once __DIR__ . '/../conexao.php';
        
        // Apenas administradores podem criar contas
        $admin = requireAdmin();
        
        $feedback = '';
        $funcionarios = Funcionario::buscarTodos();
        
        // Filtrar funcionários que já têm conta
        $funcionariosSemConta = [];
        foreach ($funcionarios as $func) {
            if (!Conta::buscarPorFuncionarioId($func->codigo)) {
                $funcionariosSemConta[] = $func;
            }
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $conta = new Conta();
            $conta->Id_funcionario = (int)($_POST['funcionario'] ?? 0);
            $senha = $_POST['senha'] ?? '';
            $confirmarSenha = $_POST['confirmar_senha'] ?? '';
            
            if ($conta->Id_funcionario <= 0 || empty($senha) || empty($confirmarSenha)) {
                $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Preencha todos os campos obrigatórios.</div>';
            } elseif ($senha !== $confirmarSenha) {
                $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> As senhas não coincidem.</div>';
            } elseif (strlen($senha) < 6) {
                $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> A senha deve ter no mínimo 6 caracteres.</div>';
            } else {
                // Verificar se o funcionário já tem conta
                if (Conta::buscarPorFuncionarioId($conta->Id_funcionario)) {
                    $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Este funcionário já possui uma conta cadastrada.</div>';
                } else {
                    $conta->senha = password_hash($senha, PASSWORD_DEFAULT);
                    
                    if ($conta->salvar()) {
                        header('Location: ?controller=ContaController&action=listar&success=created');
                        exit;
                    } else {
                        $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-x-circle"></i> Erro ao criar conta.</div>';
                    }
                }
            }
        }
        
        include __DIR__ . '/../views/conta/criar.php';
    }
    
    public static function editar() {
        require_once __DIR__ . '/../require_login.php';
        require_once __DIR__ . '/../admin_functions.php';
        require_once __DIR__ . '/../conexao.php';
        
        // Apenas administradores podem editar contas
        $admin = requireAdmin();
        
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $conta = Conta::buscarPorId($id);
        
        if (!$conta) {
            header('Location: ?controller=ContaController&action=listar&error=not_found');
            exit;
        }
        
        $feedback = '';
        $funcionario = Funcionario::buscarPorId($conta->Id_funcionario);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $senha = $_POST['senha'] ?? '';
            $confirmarSenha = $_POST['confirmar_senha'] ?? '';
            
            if (empty($senha) || empty($confirmarSenha)) {
                $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Preencha todos os campos obrigatórios.</div>';
            } elseif ($senha !== $confirmarSenha) {
                $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> As senhas não coincidem.</div>';
            } elseif (strlen($senha) < 6) {
                $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> A senha deve ter no mínimo 6 caracteres.</div>';
            } else {
                $conta->senha = password_hash($senha, PASSWORD_DEFAULT);
                
                if ($conta->atualizar()) {
                    header('Location: ?controller=ContaController&action=listar&success=updated');
                    exit;
                } else {
                    $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-x-circle"></i> Erro ao atualizar conta.</div>';
                }
            }
        }
        
        include __DIR__ . '/../views/conta/editar.php';
    }
    
    public static function excluir() {
        require_once __DIR__ . '/../require_login.php';
        require_once __DIR__ . '/../admin_functions.php';
        require_once __DIR__ . '/../conexao.php';
        
        // Apenas administradores podem excluir contas
        $admin = requireAdmin();
        
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $conta = Conta::buscarPorId($id);
        
        if (!$conta) {
            header('Location: ?controller=ContaController&action=listar&error=not_found');
            exit;
        }
        
        $feedback = '';
        $funcionario = Funcionario::buscarPorId($conta->Id_funcionario);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar']) && $_POST['confirmar'] === 'sim') {
            if ($conta->excluir()) {
                header('Location: ?controller=ContaController&action=listar&success=deleted');
                exit;
            } else {
                $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-x-circle"></i> Erro ao excluir conta.</div>';
            }
        }
        
        include __DIR__ . '/../views/conta/excluir.php';
    }
}

