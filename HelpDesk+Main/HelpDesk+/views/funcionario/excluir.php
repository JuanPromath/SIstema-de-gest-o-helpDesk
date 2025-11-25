<?php
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
                        <strong>Nome:</strong> ' . htmlspecialchars($funcionario->nome) . '<br>
                        <strong>CPF:</strong> ' . htmlspecialchars($funcionario->cpf) . '<br>
                        <strong>Email:</strong> ' . htmlspecialchars($funcionario->email) . '<br>
                        <strong>Cargo:</strong> ' . htmlspecialchars($funcionario->cargo_nome ?? 'N/A') . '<br>
                        <strong>ID:</strong> #' . htmlspecialchars($funcionario->codigo) . '
                    </div>
                </div>
                
                ' . $feedback . '
                
                <form method="post" class="d-inline">
                    <input type="hidden" name="confirmar" value="sim">
                    <button type="submit" class="btn-modern btn-modern-primary me-2">
                        <i class="bi bi-check-circle"></i> Sim, excluir
                    </button>
                </form>
                
                <a href="?controller=FuncionarioController&action=listar" class="btn-modern btn-modern-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
            </div>
        </div>
    </div>
</div>';

include '../../template.php';
?>

