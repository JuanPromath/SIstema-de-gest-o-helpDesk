<?php
$clienteOptions = '';
foreach ($clientes as $cliente) {
    $selected = ($chamado->Id_cliente == $cliente->codigo) ? 'selected' : '';
    $clienteOptions .= "<option value='" . $cliente->codigo . "' $selected>" . htmlspecialchars($cliente->nome) . " - CPF: " . htmlspecialchars($cliente->cpf) . "</option>";
}

$cargoOptions = '';
foreach ($cargos as $cargo) {
    $selected = ($chamado->id_cargo == $cargo->codigo) ? 'selected' : '';
    $cargoOptions .= "<option value='" . $cargo->codigo . "' $selected>" . htmlspecialchars($cargo->nome) . "</option>";
}

$contaOptions = '';
foreach ($contas as $conta) {
    $selected = ($chamado->Id_conta == $conta->codigo) ? 'selected' : '';
    $contaOptions .= "<option value='" . $conta->codigo . "' $selected>" . htmlspecialchars($conta->funcionario_nome ?? 'N/A') . "</option>";
}

$content = '
<div class="dashboard-header fade-in">
    <h1 class="text-gradient">
        <i class="bi bi-pencil"></i>
        Editar Chamado #' . $chamado->codigo . '
    </h1>
    <p class="text-muted">Altere as informações do chamado</p>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-lg-10 fade-in">
        <div class="card-modern">
            <div class="card-body p-4">
                ' . $feedback . '
                
                <form method="post">
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
                                >' . htmlspecialchars($chamado->bo ?? '') . '</textarea>
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-6 mb-4">
                            <div class="input-group-modern">
                                <i class="bi bi-flag input-icon"></i>
                                <select class="form-control-modern" id="status" name="status" required>
                                    <option value="aberto" ' . (($chamado->status ?? '') === 'aberto' ? 'selected' : '') . '>Aberto</option>
                                    <option value="em andamento" ' . (($chamado->status ?? '') === 'em andamento' ? 'selected' : '') . '>Em Andamento</option>
                                    <option value="fechado" ' . (($chamado->status ?? '') === 'fechado' ? 'selected' : '') . '>Fechado</option>
                                </select>
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
                        <div class="col-12 col-md-6 mb-4">
                            <div class="input-group-modern">
                                <i class="bi bi-shield-lock input-icon"></i>
                                <select class="form-control-modern" id="conta" name="conta" required>
                                    <option value="">Selecione a Conta</option>
                                    ' . $contaOptions . '
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn-modern btn-modern-primary">
                            <i class="bi bi-check-circle"></i>
                            Atualizar Chamado
                        </button>
                        <a href="?controller=ChamadoController&action=listar" class="btn-modern btn-modern-secondary">
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

