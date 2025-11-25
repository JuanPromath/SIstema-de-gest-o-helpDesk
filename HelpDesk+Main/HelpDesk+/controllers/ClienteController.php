<?php
require_once __DIR__ . '/../models/Cliente.php';

class ClienteController {
    
    public static function listar() {
        require_once __DIR__ . '/../require_login.php';
        require_once __DIR__ . '/../conexao.php';
        
        $clientes = Cliente::buscarTodos();
        
        // Mensagens de feedback
        $successMsg = '';
        $errorMsg = '';
        if (isset($_GET['success'])) {
            if ($_GET['success'] === 'created') {
                $successMsg = '<div class="alert alert-success fade-in"><i class="bi bi-check-circle"></i> Cliente cadastrado com sucesso!</div>';
            } elseif ($_GET['success'] === 'updated') {
                $successMsg = '<div class="alert alert-success fade-in"><i class="bi bi-check-circle"></i> Cliente atualizado com sucesso!</div>';
            } elseif ($_GET['success'] === 'deleted') {
                $successMsg = '<div class="alert alert-success fade-in"><i class="bi bi-check-circle"></i> Cliente excluído com sucesso!</div>';
            }
        }
        if (isset($_GET['error'])) {
            if ($_GET['error'] === 'not_found') {
                $errorMsg = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Cliente não encontrado.</div>';
            }
        }
        
        include __DIR__ . '/../views/cliente/listar.php';
    }
    
    public static function criar() {
        require_once __DIR__ . '/../require_login.php';
        require_once __DIR__ . '/../conexao.php';
        
        $feedback = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cliente = new Cliente();
            $cliente->nome = trim($_POST['nome'] ?? '');
            $cliente->cpf = trim($_POST['cpf'] ?? '');
            $cliente->email = trim($_POST['email'] ?? '');
            
            if (empty($cliente->nome) || empty($cliente->cpf) || empty($cliente->email)) {
                $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Preencha todos os campos obrigatórios.</div>';
            } elseif (strlen($cliente->cpf) !== 11) {
                $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> CPF deve conter 11 dígitos.</div>';
            } else {
                // Verificar duplicatas
                if (Cliente::buscarPorCpf($cliente->cpf)) {
                    $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> CPF já cadastrado no sistema.</div>';
                } else {
                    if ($cliente->salvar()) {
                        header('Location: ?controller=ClienteController&action=listar&success=created');
                        exit;
                    } else {
                        $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-x-circle"></i> Erro ao cadastrar cliente.</div>';
                    }
                }
            }
        }
        
        include __DIR__ . '/../views/cliente/criar.php';
    }
    
    public static function editar() {
        require_once __DIR__ . '/../require_login.php';
        require_once __DIR__ . '/../conexao.php';
        
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $cliente = Cliente::buscarPorId($id);
        
        if (!$cliente) {
            header('Location: ?controller=ClienteController&action=listar&error=not_found');
            exit;
        }
        
        $feedback = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cliente->nome = trim($_POST['nome'] ?? '');
            $cliente->cpf = trim($_POST['cpf'] ?? '');
            $cliente->email = trim($_POST['email'] ?? '');
            
            if (empty($cliente->nome) || empty($cliente->cpf) || empty($cliente->email)) {
                $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Preencha todos os campos obrigatórios.</div>';
            } elseif (strlen($cliente->cpf) !== 11) {
                $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> CPF deve conter 11 dígitos.</div>';
            } else {
                // Verificar duplicatas em outros clientes
                $existente = Cliente::buscarPorCpf($cliente->cpf);
                if ($existente && $existente->codigo != $cliente->codigo) {
                    $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> CPF já cadastrado para outro cliente.</div>';
                } else {
                    if ($cliente->atualizar()) {
                        header('Location: ?controller=ClienteController&action=listar&success=updated');
                        exit;
                    } else {
                        $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-x-circle"></i> Erro ao atualizar cliente.</div>';
                    }
                }
            }
        }
        
        include __DIR__ . '/../views/cliente/editar.php';
    }
    
    public static function excluir() {
        require_once __DIR__ . '/../require_login.php';
        require_once __DIR__ . '/../conexao.php';
        
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $cliente = Cliente::buscarPorId($id);
        
        if (!$cliente) {
            header('Location: ?controller=ClienteController&action=listar&error=not_found');
            exit;
        }
        
        $feedback = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar']) && $_POST['confirmar'] === 'sim') {
            if ($cliente->temChamados()) {
                $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Não é possível excluir este cliente pois existem chamados vinculados a ele.</div>';
            } else {
                if ($cliente->excluir()) {
                    header('Location: ?controller=ClienteController&action=listar&success=deleted');
                    exit;
                } else {
                    $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-x-circle"></i> Erro ao excluir cliente.</div>';
                }
            }
        }
        
        include __DIR__ . '/../views/cliente/excluir.php';
    }
}

