<?php
require_once '../require_login.php';
require_once '../admin_functions.php';
require_once '../conexao.php';

// Verificar se é administrador
$admin = requireAdmin();

$feedback = '';
$cargoId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Buscar dados do cargo
$cargo = null;
if ($cargoId > 0) {
    $result = selectWhere('Cargo', ['*'], "codigo = $cargoId");
    if ($result && mysqli_num_rows($result) > 0) {
        $cargo = mysqli_fetch_assoc($result);
    }
}

if (!$cargo && $cargoId > 0) {
    header('Location: ../select/selectCargo.php?error=not_found');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    
    if (empty($nome)) {
        $feedback = '<div class="alert alert-danger mt-3 fade-in"><i class="bi bi-exclamation-triangle"></i> O nome do cargo é obrigatório.</div>';
    } else {
        // Verificar se já existe outro cargo com o mesmo nome
        $exists = selectWhere('Cargo', ['codigo'], "nome = '".mysqli_real_escape_string($conn, $nome)."' AND codigo != $cargoId");
        
        if ($exists && mysqli_num_rows($exists) > 0) {
            $feedback = '<div class="alert alert-danger mt-3 fade-in"><i class="bi bi-exclamation-triangle"></i> Já existe um cargo com este nome.</div>';
        } else {
            $result = update('Cargo', ['nome' => $nome], "codigo = $cargoId");
            
            if ($result) {
                header('Location: ../select/selectCargo.php?success=updated');
                exit;
            } else {
                $feedback = '<div class="alert alert-danger mt-3 fade-in"><i class="bi bi-x-circle"></i> Erro ao atualizar cargo.</div>';
            }
        }
    }
}

$content = '
<div class="dashboard-header fade-in">
    <h1 class="text-gradient">
        <i class="bi bi-pencil"></i>
        ' . ($cargoId > 0 ? 'Editar' : 'Novo') . ' Cargo
    </h1>
    <p class="text-muted">' . ($cargoId > 0 ? 'Altere as informações do cargo' : 'Cadastre um novo cargo no sistema') . '</p>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6 fade-in">
        <div class="card-modern">
            <div class="card-body">
                <form method="post">
                    <div class="input-group-modern mb-4">
                        <i class="bi bi-briefcase input-icon"></i>
                        <input 
                            type="text" 
                            class="form-control-modern" 
                            id="nome" 
                            name="nome" 
                            placeholder="Nome do Cargo" 
                            required
                            value="' . htmlspecialchars($cargo['nome'] ?? '') . '"
                        >
                    </div>
                    
                    ' . $feedback . '
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn-modern btn-modern-primary">
                            <i class="bi bi-check-circle"></i>
                            ' . ($cargoId > 0 ? 'Atualizar' : 'Cadastrar') . ' Cargo
                        </button>
                        <a href="../select/selectCargo.php" class="btn-modern btn-modern-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>';

include '../template.php';
?>

