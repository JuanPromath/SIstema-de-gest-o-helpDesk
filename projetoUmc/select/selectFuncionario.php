<?php
    require '../verificaLogado.php';
    irparalogin('../login.php');
    verificaPermissao(['2','3'], '../forbbiden.php');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Funcionários - HelpDesk+</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <style>
        #lista-funcionario {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .funcionario-card {
            background: var(--background-light);
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 8px var(--shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .funcionario-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 16px var(--shadow-hover);
        }
        
        .funcionario-card h3 {
            color: var(--accent);
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }
        
        .funcionario-card p {
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .funcionario-card p strong {
            color: var(--text-primary);
        }
        
        .funcionario-card .actions {
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
    <nav>
        <div class="nav-container">
            <a href="../index.php" class="logo">HelpDesk</a>
            <div class="nav-links">
                <a href="../index.php">Dashboard</a>
                <a href="../create/createChamado.php">Novo Chamado</a>
                <a href="../create/createCliente.php">Novo Cliente</a>
                <a href="../create/createFuncionario.php">Novo Funcionário</a>
                <a href="selectChamado.php">Chamados</a>
                <a href="selectCliente.php">Clientes</a>
                <a href="selectFuncionario.php">Funcionários</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h1>Lista de Funcionários</h1>
            <p>Gerencie todos os funcionários cadastrados no sistema</p>
        </div>

        <div id="lista-funcionario"></div>
        <div id='edit-form'></div>

        <template id="template-funcionario">
            <div class="funcionario-card">
                <h3 class="funcionario-nome"></h3>
                <p><strong>Código:</strong> <span class="funcionario-codigo"></span></p>
                <p><strong>CPF:</strong> <span class="funcionario-cpf"></span></p>
                <p><strong>E-mail:</strong> <span class="funcionario-email"></span></p>
                <p><strong>Cargo:</strong> <span class="funcionario-cargo"></span> (Id: <span class='funcionario-cargoID'></span>)</p>
                <div class="actions">
                    <button class="btn btn-primary btn-editar" style="flex: 1;">Editar</button>
                    <button class="btn btn-danger btn-excluir" style="flex: 1;">Excluir</button>
                </div>
            </div>
        </template>
        
        <template id='edit-template'>
            <div class="card">
                <h2>Editar Funcionário</h2>
                <form action="">
                    <div class="form-group">
                        <label for="func-nome">Nome</label>
                        <input type="text" id='func-nome' class="form-group input">
                    </div>
                    <div class="form-group">
                        <label for="func-email">E-mail</label>
                        <input type="email" id='func-email' class="form-group input">
                    </div>
                    <div class="form-group">
                        <label for="func-cpf">CPF</label>
                        <input type="text" id='func-cpf' class="form-group input">
                    </div>
                    <div class="form-group">
                        <label for="func-cargoID">ID do Cargo</label>
                        <input type="text" id='func-cargoID' class="form-group input">
                    </div>
                    <div id="feedback-geral" role="status"></div>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('edit-form').innerHTML = ''" style="margin-left: 1rem;">Cancelar</button>
                </form>
            </div>
        </template>
        
        <script type="module" src="mFuncionario.js"></script>
    </div>
</body>
</html>