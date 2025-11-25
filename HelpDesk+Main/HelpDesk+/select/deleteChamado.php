<?php
require_once '../require_login.php';
require_once '../conexao.php';

$feedback = '';
$chamadoId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar']) && $_POST['confirmar'] === 'sim') {
    $chamadoId = (int)$_POST['id'];
    
    $result = delete('Chamado', "codigo = $chamadoId");
    
    if ($result) {
        header('Location: selectChamado.php?success=deleted');
        exit;
    } else {
        $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-x-circle"></i> Erro ao excluir chamado.</div>';
    }
}

// Buscar dados do chamado
$chamado = null;
if ($chamadoId > 0) {
    $query = "SELECT c.codigo, c.bo, c.status, c.data_abertura,
                     cl.nome as nome_cliente, f.nome as nome_funcionario
              FROM Chamado c
              INNER JOIN Cliente cl ON c.Id_cliente = cl.codigo
              INNER JOIN Funcionario f ON c.Id_funcionario = f.codigo
              WHERE c.codigo = $chamadoId";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $chamado = mysqli_fetch_assoc($result);
    }
}

if (!$chamado) {
    header('Location: selectChamado.php?error=not_found');
    exit;
}

$statusBadge = '';
$status = strtolower($chamado['status']);
if ($status === 'aberto') {
    $statusBadge = '<span class="badge-modern badge-open">Aberto</span>';
} elseif ($status === 'em andamento') {
    $statusBadge = '<span class="badge-modern badge-progress">Em Andamento</span>';
} else {
    $statusBadge = '<span class="badge-modern badge-closed">Fechado</span>';
}

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
                        <strong>ID:</strong> #' . htmlspecialchars($chamado['codigo']) . '<br>
                        <strong>Status:</strong> ' . $statusBadge . '<br>
                        <strong>Cliente:</strong> ' . htmlspecialchars($chamado['nome_cliente']) . '<br>
                        <strong>Funcionário:</strong> ' . htmlspecialchars($chamado['nome_funcionario']) . '<br>
                        <strong>Descrição:</strong> ' . htmlspecialchars(substr($chamado['bo'], 0, 100)) . (strlen($chamado['bo']) > 100 ? '...' : '') . '<br>
                        <strong>Data de Abertura:</strong> ' . date('d/m/Y H:i', strtotime($chamado['data_abertura'])) . '
                    </div>
                </div>
                
                ' . $feedback . '
                
                <form method="post" class="d-inline">
                    <input type="hidden" name="id" value="' . $chamado['codigo'] . '">
                    <input type="hidden" name="confirmar" value="sim">
                    <button type="submit" class="btn-modern btn-modern-primary me-2">
                        <i class="bi bi-check-circle"></i> Sim, excluir
                    </button>
                </form>
                
                <a href="selectChamado.php" class="btn-modern btn-modern-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
            </div>
        </div>
    </div>
</div>';

include '../template.php';
?>

