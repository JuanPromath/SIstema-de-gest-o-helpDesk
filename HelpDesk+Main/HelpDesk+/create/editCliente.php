<?php
require_once '../require_login.php';
require_once '../conexao.php';

$feedback = '';
$clienteId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Buscar dados do cliente
$cliente = null;
if ($clienteId > 0) {
    $result = selectWhere('Cliente', ['*'], "codigo = $clienteId");
    if ($result && mysqli_num_rows($result) > 0) {
        $cliente = mysqli_fetch_assoc($result);
    }
}

if (!$cliente && $clienteId > 0) {
    header('Location: ../select/selectCliente.php?error=not_found');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $cpf = trim($_POST['cpf'] ?? '');
    $email = trim($_POST['email'] ?? '');
    
    if (empty($nome) || empty($cpf) || empty($email)) {
        $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Preencha todos os campos obrigatórios.</div>';
    } elseif (strlen($cpf) !== 11) {
        $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> CPF deve conter 11 dígitos.</div>';
    } else {
        // Verificar se CPF ou email já existem em outros clientes
        $cpfExists = selectWhere('Cliente', ['codigo'], "cpf = '".mysqli_real_escape_string($conn, $cpf)."' AND codigo != $clienteId");
        $emailExists = selectWhere('Cliente', ['codigo'], "email = '".mysqli_real_escape_string($conn, $email)."' AND codigo != $clienteId");
        
        if ($cpfExists && mysqli_num_rows($cpfExists) > 0) {
            $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> CPF já cadastrado para outro cliente.</div>';
        } elseif ($emailExists && mysqli_num_rows($emailExists) > 0) {
            $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Email já cadastrado para outro cliente.</div>';
        } else {
            $alteracoes = [];
            if ($nome !== $cliente['nome']) $alteracoes['nome'] = $nome;
            if ($cpf !== $cliente['cpf']) $alteracoes['cpf'] = $cpf;
            if ($email !== $cliente['email']) $alteracoes['email'] = $email;
            
            if (!empty($alteracoes)) {
                $result = update('Cliente', $alteracoes, "codigo = $clienteId");
                
                if ($result) {
                    header('Location: ../select/selectCliente.php?success=updated');
                    exit;
                } else {
                    $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-x-circle"></i> Erro ao atualizar cliente.</div>';
                }
            } else {
                $feedback = '<div class="alert alert-info fade-in"><i class="bi bi-info-circle"></i> Nenhuma alteração foi feita.</div>';
            }
        }
    }
}

$content = '
<div class="dashboard-header fade-in">
    <h1 class="text-gradient">
        <i class="bi bi-pencil"></i>
        Editar Cliente
    </h1>
    <p class="text-muted">Altere as informações do cliente</p>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6 fade-in">
        <div class="card-modern">
            <div class="card-body p-4">
                ' . $feedback . '
                
                <form method="post">
                    <div class="input-group-modern mb-4">
                        <i class="bi bi-person input-icon"></i>
                        <input 
                            type="text" 
                            class="form-control-modern" 
                            id="nome" 
                            name="nome" 
                            placeholder="Nome Completo" 
                            required
                            autofocus
                            value="' . htmlspecialchars($cliente['nome'] ?? '') . '"
                        >
                    </div>
                    
                    <div class="input-group-modern mb-4">
                        <i class="bi bi-credit-card input-icon"></i>
                        <input 
                            type="text" 
                            class="form-control-modern" 
                            id="cpf" 
                            name="cpf" 
                            placeholder="CPF (apenas números)" 
                            maxlength="11"
                            required
                            value="' . htmlspecialchars($cliente['cpf'] ?? '') . '"
                        >
                        <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">11 dígitos</small>
                    </div>
                    
                    <div class="input-group-modern mb-4">
                        <i class="bi bi-envelope input-icon"></i>
                        <input 
                            type="email" 
                            class="form-control-modern" 
                            id="email" 
                            name="email" 
                            placeholder="Email" 
                            required
                            value="' . htmlspecialchars($cliente['email'] ?? '') . '"
                        >
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn-modern btn-modern-primary">
                            <i class="bi bi-check-circle"></i>
                            Atualizar Cliente
                        </button>
                        <a href="../select/selectCliente.php" class="btn-modern btn-modern-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const cpfInput = document.getElementById("cpf");
    if (cpfInput) {
        cpfInput.addEventListener("input", function() {
            this.value = this.value.replace(/\D/g, "");
            if (this.value.length > 11) {
                this.value = this.value.substring(0, 11);
            }
        });
    }
});
</script>';

include '../template.php';
?>

