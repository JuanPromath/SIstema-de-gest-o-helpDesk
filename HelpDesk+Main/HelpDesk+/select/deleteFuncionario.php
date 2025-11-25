<?php
require_once '../require_login.php';
require_once '../conexao.php';

$feedback = '';
$funcionarioId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar']) && $_POST['confirmar'] === 'sim') {
    $funcionarioId = (int)$_POST['id'];
    
    // Verificar se há chamados vinculados
    $chamados = selectWhere('Chamado', ['codigo'], "Id_funcionario = $funcionarioId");
    
    if ($chamados && mysqli_num_rows($chamados) > 0) {
        $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-exclamation-triangle"></i> Não é possível excluir este funcionário pois existem chamados vinculados a ele.</div>';
    } else {
        $result = delete('Funcionario', "codigo = $funcionarioId");
        
        if ($result) {
            header('Location: selectFuncionario.php?success=deleted');
            exit;
        } else {
            $feedback = '<div class="alert alert-danger fade-in"><i class="bi bi-x-circle"></i> Erro ao excluir funcionário.</div>';
        }
    }
}

// Buscar dados do funcionário
$funcionario = null;
if ($funcionarioId > 0) {
    $query = "SELECT f.codigo, f.nome, f.cpf, f.email, c.nome as cargo_nome 
              FROM Funcionario f 
              INNER JOIN Cargo c ON f.id_cargo = c.codigo 
              WHERE f.codigo = $funcionarioId";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $funcionario = mysqli_fetch_assoc($result);
    }
}

if (!$funcionario) {
    header('Location: selectFuncionario.php?error=not_found');
    exit;
}

$content = '
<div class="dashboard-header fade-in">
    <h1 class="text-gradient">
        <i class="bi bi-trash"></i>
        Excluir Funcionário
    </h1>
    <p class="text-muted">Confirme a exclusão do funcionário</p>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-md-6 fade-in">
        <div class="card-modern">
            <div class="card-body">
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Atenção!</strong> Esta ação não pode ser desfeita. A conta do sistema também será excluída.
                </div>
                
                <div class="mb-4">
                    <h5>Deseja realmente excluir o funcionário?</h5>
                    <div class="mt-3 p-3 bg-light rounded">
                        <strong>Nome:</strong> ' . htmlspecialchars($funcionario['nome']) . '<br>
                        <strong>CPF:</strong> ' . htmlspecialchars($funcionario['cpf']) . '<br>
                        <strong>Email:</strong> ' . htmlspecialchars($funcionario['email']) . '<br>
                        <strong>Cargo:</strong> ' . htmlspecialchars($funcionario['cargo_nome']) . '<br>
                        <strong>ID:</strong> #' . htmlspecialchars($funcionario['codigo']) . '
                    </div>
                </div>
                
                ' . $feedback . '
                
                <form method="post" class="d-inline">
                    <input type="hidden" name="id" value="' . $funcionario['codigo'] . '">
                    <input type="hidden" name="confirmar" value="sim">
                    <button type="submit" class="btn-modern btn-modern-primary me-2">
                        <i class="bi bi-check-circle"></i> Sim, excluir
                    </button>
                </form>
                
                <a href="selectFuncionario.php" class="btn-modern btn-modern-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
            </div>
        </div>
    </div>
</div>';

include '../template.php';
?>

