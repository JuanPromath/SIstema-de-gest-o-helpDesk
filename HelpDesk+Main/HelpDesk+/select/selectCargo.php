<?php
require_once '../require_login.php';
require_once '../conexao.php';
require_once '../admin_functions.php';

// Verificar se é administrador
$isAdmin = isAdmin();

// Buscar cargos
$result = select('Cargo', ['*']);
$cargos = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $cargos[] = $row;
    }
}

// Mensagens de feedback
$successMsg = '';
$errorMsg = '';
if (isset($_GET['success'])) {
    if ($_GET['success'] === 'updated') {
        $successMsg = '<div class="alert alert-success fade-in"><i class="bi bi-check-circle"></i> Cargo atualizado com sucesso!</div>';
    } elseif ($_GET['success'] === 'deleted') {
        $successMsg = '<div class="alert alert-success fade-in"><i class="bi bi-check-circle"></i> Cargo excluído com sucesso!</div>';
    } elseif ($_GET['success'] === 'created') {
        $successMsg = '<div class="alert alert-success fade-in"><i class="bi bi-check-circle"></i> Cargo cadastrado com sucesso!</div>';
    }
}
if (isset($_GET['error'])) {
    if ($_GET['error'] === 'not_found') {
        $errorMsg = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Cargo não encontrado.</div>';
    } elseif ($_GET['error'] === 'permission_denied') {
        $errorMsg = '<div class="alert alert-danger fade-in"><i class="bi bi-shield-x"></i> Você não tem permissão para acessar esta página.</div>';
    }
}

$content = '
<div class="dashboard-header fade-in">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1 class="text-gradient">
                <i class="bi bi-briefcase"></i>
                Gerenciar Cargos
            </h1>
            <p class="text-muted">Visualize e gerencie todos os cargos cadastrados</p>
            ' . ($isAdmin ? '<span class="badge-modern badge-progress mt-2"><i class="bi bi-shield-check"></i> Modo Administrador</span>' : '') . '
        </div>
        ' . ($isAdmin ? '<a href="../create/createCargo.php" class="btn-modern btn-modern-primary">
            <i class="bi bi-plus-circle"></i>
            Novo Cargo
        </a>' : '') . '
    </div>
</div>

' . ($successMsg ? '<div class="row mb-3">' . $successMsg . '</div>' : '') . '
' . ($errorMsg ? '<div class="row mb-3">' . $errorMsg . '</div>' : '') . '

<div class="row g-4">';

if (count($cargos) > 0) {
    foreach ($cargos as $index => $cargo) {
        $content .= '
        <div class="col-12 col-md-6 col-lg-4 fade-in fade-in-delay-' . (($index % 3) + 1) . '">
            <div class="card-modern h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="dashboard-card-icon me-3" style="width: 50px; height: 50px; font-size: 1.5rem;">
                            <i class="bi bi-briefcase"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">' . htmlspecialchars($cargo['nome']) . '</h5>
                            <small class="text-muted">ID: #' . htmlspecialchars($cargo['codigo']) . '</small>
                        </div>
                    </div>
                    
                    <div class="mt-3 d-grid gap-2">
                        ' . ($isAdmin ? '
                        <a href="../create/editCargo.php?id=' . $cargo['codigo'] . '" class="btn-modern btn-modern-secondary btn-sm">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <a href="../select/deleteCargo.php?id=' . $cargo['codigo'] . '" class="btn-modern btn-modern-outline btn-sm" style="color: #e74c3c; border-color: #e74c3c;" onclick="return confirm(\'Tem certeza que deseja excluir este cargo?\');">
                            <i class="bi bi-trash"></i> Excluir
                        </a>' : '<span class="text-muted small">Apenas administradores podem editar cargos</span>') . '
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
                <i class="bi bi-briefcase" style="font-size: 4rem; color: var(--text-light);"></i>
                <h5 class="mt-3 text-muted">Nenhum cargo encontrado</h5>
                <p class="text-muted">Comece cadastrando um novo cargo</p>
                <a href="../create/createCargo.php" class="btn-modern btn-modern-primary mt-3">
                    <i class="bi bi-plus-circle"></i> Cadastrar Primeiro Cargo
                </a>
            </div>
        </div>
    </div>';
}

$content .= '
</div>';

include '../template.php';
?>
