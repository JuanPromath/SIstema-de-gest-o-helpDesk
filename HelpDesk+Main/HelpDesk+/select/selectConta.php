<?php
require_once '../require_login.php';
require_once '../conexao.php';

// Buscar contas com funcionários
$result = selectInner(['Conta_Sistema', 'funcionario'], [
    'Conta_Sistema.codigo',
    'funcionario.nome',
    'funcionario.cpf',
    'funcionario.email',
    'Conta_Sistema.Id_funcionario as funcionarioID'
]);

$contas = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $contas[] = $row;
    }
}

$content = '
<div class="dashboard-header fade-in">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1 class="text-gradient">
                <i class="bi bi-shield-lock"></i>
                Gerenciar Contas
            </h1>
            <p class="text-muted">Visualize e gerencie todas as contas do sistema</p>
        </div>
        <a href="../create/createConta.php" class="btn-modern btn-modern-primary">
            <i class="bi bi-plus-circle"></i>
            Nova Conta
        </a>
    </div>
</div>

<div class="row g-4">';

if (count($contas) > 0) {
    foreach ($contas as $index => $conta) {
        $content .= '
        <div class="col-12 col-md-6 col-lg-4 fade-in fade-in-delay-' . (($index % 3) + 1) . '">
            <div class="card-modern h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="dashboard-card-icon me-3" style="width: 50px; height: 50px; font-size: 1.5rem; background: linear-gradient(135deg, #16a085, #138d75);">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">' . htmlspecialchars($conta['nome']) . '</h5>
                            <small class="text-muted">Conta ID: #' . htmlspecialchars($conta['codigo']) . '</small>
                        </div>
                    </div>
                    
                    <div class="border-top pt-3 mb-3">
                        <div class="mb-2">
                            <small class="text-muted d-block mb-1">
                                <i class="bi bi-envelope"></i> <strong>Email:</strong>
                            </small>
                            <span style="font-size: 0.95rem;">' . htmlspecialchars($conta['email']) . '</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block mb-1">
                                <i class="bi bi-credit-card"></i> <strong>CPF:</strong>
                            </small>
                            <span style="font-size: 0.95rem;">' . htmlspecialchars($conta['cpf']) . '</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block mb-1">
                                <i class="bi bi-person-badge"></i> <strong>ID Funcionário:</strong>
                            </small>
                            <span style="font-size: 0.95rem;">#' . htmlspecialchars($conta['funcionarioID']) . '</span>
                        </div>
                    </div>
                    
                    <div class="mt-3 d-grid gap-2">
                        <a href="../select/update.php?table=Conta_Sistema&id=' . $conta['codigo'] . '" class="btn-modern btn-modern-secondary btn-sm">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                    </div>
                </div>
            </div>
        </div>';
    }
} else {
    $content .= '
    <div class="col-12 fade-in">
        <div class="card-modern">
            <div class="card-body text-center py-5">
                <i class="bi bi-shield-lock" style="font-size: 4rem; color: var(--text-light);"></i>
                <h5 class="mt-3 text-muted">Nenhuma conta encontrada</h5>
                <p class="text-muted">Comece criando uma nova conta</p>
                <a href="../create/createConta.php" class="btn-modern btn-modern-primary mt-3">
                    <i class="bi bi-plus-circle"></i> Criar Primeira Conta
                </a>
            </div>
        </div>
    </div>';
}

$content .= '
</div>';

include '../template.php';
?>
