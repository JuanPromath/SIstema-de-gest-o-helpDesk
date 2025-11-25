<?php
$protect = true;
if ($protect) require_once '../require_login.php';
require_once '../admin_functions.php';

// Verificar se é administrador
$admin = requireAdmin();

$feedback = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../conexao.php';
    $nome = trim($_POST['nome'] ?? '');
    
    if (empty($nome)) {
        $feedback = '<div class="alert alert-danger mt-3 fade-in"><i class="bi bi-exclamation-triangle"></i> O nome do cargo é obrigatório.</div>';
    } else {
        // Verificar se já existe cargo com o mesmo nome
        $exists = selectWhere('Cargo', ['codigo'], "nome = '".mysqli_real_escape_string($conn, $nome)."'");
        
        if ($exists && mysqli_num_rows($exists) > 0) {
            $feedback = '<div class="alert alert-danger mt-3 fade-in"><i class="bi bi-exclamation-triangle"></i> Já existe um cargo com este nome.</div>';
        } else {
            $result = insert(['nome'], ['nome' => $nome], "Cargo");
            
            if ($result) {
                header('Location: ../select/selectCargo.php?success=created');
                exit;
            } else {
                $feedback = '<div class="alert alert-danger mt-3 fade-in"><i class="bi bi-x-circle"></i> Erro ao cadastrar cargo.</div>';
            }
        }
    }
}

$content = '
<div class="dashboard-header fade-in">
    <h1 class="text-gradient">
        <i class="bi bi-briefcase"></i>
        Novo Cargo
    </h1>
    <p class="text-muted">Cadastre um novo cargo no sistema</p>
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
                            autofocus
                        >
                    </div>
                    
                    ' . $feedback . '
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn-modern btn-modern-primary">
                            <i class="bi bi-check-circle"></i>
                            Cadastrar Cargo
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
