<?php
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
                        <a href="?controller=ClienteController&action=listar" class="btn-modern btn-modern-secondary">
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

include '../../template.php';
?>

