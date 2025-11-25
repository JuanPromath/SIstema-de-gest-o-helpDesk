<?php
require_once '../require_login.php';
require_once '../conexao.php';

$feedback = '';
$chamadoId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Buscar dados do chamado
$chamado = null;
if ($chamadoId > 0) {
    $query = "SELECT c.codigo, c.bo, c.status, c.Id_cliente, c.id_cargo, c.Id_conta, c.Id_funcionario,
                     cl.nome as nome_cliente, f.nome as nome_funcionario
              FROM Chamado c
              INNER JOIN Cliente cl ON c.Id_cliente = cl.codigo
              INNER JOIN Funcionario f ON c.Id_funcionario = f.codigo
              INNER JOIN Cargo ca ON c.id_cargo = ca.codigo
              INNER JOIN Conta_Sistema cs ON c.Id_conta = cs.codigo
              WHERE c.codigo = $chamadoId";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $chamado = mysqli_fetch_assoc($result);
    }
}

if (!$chamado && $chamadoId > 0) {
    header('Location: ../select/selectChamado.php?error=not_found');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bo = trim($_POST['bo'] ?? '');
    $status = $_POST['status'] ?? 'aberto';
    $clienteId = (int)($_POST['cliente'] ?? 0);
    $cargoId = (int)($_POST['cargo'] ?? 0);
    $contaId = (int)($_POST['conta'] ?? 0);
    
    if (empty($bo) || $clienteId <= 0 || $cargoId <= 0 || $contaId <= 0) {
        $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Preencha todos os campos obrigatórios.</div>';
    } else {
        $alteracoes = [];
        if ($bo !== $chamado['bo']) $alteracoes['bo'] = $bo;
        if ($status !== $chamado['status']) $alteracoes['status'] = $status;
        if ($clienteId != $chamado['Id_cliente']) $alteracoes['Id_cliente'] = $clienteId;
        if ($cargoId != $chamado['id_cargo']) $alteracoes['id_cargo'] = $cargoId;
        if ($contaId != $chamado['Id_conta']) {
            $alteracoes['Id_conta'] = $contaId;
            // Buscar funcionário da conta
            $contaResult = selectWhere('Conta_Sistema', ['Id_funcionario'], "codigo = $contaId");
            if ($contaResult && mysqli_num_rows($contaResult) > 0) {
                $conta = mysqli_fetch_assoc($contaResult);
                $alteracoes['Id_funcionario'] = $conta['Id_funcionario'];
            }
        }
        
        // Se fechando o chamado, adicionar data de fechamento
        if ($status === 'fechado' && $chamado['status'] !== 'fechado') {
            $alteracoes['data_fechamento'] = date('Y-m-d H:i:s');
        } elseif ($status !== 'fechado' && $chamado['status'] === 'fechado') {
            $alteracoes['data_fechamento'] = null;
        }
        
        if (!empty($alteracoes)) {
            $result = update('Chamado', $alteracoes, "codigo = $chamadoId");
            
            if ($result) {
                header('Location: ../select/selectChamado.php?success=updated');
                exit;
            } else {
                $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-x-circle"></i> Erro ao atualizar chamado.</div>';
            }
        } else {
            $feedback = '<div class="alert alert-info fade-in"><i class="bi bi-info-circle"></i> Nenhuma alteração foi feita.</div>';
        }
    }
}

// Buscar opções
$clienteOptions = '';
$result = select('Cliente', ['codigo', 'nome', 'cpf']);
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $selected = (isset($chamado['Id_cliente']) && $chamado['Id_cliente'] == $row['codigo']) ? 'selected' : '';
        $clienteOptions .= "<option value='" . $row['codigo'] . "' $selected>" . htmlspecialchars($row['nome']) . " - CPF: " . htmlspecialchars($row['cpf']) . "</option>";
    }
}

$cargoOptions = '';
$result = select("Cargo");
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $selected = (isset($chamado['id_cargo']) && $chamado['id_cargo'] == $row['codigo']) ? 'selected' : '';
        $cargoOptions .= "<option value='" . $row['codigo'] . "' $selected>" . htmlspecialchars($row['nome']) . "</option>";
    }
}

$contaOptions = '';
$result = selectInner(['Conta_Sistema', 'Funcionario'], [
    'Conta_Sistema.codigo',
    'Funcionario.nome',
    'Funcionario.cpf'
]);
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $selected = (isset($chamado['Id_conta']) && $chamado['Id_conta'] == $row['codigo']) ? 'selected' : '';
        $contaOptions .= "<option value='" . $row['codigo'] . "' $selected>" . htmlspecialchars($row['nome']) . " - CPF: " . htmlspecialchars($row['cpf']) . "</option>";
    }
}

$content = '
<div class="dashboard-header fade-in">
    <h1 class="text-gradient">
        <i class="bi bi-pencil"></i>
        Editar Chamado #' . $chamadoId . '
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
                                >' . htmlspecialchars($chamado['bo'] ?? '') . '</textarea>
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-6 mb-4">
                            <div class="input-group-modern">
                                <i class="bi bi-flag input-icon"></i>
                                <select class="form-control-modern" id="status" name="status" required>
                                    <option value="aberto" ' . (($chamado['status'] ?? '') === 'aberto' ? 'selected' : '') . '>Aberto</option>
                                    <option value="em andamento" ' . (($chamado['status'] ?? '') === 'em andamento' ? 'selected' : '') . '>Em Andamento</option>
                                    <option value="fechado" ' . (($chamado['status'] ?? '') === 'fechado' ? 'selected' : '') . '>Fechado</option>
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
                        <a href="../select/selectChamado.php" class="btn-modern btn-modern-secondary">
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

