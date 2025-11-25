<?php
require_once __DIR__ . '/../models/Chamado.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Cargo.php';
require_once __DIR__ . '/../models/Conta.php';

class ChamadoController {
    
    public static function listar() {
        require_once __DIR__ . '/../require_login.php';
        require_once __DIR__ . '/../conexao.php';
        
        $statusFiltro = $_GET['status'] ?? '';
        
        if ($statusFiltro) {
            $chamados = Chamado::buscarPorStatus($statusFiltro);
        } else {
            $chamados = Chamado::buscarTodos();
        }
        
        // Mensagens de feedback
        $successMsg = '';
        $errorMsg = '';
        if (isset($_GET['success'])) {
            if ($_GET['success'] === 'created') {
                $successMsg = '<div class="alert alert-success fade-in"><i class="bi bi-check-circle"></i> Chamado criado com sucesso!</div>';
            } elseif ($_GET['success'] === 'updated') {
                $successMsg = '<div class="alert alert-success fade-in"><i class="bi bi-check-circle"></i> Chamado atualizado com sucesso!</div>';
            } elseif ($_GET['success'] === 'deleted') {
                $successMsg = '<div class="alert alert-success fade-in"><i class="bi bi-check-circle"></i> Chamado excluído com sucesso!</div>';
            }
        }
        if (isset($_GET['error'])) {
            if ($_GET['error'] === 'not_found') {
                $errorMsg = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Chamado não encontrado.</div>';
            }
        }
        
        include __DIR__ . '/../views/chamado/listar.php';
    }
    
    public static function criar() {
        require_once __DIR__ . '/../require_login.php';
        require_once __DIR__ . '/../conexao.php';
        
        $feedback = '';
        $clientes = Cliente::buscarTodos();
        $cargos = Cargo::buscarTodos();
        $contas = Conta::buscarTodos();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $chamado = new Chamado();
            $chamado->bo = trim($_POST['bo'] ?? '');
            $chamado->Id_cliente = (int)($_POST['cliente'] ?? 0);
            $chamado->id_cargo = (int)($_POST['cargo'] ?? 0);
            $chamado->Id_conta = (int)($_POST['conta'] ?? 0);
            $chamado->status = 'aberto';
            
            if (empty($chamado->bo) || $chamado->Id_cliente <= 0 || $chamado->id_cargo <= 0 || $chamado->Id_conta <= 0) {
                $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Preencha todos os campos obrigatórios.</div>';
            } else {
                // Buscar funcionário da conta
                $conta = Conta::buscarPorId($chamado->Id_conta);
                if ($conta) {
                    $chamado->Id_funcionario = $conta->Id_funcionario;
                    
                    if ($chamado->salvar()) {
                        header('Location: ?controller=ChamadoController&action=listar&success=created');
                        exit;
                    } else {
                        global $conn;
                        $errorMsg = mysqli_error($conn);
                        $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-x-circle"></i> Erro ao criar chamado. ' . ($errorMsg ? htmlspecialchars($errorMsg) : '') . '</div>';
                    }
                } else {
                    $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Conta não encontrada.</div>';
                }
            }
        }
        
        include __DIR__ . '/../views/chamado/criar.php';
    }
    
    public static function editar() {
        require_once __DIR__ . '/../require_login.php';
        require_once __DIR__ . '/../conexao.php';
        
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $chamado = Chamado::buscarPorId($id);
        
        if (!$chamado) {
            header('Location: ?controller=ChamadoController&action=listar&error=not_found');
            exit;
        }
        
        $feedback = '';
        $clientes = Cliente::buscarTodos();
        $cargos = Cargo::buscarTodos();
        $contas = Conta::buscarTodos();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $chamado->bo = trim($_POST['bo'] ?? '');
            $chamado->status = $_POST['status'] ?? 'aberto';
            $chamado->Id_cliente = (int)($_POST['cliente'] ?? 0);
            $chamado->id_cargo = (int)($_POST['cargo'] ?? 0);
            $chamado->Id_conta = (int)($_POST['conta'] ?? 0);
            
            if (empty($chamado->bo) || $chamado->Id_cliente <= 0 || $chamado->id_cargo <= 0 || $chamado->Id_conta <= 0) {
                $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Preencha todos os campos obrigatórios.</div>';
            } else {
                if ($chamado->atualizar()) {
                    header('Location: ?controller=ChamadoController&action=listar&success=updated');
                    exit;
                } else {
                    $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-x-circle"></i> Erro ao atualizar chamado.</div>';
                }
            }
        }
        
        include __DIR__ . '/../views/chamado/editar.php';
    }
    
    public static function excluir() {
        require_once __DIR__ . '/../require_login.php';
        require_once __DIR__ . '/../conexao.php';
        
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $chamado = Chamado::buscarPorId($id);
        
        if (!$chamado) {
            header('Location: ?controller=ChamadoController&action=listar&error=not_found');
            exit;
        }
        
        $feedback = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar']) && $_POST['confirmar'] === 'sim') {
            if ($chamado->excluir()) {
                header('Location: ?controller=ChamadoController&action=listar&success=deleted');
                exit;
            } else {
                $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-x-circle"></i> Erro ao excluir chamado.</div>';
            }
        }
        
        include __DIR__ . '/../views/chamado/excluir.php';
    }
}
