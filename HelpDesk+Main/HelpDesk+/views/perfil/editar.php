<?php
$content = '
<div class="dashboard-header fade-in">
    <h1 class="text-gradient">
        <i class="bi bi-pencil"></i>
        Editar Perfil
    </h1>
    <p class="text-muted">Atualize suas informações pessoais</p>
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
                            value="' . htmlspecialchars($funcionario->nome ?? '') . '"
                        >
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
                            value="' . htmlspecialchars($funcionario->email ?? '') . '"
                        >
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <small>O CPF e o Cargo não podem ser alterados por aqui. Entre em contato com o administrador se necessário.</small>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn-modern btn-modern-primary">
                            <i class="bi bi-check-circle"></i>
                            Salvar Alterações
                        </button>
                        <a href="?controller=PerfilController&action=index" class="btn-modern btn-modern-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>';

include '../../template.php';
?>

