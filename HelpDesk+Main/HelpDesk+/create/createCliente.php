<?php
$protect = true;
if ($protect) require_once '../require_login.php';
require_once '../conexao.php';

$feedback = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $cpf = trim($_POST['cpf'] ?? '');
    $email = trim($_POST['email'] ?? '');
    
    if (empty($nome) || empty($cpf) || empty($email)) {
        $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Preencha todos os campos obrigatórios.</div>';
    } elseif (strlen($cpf) !== 11) {
        $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> CPF deve conter 11 dígitos.</div>';
    } else {
        // Verificar se CPF ou email já existem
        $cpfExists = selectWhere('Cliente', ['codigo'], "cpf = '".mysqli_real_escape_string($conn, $cpf)."'");
        $emailExists = selectWhere('Cliente', ['codigo'], "email = '".mysqli_real_escape_string($conn, $email)."'");
        
        if ($cpfExists && mysqli_num_rows($cpfExists) > 0) {
            $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> CPF já cadastrado no sistema.</div>';
        } elseif ($emailExists && mysqli_num_rows($emailExists) > 0) {
            $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Email já cadastrado no sistema.</div>';
        } else {
            $dadosCliente = [
                'nome' => $nome,
                'cpf' => $cpf,
                'email' => $email
            ];
            
            $result = insert(['nome', 'cpf', 'email'], $dadosCliente, 'Cliente');
            
            if ($result) {
                header('Location: ../select/selectCliente.php?success=created');
                exit;
  } else {
                $errorMsg = mysqli_error($conn);
                $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-x-circle"></i> Erro ao cadastrar cliente. ' . ($errorMsg ? htmlspecialchars($errorMsg) : '') . '</div>';
            }
        }
  }
}

$content = '
<div class="dashboard-header fade-in">
    <h1 class="text-gradient">
        <i class="bi bi-person-plus"></i>
        Cadastrar Cliente
    </h1>
    <p class="text-muted">Preencha os dados para cadastrar um novo cliente no sistema</p>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6 fade-in">
        <div class="card-modern">
            <div class="card-body p-4">
                ' . $feedback . '
                
                <form method="post" id="clienteForm">
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
                        >
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn-modern btn-modern-primary">
                            <i class="bi bi-check-circle"></i>
                            Cadastrar Cliente
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
