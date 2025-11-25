<?php
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
                        <a href="?controller=CargoController&action=listar" class="btn-modern btn-modern-secondary">
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

