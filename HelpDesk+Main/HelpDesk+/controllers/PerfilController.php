<?php
require_once __DIR__ . '/../models/Funcionario.php';
require_once __DIR__ . '/../models/Conta.php';

class PerfilController {
    
    public static function index() {
        require_once __DIR__ . '/../require_login.php';
        require_once __DIR__ . '/../conexao.php';
        
        include __DIR__ . '/../views/perfil/index.php';
    }
    
    public static function editar() {
        require_once __DIR__ . '/../require_login.php';
        require_once __DIR__ . '/../conexao.php';
        require_once __DIR__ . '/../models/Cargo.php';
        
        $funcionarioId = $_SESSION['usuario'] ?? 0;
        $funcionario = Funcionario::buscarPorId($funcionarioId);
        
        if (!$funcionario) {
            header('Location: index.php?controller=PerfilController&action=index');
            exit;
        }
        
        $feedback = '';
        $cargos = Cargo::buscarTodos();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $funcionario->nome = trim($_POST['nome'] ?? '');
            $funcionario->email = trim($_POST['email'] ?? '');
            
            if (empty($funcionario->nome) || empty($funcionario->email)) {
                $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Preencha todos os campos obrigatórios.</div>';
            } else {
                // Verificar se email já existe em outro funcionário
                $existente = Funcionario::buscarPorEmail($funcionario->email);
                if ($existente && $existente->codigo != $funcionario->codigo) {
                    $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Email já cadastrado para outro funcionário.</div>';
                } else {
                    if ($funcionario->atualizar()) {
                        header('Location: index.php?controller=PerfilController&action=index&success=updated');
                        exit;
                    } else {
                        $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-x-circle"></i> Erro ao atualizar perfil.</div>';
                    }
                }
            }
        }
        
        include __DIR__ . '/../views/perfil/editar.php';
    }
    
    public static function alterarSenha() {
        require_once __DIR__ . '/../require_login.php';
        require_once __DIR__ . '/../conexao.php';
        
        $funcionarioId = $_SESSION['usuario'] ?? 0;
        $conta = Conta::buscarPorFuncionarioId($funcionarioId);
        
        if (!$conta) {
            header('Location: index.php?controller=PerfilController&action=index');
            exit;
        }
        
        $feedback = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $senhaAtual = $_POST['senha_atual'] ?? '';
            $novaSenha = $_POST['nova_senha'] ?? '';
            $confirmarSenha = $_POST['confirmar_senha'] ?? '';
            
            if (empty($senhaAtual) || empty($novaSenha) || empty($confirmarSenha)) {
                $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Preencha todos os campos.</div>';
            } elseif (!$conta->verificarSenha($senhaAtual)) {
                $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Senha atual incorreta.</div>';
            } elseif (strlen($novaSenha) < 6) {
                $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> A nova senha deve ter no mínimo 6 caracteres.</div>';
            } elseif ($novaSenha !== $confirmarSenha) {
                $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> As senhas não coincidem.</div>';
            } else {
                $conta->senha = password_hash($novaSenha, PASSWORD_DEFAULT);
                if ($conta->atualizar()) {
                    header('Location: index.php?controller=PerfilController&action=index&success=password_changed');
                    exit;
                } else {
                    $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-x-circle"></i> Erro ao alterar senha.</div>';
                }
            }
        }
        
        include __DIR__ . '/../views/perfil/alterarSenha.php';
    }
}

