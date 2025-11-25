<?php
// Buscar dados do funcionário
$funcionario = Funcionario::buscarPorId($conta->Id_funcionario);

$content = '
<div class="dashboard-header fade-in">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1 class="text-gradient">
                <i class="bi bi-exclamation-triangle text-warning"></i>
                Excluir Conta
            </h1>
            <p class="text-muted">Confirme a exclusão desta conta</p>
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
                <div class="alert alert-warning mb-4">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Atenção!</strong> Esta ação não pode ser desfeita.
                </div>
                
                <div class="mb-4 p-3" style="background: var(--bg-tertiary); border-radius: 8px;">
                    <h5 class="mb-3">Dados da Conta</h5>
                    <div class="mb-2">
                        <small class="text-muted d-block mb-1">Funcionário:</small>
                        <strong>' . htmlspecialchars($funcionario->nome ?? 'N/A') . '</strong>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted d-block mb-1">ID Funcionário:</small>
                        <span>#' . htmlspecialchars($conta->Id_funcionario) . '</span>
                    </div>
                    <div>
                        <small class="text-muted d-block mb-1">ID Conta:</small>
                        <span>#' . htmlspecialchars($conta->codigo) . '</span>
                    </div>
                </div>
                
                <form action="?controller=ContaController&action=excluir&id=' . $conta->codigo . '" method="post">
                    <input type="hidden" name="confirmar" value="sim">
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn-modern" style="background: #e74c3c; color: white; border: none;">
                            <i class="bi bi-trash"></i>
                            Confirmar Exclusão
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

