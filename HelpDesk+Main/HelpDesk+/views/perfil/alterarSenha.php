<?php
$content = '
<div class="dashboard-header fade-in">
    <h1 class="text-gradient">
        <i class="bi bi-key"></i>
        Alterar Senha
    </h1>
    <p class="text-muted">Altere sua senha de acesso ao sistema</p>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6 fade-in">
        <div class="card-modern">
            <div class="card-body p-4">
                ' . $feedback . '
                
                <form method="post" id="senhaForm">
                    <div class="input-group-modern mb-4">
                        <i class="bi bi-lock input-icon"></i>
                        <input 
                            type="password" 
                            class="form-control-modern" 
                            id="senha_atual" 
                            name="senha_atual" 
                            placeholder="Senha Atual" 
                            required
                            autofocus
                        >
                    </div>
                    
                    <div class="input-group-modern mb-4">
                        <i class="bi bi-key input-icon"></i>
                        <input 
                            type="password" 
                            class="form-control-modern" 
                            id="nova_senha" 
                            name="nova_senha" 
                            placeholder="Nova Senha (mínimo 6 caracteres)" 
                            required
                            minlength="6"
                        >
                        <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Mínimo de 6 caracteres</small>
                    </div>
                    
                    <div class="input-group-modern mb-4">
                        <i class="bi bi-key-fill input-icon"></i>
                        <input 
                            type="password" 
                            class="form-control-modern" 
                            id="confirmar_senha" 
                            name="confirmar_senha" 
                            placeholder="Confirmar Nova Senha" 
                            required
                            minlength="6"
                        >
                        <small id="senhaMatch" class="d-block mt-1" style="font-size: 0.75rem;"></small>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        <small><strong>Importante:</strong> Certifique-se de que sua nova senha seja segura e fácil de lembrar.</small>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn-modern btn-modern-primary">
                            <i class="bi bi-check-circle"></i>
                            Alterar Senha
                        </button>
                        <a href="?controller=PerfilController&action=index" class="btn-modern btn-modern-secondary">
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
    const novaSenha = document.getElementById("nova_senha");
    const confirmarSenha = document.getElementById("confirmar_senha");
    const senhaMatch = document.getElementById("senhaMatch");
    
    function verificarSenhas() {
        if (confirmarSenha.value) {
            if (novaSenha.value === confirmarSenha.value) {
                senhaMatch.innerHTML = \'<i class="bi bi-check-circle text-success"></i> As senhas coincidem\';
                senhaMatch.className = "text-success d-block mt-1";
            } else {
                senhaMatch.innerHTML = \'<i class="bi bi-x-circle text-danger"></i> As senhas não coincidem\';
                senhaMatch.className = "text-danger d-block mt-1";
            }
        } else {
            senhaMatch.innerHTML = "";
        }
    }
    
    novaSenha.addEventListener("input", verificarSenhas);
    confirmarSenha.addEventListener("input", verificarSenhas);
});
</script>';

include '../../template.php';
?>

