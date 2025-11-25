<?php
$statusBadge = '';
$status = strtolower($chamado->status);
if ($status === 'aberto') {
    $statusBadge = '<span class="badge-modern badge-open">Aberto</span>';
} elseif ($status === 'em andamento') {
    $statusBadge = '<span class="badge-modern badge-progress">Em Andamento</span>';
} else {
    $statusBadge = '<span class="badge-modern badge-closed">Fechado</span>';
}

$dataAbertura = $chamado->data_abertura ? date('d/m/Y H:i', strtotime($chamado->data_abertura)) : '-';

$content = '
<div class="dashboard-header fade-in">
    <h1 class="text-gradient">
        <i class="bi bi-trash"></i>
        Excluir Chamado
    </h1>
    <p class="text-muted">Confirme a exclusão do chamado</p>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-md-6 fade-in">
        <div class="card-modern">
            <div class="card-body">
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Atenção!</strong> Esta ação não pode ser desfeita.
                </div>
                
                <div class="mb-4">
                    <h5>Deseja realmente excluir o chamado?</h5>
                    <div class="mt-3 p-3 bg-light rounded">
                        <strong>ID:</strong> #' . htmlspecialchars($chamado->codigo) . '<br>
                        <strong>Status:</strong> ' . $statusBadge . '<br>
                        <strong>Cliente:</strong> ' . htmlspecialchars($chamado->nome_cliente ?? 'N/A') . '<br>
                        <strong>Funcionário:</strong> ' . htmlspecialchars($chamado->nome_funcionario ?? 'N/A') . '<br>
                        <strong>Descrição:</strong> ' . htmlspecialchars(substr($chamado->bo, 0, 100)) . (strlen($chamado->bo) > 100 ? '...' : '') . '<br>
                        <strong>Data de Abertura:</strong> ' . $dataAbertura . '
                    </div>
                </div>
                
                ' . $feedback . '
                
                <form method="post" class="d-inline">
                    <input type="hidden" name="confirmar" value="sim">
                    <button type="submit" class="btn-modern btn-modern-primary me-2">
                        <i class="bi bi-check-circle"></i> Sim, excluir
                    </button>
                </form>
                
                <a href="?controller=ChamadoController&action=listar" class="btn-modern btn-modern-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
            </div>
        </div>
    </div>
</div>';

include '../../template.php';
?>

