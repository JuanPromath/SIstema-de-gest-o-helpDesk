<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Chamados - HelpDesk+</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <style>
        #lista-chamado {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .chamado-card {
            background: var(--background-light);
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 8px var(--shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .chamado-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 16px var(--shadow-hover);
        }
        
        .chamado-card h3 {
            color: var(--accent);
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }
        
        .chamado-card p {
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .chamado-card p strong {
            color: var(--text-primary);
        }
        
        .chamado-card .actions {
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
                <a href="selectChamado.php">Chamados</a>
                <a href="selectCliente.php">Clientes</a>
                <a href="selectFuncionario.php">Funcionários</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h1>Lista de Chamados</h1>
            <p>Gerencie todos os chamados do sistema</p>
        </div>

        <div id="lista-chamado"></div>
        <div id='edit-form'></div>

        <template id='template-chamado'>
            <div class='chamado-card'>
                <h3 class="chamado-bo"></h3>
                <p><strong>Código:</strong> <span class="chamado-codigo"></span></p>
                <p><strong>Status:</strong> <span class="chamado-status"></span></p>
                <p><strong>Idcliente: </strong> <span class="chamado-IdCliente"></span> <span class="chamado-Idfuncionario"></span> <span class='chamado-cargoID'></span> <span class='chamado-IdConta'></span></p>
                <p><strong>Cliente:</strong> <span class="chamado-nome-cliente"></span>(CPF: <span class="chamado-cpf-cliente"></span>)</p>
                <p><strong>Funcionário:</strong> <span class="chamado-nome-funcionario"></span></span>(CPF: <span class="chamado-cpf-funcionario"></span>)</p>
                <p><strong>Atendente:</strong> <span class="chamado-nome-atendente"></span></span>(CPF: <span class="chamado-cpf-atendente"></span>)</p>
                <p><strong>Cargo:</strong> <span class="chamado-cargo"></span></p>
                <div class="actions">
                    <button class="btn btn-primary btn-editar" style="flex: 1;">Editar</button>
                    <button class="btn btn-danger btn-excluir" style="flex: 1;">Excluir</button>
                </div>
            </div>
        </template>

        <template id='edit-template'>
            <div class="card">
                <h2>Editar Chamado</h2>
                <form action="">
                    <div class="form-group">
                        <label for="bo">BO</label>
                        <input type="text" id='bo' class="form-group input">
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <input type="text" id='status' class="form-group input">
                    </div>
                    <div class="form-group">
                        <label for="id-cliente">ID Cliente</label>
                        <input type="text" id='id-cliente' class="form-group input">
                    </div>
                    <div class="form-group">
                        <label for="Id_funcionario">ID Funcionário</label>
                        <input type="text" id='Id_funcionario' class="form-group input">
                    </div>
                    <div class="form-group">
                        <label for="Id_conta">ID Conta</label>
                        <input type="text" id='Id_conta' class="form-group input">
                    </div>
                    <div class="form-group">
                        <label for="id_cargo">ID Cargo</label>
                        <input type="text" id='id_cargo' class="form-group input">
                    </div>
                    <div id="feedback-geral" role="status"></div>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('edit-form').innerHTML = ''" style="margin-left: 1rem;">Cancelar</button>
                </form>
            </div>
        </template>

        <script type="module" src="mChamado.js"></script>
    </div>
</body>
</html>