<?php
// Buscar dados do funcionário
$funcionario = Funcionario::buscarPorId($conta->Id_funcionario);

$content = '
<div class="dashboard-header fade-in">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1 class="text-gradient">
                <i class="bi bi-shield-lock"></i>
                Alterar Senha da Conta
            </h1>
            <p class="text-muted">Altere a senha da conta do funcionário</p>
        </div>
        <a href="?controller=ContaController&action=listar" class="btn-modern btn-modern-outline">
            <i class="bi bi-arrow-left"></i>
            Voltar
        </a>
    </div>
</div>

' . ($feedback ? '<div class="row mb-3"><div class="col-12">' . $feedback . '</div></div>' : '') . '

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6 fade-in">
        <div class="card-modern">
            <div class="card-body p-4">
                <div class="mb-4 p-3" style="background: var(--bg-tertiary); border-radius: 8px;">
                    <small class="text-muted d-block mb-1">Funcionário:</small>
                    <strong>' . htmlspecialchars($funcionario->nome ?? 'N/A') . '</strong>
                    <br>
                    <small class="text-muted">ID: #' . htmlspecialchars($conta->Id_funcionario) . '</small>
                </div>
                
                <form action="?controller=ContaController&action=editar&id=' . $conta->codigo . '" method="post">
                    <div class="input-group-modern mb-4">
                        <i class="bi bi-key input-icon"></i>
                        <input 
                            type="password" 
                            class="form-control-modern" 
                            id="senha" 
                            name="senha" 
                            placeholder="Nova Senha" 
                            required
                            minlength="6"
                        >
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
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn-modern btn-modern-primary">
                            <i class="bi bi-check-circle"></i>
                            Salvar Alterações
                        </button>
                        <a href="?controller=ContaController&action=listar" class="btn-modern btn-modern-outline">
                            <i class="bi bi-x-circle"></i>
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>';

include '../../template.php';
?>

<script>
// Validação de confirmação de senha
const senhaInput = document.getElementById('senha');
const confirmarSenhaInput = document.getElementById('confirmar_senha');

if (senhaInput && confirmarSenhaInput) {
    function validatePassword() {
        const senha = senhaInput.value;
        const confirmar = confirmarSenhaInput.value;
        
        if (confirmar && senha !== confirmar) {
            confirmarSenhaInput.setCustomValidity('As senhas não coincidem');
            confirmarSenhaInput.classList.add('is-invalid');
        } else {
            confirmarSenhaInput.setCustomValidity('');
            confirmarSenhaInput.classList.remove('is-invalid');
        }
    }
    
    senhaInput.addEventListener('input', validatePassword);
    confirmarSenhaInput.addEventListener('input', validatePassword);
}
</script>

