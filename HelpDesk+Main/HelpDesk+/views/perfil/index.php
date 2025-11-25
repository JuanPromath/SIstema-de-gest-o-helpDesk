<?php
require_once __DIR__ . '/../../require_login.php';
require_once __DIR__ . '/../../conexao.php';
require_once __DIR__ . '/../../models/Funcionario.php';
require_once __DIR__ . '/../../models/Conta.php';

// Buscar dados do usuário logado
$funcionarioId = $_SESSION['usuario'] ?? 0;
$funcionario = Funcionario::buscarPorId($funcionarioId);
$conta = Conta::buscarPorFuncionarioId($funcionarioId);

if (!$funcionario) {
    header('Location: index.php');
    exit;
}

// Buscar estatísticas do funcionário
$chamadosAtendidos = mysqli_num_rows(selectWhere('Chamado', ['codigo'], "Id_funcionario = $funcionarioId"));
$chamadosAbertos = mysqli_num_rows(selectWhere('Chamado', ['codigo'], "Id_funcionario = $funcionarioId AND status = 'aberto'"));
$chamadosProgresso = mysqli_num_rows(selectWhere('Chamado', ['codigo'], "Id_funcionario = $funcionarioId AND status = 'em andamento'"));
$chamadosFechados = mysqli_num_rows(selectWhere('Chamado', ['codigo'], "Id_funcionario = $funcionarioId AND status = 'fechado'"));

// Verificar se é admin
require_once __DIR__ . '/../../admin_functions.php';
$isAdmin = isAdmin();

// Mensagens de feedback
$successMsg = '';
if (isset($_GET['success'])) {
    if ($_GET['success'] === 'updated') {
        $successMsg = '<div class="alert alert-success fade-in"><i class="bi bi-check-circle"></i> Perfil atualizado com sucesso!</div>';
    } elseif ($_GET['success'] === 'password_changed') {
        $successMsg = '<div class="alert alert-success fade-in"><i class="bi bi-check-circle"></i> Senha alterada com sucesso!</div>';
    }
}

$content = '
' . ($successMsg ? '<div class="row mb-3">' . $successMsg . '</div>' : '') . '
<div class="dashboard-header fade-in">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1 class="text-gradient">
                <i class="bi bi-person-circle"></i>
                Meu Perfil
            </h1>
            <p class="text-muted">Gerencie suas informações pessoais e preferências</p>
        </div>
        <div class="d-flex gap-2">
            <a href="?controller=PerfilController&action=editar" class="btn-modern btn-modern-primary">
                <i class="bi bi-pencil"></i>
                Editar Perfil
            </a>
            <a href="?controller=PerfilController&action=alterarSenha" class="btn-modern btn-modern-secondary">
                <i class="bi bi-key"></i>
                Alterar Senha
            </a>
        </div>
    </div>
</div>

<!-- Informações do Usuário -->
<div class="row g-4 mb-4">
    <div class="col-12 col-md-4 fade-in">
        <div class="card-modern text-center">
            <div class="card-body p-4">
                <div class="mb-3">
                    <img src="https://ui-avatars.com/api/?name=' . urlencode($funcionario->nome) . '&background=3498db&color=fff&size=120" 
                         alt="Avatar" 
                         class="rounded-circle border border-3 border-primary" 
                         style="width: 120px; height: 120px;">
                </div>
                <h4 class="mb-1">' . htmlspecialchars($funcionario->nome) . '</h4>
                <p class="text-muted mb-2">' . htmlspecialchars($funcionario->cargo_nome ?? 'N/A') . '</p>
                ' . ($isAdmin ? '<span class="badge-modern badge-progress"><i class="bi bi-shield-check"></i> Administrador</span>' : '') . '
                <div class="mt-3 pt-3 border-top">
                    <small class="text-muted d-block mb-1">ID do Funcionário</small>
                    <strong>#' . htmlspecialchars($funcionario->codigo) . '</strong>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-md-8 fade-in fade-in-delay-1">
        <div class="card-modern">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="bi bi-info-circle text-primary"></i>
                    Informações Pessoais
                </h5>
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted d-block mb-1">
                                <i class="bi bi-envelope"></i> Email
                            </small>
                            <strong>' . htmlspecialchars($funcionario->email) . '</strong>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted d-block mb-1">
                                <i class="bi bi-credit-card"></i> CPF
                            </small>
                            <strong>' . htmlspecialchars($funcionario->cpf) . '</strong>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted d-block mb-1">
                                <i class="bi bi-briefcase"></i> Cargo
                            </small>
                            <strong>' . htmlspecialchars($funcionario->cargo_nome ?? 'N/A') . '</strong>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted d-block mb-1">
                                <i class="bi bi-shield-lock"></i> Status da Conta
                            </small>
                            <strong class="text-success">Ativa</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estatísticas -->
<div class="row g-4 mb-4">
    <div class="col-12 fade-in fade-in-delay-2">
        <div class="card-modern">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="bi bi-graph-up text-primary"></i>
                    Minhas Estatísticas
                </h5>
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="dashboard-card-icon mx-auto mb-2" style="width: 50px; height: 50px; background: linear-gradient(135deg, #3498db, #2980b9);">
                                <i class="bi bi-ticket-perforated"></i>
                            </div>
                            <h3 class="mb-0">' . $chamadosAtendidos . '</h3>
                            <small class="text-muted">Total de Chamados</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="dashboard-card-icon mx-auto mb-2" style="width: 50px; height: 50px; background: linear-gradient(135deg, #e74c3c, #c0392b);">
                                <i class="bi bi-circle-fill"></i>
                            </div>
                            <h3 class="mb-0" style="color: #e74c3c;">' . $chamadosAbertos . '</h3>
                            <small class="text-muted">Abertos</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="dashboard-card-icon mx-auto mb-2" style="width: 50px; height: 50px; background: linear-gradient(135deg, #f39c12, #e67e22);">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <h3 class="mb-0" style="color: #f39c12;">' . $chamadosProgresso . '</h3>
                            <small class="text-muted">Em Andamento</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="dashboard-card-icon mx-auto mb-2" style="width: 50px; height: 50px; background: linear-gradient(135deg, #27ae60, #229954);">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <h3 class="mb-0" style="color: #27ae60;">' . $chamadosFechados . '</h3>
                            <small class="text-muted">Fechados</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ações Rápidas -->
<div class="row g-4">
    <div class="col-12 col-md-6 fade-in fade-in-delay-3">
        <div class="card-modern">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="bi bi-lightning-charge text-primary"></i>
                    Ações Rápidas
                </h5>
                <div class="d-grid gap-2">
                    <a href="?controller=ChamadoController&action=criar" class="btn-modern btn-modern-primary">
                        <i class="bi bi-plus-circle"></i>
                        Criar Novo Chamado
                    </a>
                    <a href="?controller=ChamadoController&action=listar&meus=true" class="btn-modern btn-modern-secondary">
                        <i class="bi bi-list-ul"></i>
                        Meus Chamados
                    </a>
                    <a href="?controller=ClienteController&action=listar" class="btn-modern btn-modern-outline">
                        <i class="bi bi-people"></i>
                        Ver Clientes
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-md-6 fade-in fade-in-delay-4">
        <div class="card-modern">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="bi bi-gear text-primary"></i>
                    Configurações
                </h5>
                <div class="list-group list-group-flush">
                    <a href="?controller=PerfilController&action=editar" class="list-group-item list-group-item-action border-0 px-0">
                        <i class="bi bi-person text-primary me-2"></i>
                        Editar Informações Pessoais
                    </a>
                    <a href="?controller=PerfilController&action=alterarSenha" class="list-group-item list-group-item-action border-0 px-0">
                        <i class="bi bi-key text-primary me-2"></i>
                        Alterar Senha
                    </a>
                    ' . ($isAdmin ? '
                    <a href="?controller=CargoController&action=listar" class="list-group-item list-group-item-action border-0 px-0">
                        <i class="bi bi-briefcase text-primary me-2"></i>
                        Gerenciar Cargos
                    </a>
                    <a href="select/selectConta.php" class="list-group-item list-group-item-action border-0 px-0">
                        <i class="bi bi-shield-lock text-primary me-2"></i>
                        Gerenciar Contas
                    </a>' : '') . '
                    <hr class="my-2">
                    <a href="logout.php" class="list-group-item list-group-item-action border-0 px-0 text-danger">
                        <i class="bi bi-box-arrow-right text-danger me-2"></i>
                        Sair do Sistema
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>';

include '../../template.php';
?>

