<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Contas - HelpDesk+</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <style>
        #lista-conta {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .card-conta {
            background: var(--background-light);
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 8px var(--shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card-conta:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 16px var(--shadow-hover);
        }
        
        .card-conta h3 {
            color: var(--accent);
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }
        
        .card-conta p {
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .card-conta p strong {
            color: var(--text-primary);
        }
        
        .card-conta .actions {
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
                <a href="../create/createConta.php">Nova Conta</a>
                <a href="selectConta.php">Contas</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h1>Lista de Contas</h1>
            <p>Gerencie todas as contas de acesso ao sistema</p>
        </div>

        <div id="lista-conta"></div>
        <div id='edit-form'></div>

        <template id="template-conta">
            <div class="card-conta">
                <h3><strong>Código:</strong> <span class='conta-codigo'></span></h3>
                <p><strong>E-mail:</strong> <span class='conta-email'></span></p>
                <p><strong>Senha:</strong> <span class='conta-senha'></span></p>
                <p><strong>Funcionário:</strong> <span class='conta-nome'></span></p>
                <p><strong>CPF:</strong> <span class='conta-cpf'></span></p>
                <p><strong>Cargo:</strong> <span class='conta-cargo'></span></p>
                <div class="actions">
                    <button class="btn btn-primary btn-editar" style="flex: 1;">Editar</button>
                    <button class="btn btn-danger btn-excluir" style="flex: 1;">Excluir</button>
                </div>
            </div>
        </template>

        <template id='edit-template'>
            <div class="card">
                <h2>Editar Conta</h2>
                <form action="">
                    <div class="form-group">
                        <label for="conta-senha">Senha</label>
                        <input type="password" id='conta-senha' class="form-group input">
                    </div>
                    <div class="form-group">
                        <label for="conta-funcId">ID do Funcionário</label>
                        <input type="text" id='conta-funcId' class="form-group input">
                    </div>
                    <div id="feedback-geral" role="status"></div>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('edit-form').innerHTML = ''" style="margin-left: 1rem;">Cancelar</button>
                </form>
            </div>
        </template>
    
        <script type="module" src="mConta.js"></script>
    </div>
</body>
</html>
