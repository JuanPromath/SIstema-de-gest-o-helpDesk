<?php
require_once '../require_login.php';
require_once '../conexao.php';

$feedback = '';
$funcionarioId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Buscar dados do funcionário com cargo
$funcionario = null;
if ($funcionarioId > 0) {
    $query = "SELECT f.codigo, f.nome, f.cpf, f.email, f.id_cargo, c.nome as cargo_nome 
              FROM Funcionario f 
              INNER JOIN Cargo c ON f.id_cargo = c.codigo 
              WHERE f.codigo = $funcionarioId";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $funcionario = mysqli_fetch_assoc($result);
    }
}

if (!$funcionario && $funcionarioId > 0) {
    header('Location: ../select/selectFuncionario.php?error=not_found');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $cpf = trim($_POST['cpf'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $cargoId = (int)($_POST['cargo'] ?? 0);
    
    if (empty($nome) || empty($cpf) || empty($email) || $cargoId <= 0) {
        $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Preencha todos os campos obrigatórios.</div>';
    } elseif (strlen($cpf) !== 11) {
        $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> CPF deve conter 11 dígitos.</div>';
    } else {
        // Verificar se CPF ou email já existem em outros funcionários
        $cpfExists = selectWhere('Funcionario', ['codigo'], "cpf = '".mysqli_real_escape_string($conn, $cpf)."' AND codigo != $funcionarioId");
        $emailExists = selectWhere('Funcionario', ['codigo'], "email = '".mysqli_real_escape_string($conn, $email)."' AND codigo != $funcionarioId");
        
        if ($cpfExists && mysqli_num_rows($cpfExists) > 0) {
            $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> CPF já cadastrado para outro funcionário.</div>';
        } elseif ($emailExists && mysqli_num_rows($emailExists) > 0) {
            $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Email já cadastrado para outro funcionário.</div>';
        } else {
            $alteracoes = [];
            if ($nome !== $funcionario['nome']) $alteracoes['nome'] = $nome;
            if ($cpf !== $funcionario['cpf']) $alteracoes['cpf'] = $cpf;
            if ($email !== $funcionario['email']) $alteracoes['email'] = $email;
            if ($cargoId != $funcionario['id_cargo']) $alteracoes['id_cargo'] = $cargoId;
            
            if (!empty($alteracoes)) {
                $result = update('Funcionario', $alteracoes, "codigo = $funcionarioId");
                
                if ($result) {
                    header('Location: ../select/selectFuncionario.php?success=updated');
                    exit;
                } else {
                    $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-x-circle"></i> Erro ao atualizar funcionário.</div>';
                }
            } else {
                $feedback = '<div class="alert alert-info fade-in"><i class="bi bi-info-circle"></i> Nenhuma alteração foi feita.</div>';
            }
        }
    }
}

// Buscar cargos
$cargoOptions = '';
$result = select("Cargo");
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $selected = (isset($funcionario['id_cargo']) && $funcionario['id_cargo'] == $row['codigo']) ? 'selected' : '';
        $cargoOptions .= "<option value='" . $row['codigo'] . "' $selected>" . htmlspecialchars($row['nome']) . "</option>";
    }
} else {
    $cargoOptions = '<option value="">Nenhum cargo cadastrado</option>';
}

$content = '
<div class="dashboard-header fade-in">
    <h1 class="text-gradient">
        <i class="bi bi-pencil"></i>
        Editar Funcionário
    </h1>
    <p class="text-muted">Altere as informações do funcionário</p>
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
                            value="' . htmlspecialchars($funcionario['nome'] ?? '') . '"
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
                            value="' . htmlspecialchars($funcionario['cpf'] ?? '') . '"
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
                            value="' . htmlspecialchars($funcionario['email'] ?? '') . '"
                        >
                    </div>
                    
                    <div class="input-group-modern mb-4">
                        <i class="bi bi-briefcase input-icon"></i>
                        <select class="form-control-modern" id="cargo" name="cargo" required>
                            <option value="">Selecione o Cargo</option>
                            ' . $cargoOptions . '
                        </select>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn-modern btn-modern-primary">
                            <i class="bi bi-check-circle"></i>
                            Atualizar Funcionário
                        </button>
                        <a href="../select/selectFuncionario.php" class="btn-modern btn-modern-secondary">
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

