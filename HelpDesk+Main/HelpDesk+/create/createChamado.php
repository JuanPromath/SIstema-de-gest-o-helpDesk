<?php
$protect = true;
if ($protect) require_once '../require_login.php';
require_once '../conexao.php';

$feedback = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar campos
    $bo = trim($_POST['bo'] ?? '');
    $clienteId = (int)($_POST['cliente'] ?? 0);
    $cargoId = (int)($_POST['cargo'] ?? 0);
    $contaId = (int)($_POST['conta'] ?? 0);
    
    if (empty($bo) || $clienteId <= 0 || $cargoId <= 0 || $contaId <= 0) {
        $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Preencha todos os campos obrigatórios.</div>';
    } else {
        // Buscar ID do funcionário da conta
        $contaResult = selectWhere('Conta_Sistema', ['Id_funcionario'], "codigo = $contaId");
        if ($contaResult && mysqli_num_rows($contaResult) > 0) {
            $conta = mysqli_fetch_assoc($contaResult);
            $funcionarioContaId = $conta['Id_funcionario'];
            
            $dadosChamado = [
                'bo' => $bo,
                'Id_cliente' => $clienteId,
                'id_cargo' => $cargoId,
                'Id_funcionario' => $funcionarioContaId,
                'Id_conta' => $contaId,
                'status' => 'aberto'
            ];
            
            $result = insert(['bo', 'Id_cliente', 'id_cargo', 'Id_funcionario', 'Id_conta', 'status'], $dadosChamado, 'Chamado');
            
            if ($result) {
                header('Location: ../select/selectChamado.php?success=created');
                exit;
            } else {
                $errorMsg = mysqli_error($conn);
                $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-x-circle"></i> Erro ao criar chamado. ' . ($errorMsg ? htmlspecialchars($errorMsg) : '') . '</div>';
            }
        } else {
            $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Conta não encontrada.</div>';
        }
    }
}

// Buscar opções para os selects
$clienteOptions = '';
$result = select('Cliente', ['codigo', 'nome', 'cpf']);
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $clienteOptions .= "<option value='" . $row['codigo'] . "'>" . htmlspecialchars($row['nome']) . " - CPF: " . htmlspecialchars($row['cpf']) . "</option>";
    }
} else {
    $clienteOptions = '<option value="">Nenhum cliente cadastrado</option>';
}

$cargoOptions = '';
$result = select("Cargo");
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $cargoOptions .= "<option value='" . $row['codigo'] . "'>" . htmlspecialchars($row['nome']) . "</option>";
    }
} else {
    $cargoOptions = '<option value="">Nenhum cargo cadastrado</option>';
}

$contaOptions = '';
$result = selectInner(['Conta_Sistema', 'Funcionario'], [
    'Conta_Sistema.codigo',
    'Funcionario.nome',
    'Funcionario.cpf'
]);
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $contaOptions .= "<option value='" . $row['codigo'] . "'>" . htmlspecialchars($row['nome']) . " - CPF: " . htmlspecialchars($row['cpf']) . "</option>";
    }
} else {
    $contaOptions = '<option value="">Nenhuma conta cadastrada</option>';
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
                        <a href="../select/selectChamado.php" class="btn-modern btn-modern-secondary">
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
    
    // Atualizar preview do BO
    if (boInput && boPreview) {
        boInput.addEventListener("input", function() {
            boPreview.textContent = this.value || "—";
        });
    }
    
    // Atualizar preview do cliente
    if (clienteSelect && clientePreview) {
        clienteSelect.addEventListener("change", function() {
            clientePreview.textContent = this.options[this.selectedIndex].text || "—";
        });
    }
    
    // Atualizar preview do cargo
    if (cargoSelect && cargoPreview) {
        cargoSelect.addEventListener("change", function() {
            cargoPreview.textContent = this.options[this.selectedIndex].text || "—";
        });
    }
    
});
</script>';

include '../template.php';
?>
