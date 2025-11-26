<?php
    require '../verificaLogado.php';
    irparalogin('../login.php');
    verificaPermissao(['2'], '../forbbiden.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <style>
        .lista-chamado {
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
            <h1>Lista de Chamados atribuidos</h1>
            <p>Veja todos os chamados atribuidos a você</p>
        </div>
    
        <div class="page-header">
            <h2>lista de chamados abertos</h2>
            <div id='chamados-fechados' class='lista-chamado'></div>
            <h2>lista de chamados fechados</h2>
            <div id='chamados-abertos' class='lista-chamado'></div>
        </div>
        <template id='chamado-template'>

            <div id='chamado-card' class='chamado-card'>
                <div class='chamado-card'>
                    <h3 class="chamado-bo"></h3>
                    <p><strong>Status:</strong> <span class="chamado-status"></span></p>
                    <p><strong>Cliente:</strong> <span class="chamado-nome-cliente"></span></p>
                    <div class="actions">
                        <select name="status" id="status">

                            <option value="aberto">aberto</option>
                            <option value="fechado">fechado</option>

                        </select>
                        <button id='atualizar' class="btn btn-primary btn-atualizar" style="flex: 1;">atualizar status</button>
                    </div>
                </div>

            </div>
        </template>
    </div>

    <script type="module" src="chamadoFunc.js"></script>

</body>
</html>