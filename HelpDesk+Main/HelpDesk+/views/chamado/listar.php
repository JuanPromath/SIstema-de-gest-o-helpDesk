<?php
$content = '
<div class="dashboard-header fade-in">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1 class="text-gradient">
                <i class="bi bi-ticket-perforated"></i>
                Gerenciar Chamados
            </h1>
            <p class="text-muted">Visualize e gerencie todos os chamados do sistema</p>
        </div>
        <a href="?controller=ChamadoController&action=criar" class="btn-modern btn-modern-primary">
            <i class="bi bi-plus-circle"></i>
            Novo Chamado
        </a>
    </div>
</div>

' . ($successMsg ? '<div class="row mb-3">' . $successMsg . '</div>' : '') . '
' . ($errorMsg ? '<div class="row mb-3">' . $errorMsg . '</div>' : '') . '

<div class="row mb-4 fade-in fade-in-delay-1">
    <div class="col-12">
        <div class="card-modern">
            <div class="card-body">
                <div class="d-flex gap-2 flex-wrap">
                    <a href="?controller=ChamadoController&action=listar" class="btn-modern btn-modern-secondary">
                        <i class="bi bi-list-ul"></i> Todos
                    </a>
                    <a href="?controller=ChamadoController&action=listar&status=aberto" class="btn-modern btn-modern-outline">
                        <i class="bi bi-circle-fill"></i> Abertos
                    </a>
                    <a href="?controller=ChamadoController&action=listar&status=em andamento" class="btn-modern btn-modern-outline">
                        <i class="bi bi-clock-history"></i> Em Andamento
                    </a>
                    <a href="?controller=ChamadoController&action=listar&status=fechado" class="btn-modern btn-modern-outline">
                        <i class="bi bi-check-circle-fill"></i> Fechados
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">';

if (count($chamados) > 0) {
    foreach ($chamados as $index => $chamado) {
        $status = strtolower($chamado->status);
        $badgeClass = '';
        $badgeIcon = '';
        
        if ($status === 'aberto') {
            $badgeClass = 'badge-open';
            $badgeIcon = 'bi-circle-fill';
        } elseif ($status === 'em andamento') {
            $badgeClass = 'badge-progress';
            $badgeIcon = 'bi-clock-history';
        } else {
            $badgeClass = 'badge-closed';
            $badgeIcon = 'bi-check-circle-fill';
        }
        
        $dataAbertura = $chamado->data_abertura ? date('d/m/Y H:i', strtotime($chamado->data_abertura)) : '-';
        $dataFechamento = $chamado->data_fechamento ? date('d/m/Y H:i', strtotime($chamado->data_fechamento)) : '-';
        
        $content .= '
        <div class="col-12 col-md-6 col-lg-4 fade-in fade-in-delay-' . (($index % 3) + 1) . '">
            <div class="card-modern h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-ticket-perforated text-primary"></i>
                            Chamado #' . htmlspecialchars($chamado->codigo) . '
                        </h5>
                        <span class="badge-modern ' . $badgeClass . '">
                            <i class="bi ' . $badgeIcon . '"></i>
                            ' . ucfirst($chamado->status) . '
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <p class="text-muted mb-2" style="font-size: 0.9rem;">
                            <i class="bi bi-file-earmark-text"></i>
                            <strong>Descrição:</strong>
                        </p>
                        <p class="mb-0" style="font-size: 0.95rem;">' . htmlspecialchars(substr($chamado->bo, 0, 100)) . (strlen($chamado->bo) > 100 ? '...' : '') . '</p>
                    </div>
                    
                    <div class="border-top pt-3 mb-3">
                        <div class="mb-2">
                            <small class="text-muted d-block mb-1">
                                <i class="bi bi-person"></i> <strong>Cliente:</strong>
                            </small>
                            <span>' . htmlspecialchars($chamado->nome_cliente ?? 'N/A') . '</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block mb-1">
                                <i class="bi bi-person-badge"></i> <strong>Atendente:</strong>
                            </small>
                            <span>' . htmlspecialchars($chamado->nome_funcionario ?? 'N/A') . '</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block mb-1">
                                <i class="bi bi-briefcase"></i> <strong>Cargo:</strong>
                            </small>
                            <span>' . htmlspecialchars($chamado->cargo ?? 'N/A') . '</span>
                        </div>
                    </div>
                    
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">
                                <i class="bi bi-calendar-event"></i> Abertura:
                            </small>
                            <span style="font-size: 0.85rem;">' . $dataAbertura . '</span>
                        </div>
                        ' . ($chamado->data_fechamento ? '
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bi bi-calendar-check"></i> Fechamento:
                            </small>
                            <span style="font-size: 0.85rem;">' . $dataFechamento . '</span>
                        </div>' : '') . '
                    </div>
                    
                    <div class="mt-3 d-grid gap-2">
                        <a href="?controller=ChamadoController&action=editar&id=' . $chamado->codigo . '" class="btn-modern btn-modern-secondary btn-sm">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <a href="?controller=ChamadoController&action=excluir&id=' . $chamado->codigo . '" class="btn-modern btn-modern-outline btn-sm" style="color: #e74c3c; border-color: #e74c3c;" onclick="return confirm(\'Tem certeza que deseja excluir este chamado?\');">
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
                <i class="bi bi-inbox" style="font-size: 4rem; color: var(--text-light);"></i>
                <h5 class="mt-3 text-muted">Nenhum chamado encontrado</h5>
                <p class="text-muted">Comece criando um novo chamado</p>
                <a href="?controller=ChamadoController&action=criar" class="btn-modern btn-modern-primary mt-3">
                    <i class="bi bi-plus-circle"></i> Criar Primeiro Chamado
                </a>
            </div>
        </div>
    </div>';
}

$content .= '
</div>';

include '../../template.php';
?>
