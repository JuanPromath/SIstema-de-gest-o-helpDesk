<?php
// Router para index - redireciona para router.php se houver parâmetros de controller
if (isset($_GET['controller']) || isset($_GET['action'])) {
    require_once 'router.php';
    exit;
}

$protect = true;
if ($protect) require_once 'require_login.php';
require_once 'conexao.php';

// Buscar estatísticas para o dashboard
$totalChamados = mysqli_num_rows(select('Chamado'));
$chamadosAbertos = mysqli_num_rows(selectWhere('Chamado', ['*'], "status = 'aberto'"));
$chamadosProgresso = mysqli_num_rows(selectWhere('Chamado', ['*'], "status = 'em andamento'"));
$chamadosFechados = mysqli_num_rows(selectWhere('Chamado', ['*'], "status = 'fechado'"));
$totalClientes = mysqli_num_rows(select('Cliente'));
$totalFuncionarios = mysqli_num_rows(select('Funcionario'));

$content = '
<div class="dashboard-header fade-in">
    <h1 class="text-gradient">Dashboard</h1>
    <p class="text-muted">Bem-vindo ao HelpDesk+ - Sistema de Gestão</p>
</div>

<div class="stats-grid">
    <div class="dashboard-card fade-in fade-in-delay-1">
        <a href="?controller=ChamadoController&action=listar" class="dashboard-card-link">
            <div class="dashboard-card-icon">
                <i class="bi bi-ticket-perforated"></i>
            </div>
            <div class="dashboard-card-title">Total de Chamados</div>
            <div class="dashboard-card-value">' . $totalChamados . '</div>
        </a>
    </div>
    
    <div class="dashboard-card fade-in fade-in-delay-2">
        <a href="?controller=ChamadoController&action=listar&status=aberto" class="dashboard-card-link">
            <div class="dashboard-card-icon" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                <i class="bi bi-circle-fill"></i>
            </div>
            <div class="dashboard-card-title">Chamados Abertos</div>
            <div class="dashboard-card-value" style="color: #e74c3c;">' . $chamadosAbertos . '</div>
        </a>
    </div>
    
    <div class="dashboard-card fade-in fade-in-delay-3">
        <a href="?controller=ChamadoController&action=listar&status=em andamento" class="dashboard-card-link">
            <div class="dashboard-card-icon" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="dashboard-card-title">Em Andamento</div>
            <div class="dashboard-card-value" style="color: #f39c12;">' . $chamadosProgresso . '</div>
        </a>
    </div>
    
    <div class="dashboard-card fade-in fade-in-delay-4">
        <a href="?controller=ChamadoController&action=listar&status=fechado" class="dashboard-card-link">
            <div class="dashboard-card-icon" style="background: linear-gradient(135deg, #27ae60, #229954);">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="dashboard-card-title">Chamados Fechados</div>
            <div class="dashboard-card-value" style="color: #27ae60;">' . $chamadosFechados . '</div>
        </a>
    </div>
    
    <div class="dashboard-card fade-in fade-in-delay-1">
        <a href="?controller=ClienteController&action=listar" class="dashboard-card-link">
            <div class="dashboard-card-icon" style="background: linear-gradient(135deg, #9b59b6, #8e44ad);">
                <i class="bi bi-people"></i>
            </div>
            <div class="dashboard-card-title">Total de Clientes</div>
            <div class="dashboard-card-value" style="color: #9b59b6;">' . $totalClientes . '</div>
        </a>
    </div>
    
    <div class="dashboard-card fade-in fade-in-delay-2">
        <a href="?controller=FuncionarioController&action=listar" class="dashboard-card-link">
            <div class="dashboard-card-icon" style="background: linear-gradient(135deg, #16a085, #138d75);">
                <i class="bi bi-person-badge"></i>
            </div>
            <div class="dashboard-card-title">Total de Funcionários</div>
            <div class="dashboard-card-value" style="color: #16a085;">' . $totalFuncionarios . '</div>
        </a>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12 col-lg-6 mb-4 fade-in">
        <div class="card-modern">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="bi bi-plus-circle text-primary"></i>
                    Ações Rápidas
                </h5>
                <div class="d-grid gap-2">
                    <a href="?controller=ChamadoController&action=criar" class="btn-modern btn-modern-primary">
                        <i class="bi bi-ticket-perforated"></i>
                        Abrir Novo Chamado
                    </a>
                    <a href="?controller=ClienteController&action=criar" class="btn-modern btn-modern-secondary">
                        <i class="bi bi-person-plus"></i>
                        Cadastrar Cliente
                    </a>
                    <a href="?controller=FuncionarioController&action=criar" class="btn-modern btn-modern-outline">
                        <i class="bi bi-person-badge"></i>
                        Cadastrar Funcionário
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-lg-6 mb-4 fade-in fade-in-delay-1">
        <div class="card-modern">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="bi bi-list-ul text-primary"></i>
                    Gerenciamento
                </h5>
                <div class="list-group list-group-flush">
                    <a href="?controller=ChamadoController&action=listar" class="list-group-item list-group-item-action border-0 px-0">
                        <i class="bi bi-ticket-perforated text-primary me-2"></i>
                        Gerenciar Chamados
                    </a>
                    <a href="?controller=ClienteController&action=listar" class="list-group-item list-group-item-action border-0 px-0">
                        <i class="bi bi-people text-primary me-2"></i>
                        Gerenciar Clientes
                    </a>
                    <a href="?controller=FuncionarioController&action=listar" class="list-group-item list-group-item-action border-0 px-0">
                        <i class="bi bi-person-badge text-primary me-2"></i>
                        Gerenciar Funcionários
                    </a>
                    <a href="?controller=CargoController&action=listar" class="list-group-item list-group-item-action border-0 px-0">
                        <i class="bi bi-briefcase text-primary me-2"></i>
                        Gerenciar Cargos
                    </a>
                    <a href="?controller=ContaController&action=listar" class="list-group-item list-group-item-action border-0 px-0">
                        <i class="bi bi-shield-lock text-primary me-2"></i>
                        Gerenciar Contas
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
';

include 'template.php';
?>
