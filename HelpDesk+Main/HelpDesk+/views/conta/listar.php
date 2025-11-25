<?php
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
        <a href="?controller=ContaController&action=criar" class="btn-modern btn-modern-primary">
            <i class="bi bi-plus-circle"></i>
            Nova Conta
        </a>
    </div>
</div>

' . ($successMsg ? '<div class="row mb-3">' . $successMsg . '</div>' : '') . '
' . ($errorMsg ? '<div class="row mb-3">' . $errorMsg . '</div>' : '') . '

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
                            <h5 class="card-title mb-0">' . htmlspecialchars($conta->funcionario_nome ?? 'N/A') . '</h5>
                            <small class="text-muted">Conta ID: #' . htmlspecialchars($conta->codigo) . '</small>
                        </div>
                    </div>
                    
                    <div class="border-top pt-3 mb-3">
                        <div class="mb-2">
                            <small class="text-muted d-block mb-1">
                                <i class="bi bi-person-badge"></i> <strong>ID Funcionário:</strong>
                            </small>
                            <span style="font-size: 0.95rem;">#' . htmlspecialchars($conta->Id_funcionario) . '</span>
                        </div>
                    </div>
                    
                    <div class="mt-3 d-grid gap-2">
                        <a href="?controller=ContaController&action=editar&id=' . $conta->codigo . '" class="btn-modern btn-modern-secondary btn-sm">
                            <i class="bi bi-pencil"></i> Alterar Senha
                        </a>
                        <a href="?controller=ContaController&action=excluir&id=' . $conta->codigo . '" class="btn-modern btn-modern-outline btn-sm" style="color: #e74c3c; border-color: #e74c3c;" onclick="return confirm(\'Tem certeza que deseja excluir esta conta?\');">
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
                <i class="bi bi-shield-lock" style="font-size: 4rem; color: var(--text-light);"></i>
                <h5 class="mt-3 text-muted">Nenhuma conta encontrada</h5>
                <p class="text-muted">Comece criando uma nova conta</p>
                <a href="?controller=ContaController&action=criar" class="btn-modern btn-modern-primary mt-3">
                    <i class="bi bi-plus-circle"></i> Criar Primeira Conta
                </a>
            </div>
        </div>
    </div>';
}

$content .= '
</div>';

include '../../template.php';
?>

