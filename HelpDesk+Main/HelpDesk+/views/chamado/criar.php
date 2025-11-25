<?php
$clienteOptions = '';
foreach ($clientes as $cliente) {
    $clienteOptions .= "<option value='" . $cliente->codigo . "'>" . htmlspecialchars($cliente->nome) . " - CPF: " . htmlspecialchars($cliente->cpf) . "</option>";
}

$cargoOptions = '';
foreach ($cargos as $cargo) {
    $cargoOptions .= "<option value='" . $cargo->codigo . "'>" . htmlspecialchars($cargo->nome) . "</option>";
}

$contaOptions = '';
foreach ($contas as $conta) {
    $contaOptions .= "<option value='" . $conta->codigo . "'>" . htmlspecialchars($conta->funcionario_nome ?? 'N/A') . "</option>";
}

$content = '
<div class="dashboard-header fade-in">
    <h1 class="text-gradient">
        <i class="bi bi-ticket-perforated"></i>
        Abrir Novo Chamado
    </h1>
    <p class="text-muted">Preencha os dados para abrir um novo chamado no sistema</p>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-lg-10 fade-in">
        <div class="card-modern">
            <div class="card-body p-4">
                ' . $feedback . '
                
                <form method="post" id="chamadoForm">
                    <div class="row">
                        <div class="col-12 col-md-6 mb-4">
                            <div class="input-group-modern">
                                <i class="bi bi-file-earmark-text input-icon"></i>
                                <textarea 
                                    class="form-control-modern" 
                                    id="bo" 
                                    name="bo" 
                                    placeholder="Descrição do problema (BO)" 
                                    rows="5"
                                    required
                                ></textarea>
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-6 mb-4">
                            <div class="card-modern" style="background: var(--bg-tertiary);">
                                <div class="card-body">
                                    <h6 class="mb-3">
                                        <i class="bi bi-eye text-primary"></i>
                                        Pré-visualização
                                    </h6>
                                    <div class="mb-2">
                                        <small class="text-muted d-block">Descrição:</small>
                                        <span id="boPreview" class="text-muted">—</span>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted d-block">Cliente:</small>
                                        <span id="clientePreview" class="text-muted">—</span>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Cargo:</small>
                                        <span id="cargoPreview" class="text-muted">—</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 col-md-6 mb-4">
                            <div class="input-group-modern">
                                <i class="bi bi-people input-icon"></i>
                                <select class="form-control-modern" id="cliente" name="cliente" required>
                                    <option value="">Selecione o Cliente</option>
                                    ' . $clienteOptions . '
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-6 mb-4">
                            <div class="input-group-modern">
                                <i class="bi bi-briefcase input-icon"></i>
                                <select class="form-control-modern" id="cargo" name="cargo" required>
                                    <option value="">Selecione o Cargo</option>
                                    ' . $cargoOptions . '
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 mb-4">
                            <div class="input-group-modern">
                                <i class="bi bi-shield-lock input-icon"></i>
                                <select class="form-control-modern" id="conta" name="conta" required>
                                    <option value="">Selecione a Conta</option>
                                    ' . $contaOptions . '
                                </select>
                            </div>
                            <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">O funcionário será selecionado automaticamente baseado na conta</small>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn-modern btn-modern-primary">
                            <i class="bi bi-check-circle"></i>
                            Abrir Chamado
                        </button>
                        <a href="?controller=ChamadoController&action=listar" class="btn-modern btn-modern-secondary">
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
    const boInput = document.getElementById("bo");
    const clienteSelect = document.getElementById("cliente");
    const cargoSelect = document.getElementById("cargo");
    
    const boPreview = document.getElementById("boPreview");
    const clientePreview = document.getElementById("clientePreview");
    const cargoPreview = document.getElementById("cargoPreview");
    
    if (boInput && boPreview) {
        boInput.addEventListener("input", function() {
            boPreview.textContent = this.value || "—";
        });
    }
    
    if (clienteSelect && clientePreview) {
        clienteSelect.addEventListener("change", function() {
            clientePreview.textContent = this.options[this.selectedIndex].text || "—";
        });
    }
    
    if (cargoSelect && cargoPreview) {
        cargoSelect.addEventListener("change", function() {
            cargoPreview.textContent = this.options[this.selectedIndex].text || "—";
        });
    }
});
</script>';

include '../../template.php';
?>

