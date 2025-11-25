<?php
// Buscar estatísticas dos cargos
require_once __DIR__ . '/../../conexao.php';
$stats = [];
foreach ($cargos as $cargo) {
    $funcCount = mysqli_num_rows(selectWhere('Funcionario', ['codigo'], "id_cargo = " . $cargo->codigo));
    $stats[$cargo->codigo] = $funcCount;
}

$totalFuncionarios = array_sum($stats);
$cargoMaisUsado = null;
$maxFunc = 0;
foreach ($stats as $cargoId => $count) {
    if ($count > $maxFunc) {
        $maxFunc = $count;
        $cargoMaisUsado = $cargoId;
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
            <p class="text-muted">Visualize e gerencie todos os cargos cadastrados no sistema</p>
            ' . ($isAdmin ? '<span class="badge-modern badge-progress mt-2"><i class="bi bi-shield-check"></i> Modo Administrador</span>' : '') . '
        </div>
        ' . ($isAdmin ? '<a href="?controller=CargoController&action=criar" class="btn-modern btn-modern-primary">
            <i class="bi bi-plus-circle"></i>
            Novo Cargo
        </a>' : '') . '
    </div>
</div>

' . ($successMsg ? '<div class="row mb-3">' . $successMsg . '</div>' : '') . '
' . ($errorMsg ? '<div class="row mb-3">' . $errorMsg . '</div>' : '') . '

<!-- Estatísticas -->
<div class="row g-4 mb-4 fade-in">
    <div class="col-12 col-md-4">
        <div class="card-modern">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="dashboard-card-icon me-3" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                        <i class="bi bi-briefcase"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">' . count($cargos) . '</h3>
                        <small class="text-muted">Total de Cargos</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card-modern">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="dashboard-card-icon me-3" style="background: linear-gradient(135deg, #16a085, #138d75);">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">' . $totalFuncionarios . '</h3>
                        <small class="text-muted">Funcionários Vinculados</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card-modern">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="dashboard-card-icon me-3" style="background: linear-gradient(135deg, #9b59b6, #8e44ad);">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">' . (count($cargos) > 0 ? number_format(($totalFuncionarios / count($cargos)), 1) : '0') . '</h3>
                        <small class="text-muted">Média por Cargo</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtros e Busca -->
<div class="row mb-4 fade-in">
    <div class="col-12">
        <div class="card-modern">
            <div class="card-body">
                <div class="row align-items-end">
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                        <label class="form-label small text-muted mb-2">
                            <i class="bi bi-search"></i> Buscar Cargo
                        </label>
                        <input type="text" class="form-control-modern" id="searchCargo" placeholder="Digite o nome do cargo...">
                    </div>
                    <div class="col-12 col-md-3 mb-3 mb-md-0">
                        <label class="form-label small text-muted mb-2">
                            <i class="bi bi-funnel"></i> Filtrar por
                        </label>
                        <select class="form-control-modern" id="filterCargo">
                            <option value="all">Todos os Cargos</option>
                            <option value="with">Com Funcionários</option>
                            <option value="without">Sem Funcionários</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label small text-muted mb-2">
                            <i class="bi bi-sort-alpha-down"></i> Ordenar
                        </label>
                        <select class="form-control-modern" id="sortCargo">
                            <option value="name">Nome (A-Z)</option>
                            <option value="name-desc">Nome (Z-A)</option>
                            <option value="func">Mais Funcionários</option>
                            <option value="func-desc">Menos Funcionários</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4" id="cargosContainer">';

if (count($cargos) > 0) {
    foreach ($cargos as $index => $cargo) {
        $funcCount = $stats[$cargo->codigo] ?? 0;
        $isMostUsed = ($cargo->codigo == $cargoMaisUsado && $maxFunc > 0);
        
        $content .= '
        <div class="col-12 col-md-6 col-lg-4 fade-in fade-in-delay-' . (($index % 3) + 1) . '" data-cargo-name="' . strtolower(htmlspecialchars($cargo->nome)) . '" data-func-count="' . $funcCount . '">
            <div class="card-modern h-100 ' . ($isMostUsed ? 'border-primary' : '') . '" style="' . ($isMostUsed ? 'border-width: 2px;' : '') . '">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="dashboard-card-icon me-3" style="width: 50px; height: 50px; font-size: 1.5rem;">
                            <i class="bi bi-briefcase"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0">' . htmlspecialchars($cargo->nome) . '</h5>
                            <small class="text-muted">ID: #' . htmlspecialchars($cargo->codigo) . '</small>
                            ' . ($isMostUsed ? '<span class="badge bg-primary ms-2" style="font-size: 0.7rem;"><i class="bi bi-star-fill"></i> Mais Usado</span>' : '') . '
                        </div>
                    </div>
                    
                    <div class="border-top pt-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">
                                <i class="bi bi-people"></i> <strong>Funcionários:</strong>
                            </small>
                            <span class="badge-modern ' . ($funcCount > 0 ? 'badge-progress' : 'badge-closed') . '">' . $funcCount . '</span>
                        </div>
                        <div class="progress" style="height: 6px; background: var(--bg-tertiary);">
                            <div class="progress-bar" role="progressbar" style="width: ' . ($totalFuncionarios > 0 ? ($funcCount / $totalFuncionarios * 100) : 0) . '%; background: linear-gradient(90deg, #3498db, #2980b9);" aria-valuenow="' . $funcCount . '" aria-valuemin="0" aria-valuemax="' . $totalFuncionarios . '"></div>
                        </div>
                    </div>
                    
                    <div class="mt-3 d-grid gap-2">
                        ' . ($isAdmin ? '
                        <div class="btn-group" role="group">
                            <a href="?controller=CargoController&action=editar&id=' . $cargo->codigo . '" class="btn-modern btn-modern-secondary btn-sm flex-fill">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                            <a href="?controller=FuncionarioController&action=listar&cargo=' . $cargo->codigo . '" class="btn-modern btn-modern-outline btn-sm flex-fill" title="Ver Funcionários">
                                <i class="bi bi-people"></i>
                            </a>
                        </div>
                        <a href="?controller=CargoController&action=excluir&id=' . $cargo->codigo . '" class="btn-modern btn-modern-outline btn-sm" style="color: #e74c3c; border-color: #e74c3c;" onclick="return confirm(\'Tem certeza que deseja excluir este cargo?\');">
                            <i class="bi bi-trash"></i> Excluir
                        </a>' : '<span class="text-muted small text-center d-block py-2">Apenas administradores podem editar cargos</span>') . '
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
                ' . ($isAdmin ? '<a href="?controller=CargoController&action=criar" class="btn-modern btn-modern-primary mt-3">
                    <i class="bi bi-plus-circle"></i> Cadastrar Primeiro Cargo
                </a>' : '') . '
            </div>
        </div>
    </div>';
}

$content .= '
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById("searchCargo");
    const filterSelect = document.getElementById("filterCargo");
    const sortSelect = document.getElementById("sortCargo");
    const container = document.getElementById("cargosContainer");
    const cards = Array.from(container.querySelectorAll(".col-12"));
    
    function filterAndSort() {
        const searchTerm = searchInput.value.toLowerCase();
        const filterValue = filterSelect.value;
        const sortValue = sortSelect.value;
        
        let filtered = cards.filter(card => {
            const name = card.getAttribute("data-cargo-name");
            const funcCount = parseInt(card.getAttribute("data-func-count"));
            
            // Busca
            if (searchTerm && !name.includes(searchTerm)) {
                return false;
            }
            
            // Filtro
            if (filterValue === "with" && funcCount === 0) return false;
            if (filterValue === "without" && funcCount > 0) return false;
            
            return true;
        });
        
        // Ordenação
        filtered.sort((a, b) => {
            const nameA = a.getAttribute("data-cargo-name");
            const nameB = b.getAttribute("data-cargo-name");
            const funcA = parseInt(a.getAttribute("data-func-count"));
            const funcB = parseInt(b.getAttribute("data-func-count"));
            
            switch(sortValue) {
                case "name":
                    return nameA.localeCompare(nameB);
                case "name-desc":
                    return nameB.localeCompare(nameA);
                case "func":
                    return funcB - funcA;
                case "func-desc":
                    return funcA - funcB;
                default:
                    return 0;
            }
        });
        
        // Esconder todos
        cards.forEach(card => card.style.display = "none");
        
        // Mostrar filtrados
        filtered.forEach(card => {
            card.style.display = "block";
        });
        
        // Se não houver resultados
        if (filtered.length === 0) {
            container.innerHTML = \'<div class="col-12 fade-in"><div class="card-modern"><div class="card-body text-center py-5"><i class="bi bi-search" style="font-size: 4rem; color: var(--text-light);"></i><h5 class="mt-3 text-muted">Nenhum cargo encontrado</h5><p class="text-muted">Tente ajustar os filtros de busca</p></div></div></div>\';
        }
    }
    
    searchInput.addEventListener("input", filterAndSort);
    filterSelect.addEventListener("change", filterAndSort);
    sortSelect.addEventListener("change", filterAndSort);
});
</script>';

include '../../template.php';
?>
