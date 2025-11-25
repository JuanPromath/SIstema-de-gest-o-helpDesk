<?php
$content = '
<div class="dashboard-header fade-in">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1 class="text-gradient">
                <i class="bi bi-person-badge"></i>
                Gerenciar Funcionários
            </h1>
            <p class="text-muted">Visualize e gerencie todos os funcionários cadastrados</p>
        </div>
        <a href="?controller=FuncionarioController&action=criar" class="btn-modern btn-modern-primary">
            <i class="bi bi-plus-circle"></i>
            Novo Funcionário
        </a>
    </div>
</div>

' . ($successMsg ? '<div class="row mb-3">' . $successMsg . '</div>' : '') . '
' . ($errorMsg ? '<div class="row mb-3">' . $errorMsg . '</div>' : '') . '

<div class="row g-4">';

if (count($funcionarios) > 0) {
    foreach ($funcionarios as $index => $funcionario) {
        $content .= '
        <div class="col-12 col-md-6 col-lg-4 fade-in fade-in-delay-' . (($index % 3) + 1) . '">
            <div class="card-modern h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="dashboard-card-icon me-3" style="width: 50px; height: 50px; font-size: 1.5rem;">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">' . htmlspecialchars($funcionario->nome) . '</h5>
                            <small class="text-muted">ID: #' . htmlspecialchars($funcionario->codigo) . '</small>
                        </div>
                    </div>
                    
                    <div class="border-top pt-3 mb-3">
                        <div class="mb-2">
                            <small class="text-muted d-block mb-1">
                                <i class="bi bi-envelope"></i> <strong>Email:</strong>
                            </small>
                            <span style="font-size: 0.95rem;">' . htmlspecialchars($funcionario->email) . '</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block mb-1">
                                <i class="bi bi-credit-card"></i> <strong>CPF:</strong>
                            </small>
                            <span style="font-size: 0.95rem;">' . htmlspecialchars($funcionario->cpf) . '</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block mb-1">
                                <i class="bi bi-briefcase"></i> <strong>Cargo:</strong>
                            </small>
                            <span class="badge-modern badge-progress" style="font-size: 0.85rem;">
                                ' . htmlspecialchars($funcionario->cargo_nome ?? 'N/A') . '
                            </span>
                        </div>
                    </div>
                    
                    <div class="mt-3 d-grid gap-2">
                        <a href="?controller=FuncionarioController&action=editar&id=' . $funcionario->codigo . '" class="btn-modern btn-modern-secondary btn-sm">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <a href="?controller=FuncionarioController&action=excluir&id=' . $funcionario->codigo . '" class="btn-modern btn-modern-outline btn-sm" style="color: #e74c3c; border-color: #e74c3c;" onclick="return confirm(\'Tem certeza que deseja excluir este funcionário? A conta também será excluída.\');">
                            <i class="bi bi-trash"></i> Excluir
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
                <i class="bi bi-person-badge" style="font-size: 4rem; color: var(--text-light);"></i>
                <h5 class="mt-3 text-muted">Nenhum funcionário encontrado</h5>
                <p class="text-muted">Comece cadastrando um novo funcionário</p>
                <a href="?controller=FuncionarioController&action=criar" class="btn-modern btn-modern-primary mt-3">
                    <i class="bi bi-plus-circle"></i> Cadastrar Primeiro Funcionário
                </a>
            </div>
        </div>
    </div>';
}

$content .= '
</div>';

include '../../template.php';
?>

