<?php
require_once __DIR__ . '/../models/Funcionario.php';
require_once __DIR__ . '/../models/Cargo.php';

class FuncionarioController {
    
    public static function listar() {
        require_once __DIR__ . '/../require_login.php';
        require_once __DIR__ . '/../conexao.php';
        
        $funcionarios = Funcionario::buscarTodos();
        
        // Mensagens de feedback
        $successMsg = '';
        $errorMsg = '';
        if (isset($_GET['success'])) {
            if ($_GET['success'] === 'created') {
                $successMsg = '<div class="alert alert-success fade-in"><i class="bi bi-check-circle"></i> Funcionário cadastrado com sucesso!</div>';
            } elseif ($_GET['success'] === 'updated') {
                $successMsg = '<div class="alert alert-success fade-in"><i class="bi bi-check-circle"></i> Funcionário atualizado com sucesso!</div>';
            } elseif ($_GET['success'] === 'deleted') {
                $successMsg = '<div class="alert alert-success fade-in"><i class="bi bi-check-circle"></i> Funcionário excluído com sucesso!</div>';
            }
        }
        if (isset($_GET['error'])) {
            if ($_GET['error'] === 'not_found') {
                $errorMsg = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Funcionário não encontrado.</div>';
            }
        }
        
        include __DIR__ . '/../views/funcionario/listar.php';
    }
    
    public static function criar() {
        require_once __DIR__ . '/../require_login.php';
        require_once __DIR__ . '/../conexao.php';
        
        $feedback = '';
        $cargos = Cargo::buscarTodos();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $funcionario = new Funcionario();
            $funcionario->nome = trim($_POST['nome'] ?? '');
            $funcionario->cpf = trim($_POST['cpf'] ?? '');
            $funcionario->email = trim($_POST['email'] ?? '');
            $funcionario->id_cargo = (int)($_POST['cargo'] ?? 0);
            
            if (empty($funcionario->nome) || empty($funcionario->cpf) || empty($funcionario->email) || $funcionario->id_cargo <= 0) {
                $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Preencha todos os campos obrigatórios.</div>';
            } elseif (strlen($funcionario->cpf) !== 11) {
                $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> CPF deve conter 11 dígitos.</div>';
            } else {
                // Verificar duplicatas
                if (Funcionario::buscarPorCpf($funcionario->cpf)) {
                    $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> CPF já cadastrado no sistema.</div>';
                } elseif (Funcionario::buscarPorEmail($funcionario->email)) {
                    $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Email já cadastrado no sistema.</div>';
                } else {
                    if ($funcionario->salvar()) {
                        header('Location: ?controller=FuncionarioController&action=listar&success=created');
                        exit;
                    } else {
                        $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-x-circle"></i> Erro ao cadastrar funcionário.</div>';
                    }
                }
            }
        }
        
        include __DIR__ . '/../views/funcionario/criar.php';
    }
    
    public static function editar() {
        require_once __DIR__ . '/../require_login.php';
        require_once __DIR__ . '/../conexao.php';
        
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $funcionario = Funcionario::buscarPorId($id);
        
        if (!$funcionario) {
            header('Location: ?controller=FuncionarioController&action=listar&error=not_found');
            exit;
        }
        
        $feedback = '';
        $cargos = Cargo::buscarTodos();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $funcionario->nome = trim($_POST['nome'] ?? '');
            $funcionario->cpf = trim($_POST['cpf'] ?? '');
            $funcionario->email = trim($_POST['email'] ?? '');
            $funcionario->id_cargo = (int)($_POST['cargo'] ?? 0);
            
            if (empty($funcionario->nome) || empty($funcionario->cpf) || empty($funcionario->email) || $funcionario->id_cargo <= 0) {
                $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Preencha todos os campos obrigatórios.</div>';
            } elseif (strlen($funcionario->cpf) !== 11) {
                $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> CPF deve conter 11 dígitos.</div>';
            } else {
                // Verificar duplicatas em outros funcionários
                $existenteCpf = Funcionario::buscarPorCpf($funcionario->cpf);
                $existenteEmail = Funcionario::buscarPorEmail($funcionario->email);
                
                if ($existenteCpf && $existenteCpf->codigo != $funcionario->codigo) {
                    $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> CPF já cadastrado para outro funcionário.</div>';
                } elseif ($existenteEmail && $existenteEmail->codigo != $funcionario->codigo) {
                    $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Email já cadastrado para outro funcionário.</div>';
                } else {
                    if ($funcionario->atualizar()) {
                        header('Location: ?controller=FuncionarioController&action=listar&success=updated');
                        exit;
                    } else {
                        $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-x-circle"></i> Erro ao atualizar funcionário.</div>';
                    }
                }
            }
        }
        
        include __DIR__ . '/../views/funcionario/editar.php';
    }
    
    public static function excluir() {
        require_once __DIR__ . '/../require_login.php';
        require_once __DIR__ . '/../conexao.php';
        
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $funcionario = Funcionario::buscarPorId($id);
        
        if (!$funcionario) {
            header('Location: ?controller=FuncionarioController&action=listar&error=not_found');
            exit;
        }
        
        $feedback = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar']) && $_POST['confirmar'] === 'sim') {
            if ($funcionario->temChamados()) {
                $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Não é possível excluir este funcionário pois existem chamados vinculados a ele.</div>';
            } else {
                if ($funcionario->excluir()) {
                    header('Location: ?controller=FuncionarioController&action=listar&success=deleted');
                    exit;
                } else {
                    $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-x-circle"></i> Erro ao excluir funcionário.</div>';
                }
            }
        }
        
        include __DIR__ . '/../views/funcionario/excluir.php';
    }
}

