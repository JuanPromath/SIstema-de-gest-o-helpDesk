<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Cargos - HelpDesk+</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <style>
        #lista-cargo {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .cargo-card {
            background: var(--background-light);
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 8px var(--shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .cargo-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 16px var(--shadow-hover);
        }
        
        .cargo-card h3 {
            color: var(--accent);
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }
        
        .cargo-card p {
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .cargo-card .actions {
            margin-top: 1rem;
            display: flex;
            gap: 0.5rem;
        }
        
        #edit-form {
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <?php session_start(); ?>
    <nav>
        <div class="nav-container">
            <a href="../index.php" class="logo">HelpDesk</a>
            <div class="nav-links">
                <a href="../index.php">Dashboard</a>
                <a href="../create/createChamado.php">Novo Chamado</a>
                <a href="../create/createCliente.php">Novo Cliente</a>
                <a href="../create/createFuncionario.php">Novo Funcionário</a>
                <a href="../create/createCargo.php">Novo Cargo</a>
                <a href="selectCargo.php">Cargos</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h1>Lista de Cargos</h1>
            <p>Gerencie todos os cargos cadastrados no sistema</p>
        </div>

        <div id="lista-cargo">Carregando...</div>
        <div id='edit-form'></div>
    
        <template id="edit-template">
            <div class="card">
                <h2>Editar Cargo</h2>
                <form action="">
                    <div class="form-group">
                        <label for="cargo">Nome do Cargo</label>
                        <input type="text" id='cargo' name="nome" placeholder="Digite o nome do cargo" class="form-group input">
                    </div>
                    <div id="feedback-geral" role="status"></div>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('edit-form').innerHTML = ''" style="margin-left: 1rem;">Cancelar</button>
                </form>
            </div>
        </template>

        <template id="cargo-template">
            <div class="cargo-card">
                <h3><strong>Código:</strong> <span class="cargo-codigo"></span></h3>
                <p><strong>Nome:</strong> <span class="cargo-nome"></span></p>
                <div class="actions">
                    <button class="btn btn-primary btn-editar" style="flex: 1;">Editar</button>
                    <button class="btn btn-danger btn-excluir" style="flex: 1;">Excluir</button>
                </div>
            </div>
        </template>

        <script type="module" src="mCargo.js"></script>
    </div>
</body>
</html>