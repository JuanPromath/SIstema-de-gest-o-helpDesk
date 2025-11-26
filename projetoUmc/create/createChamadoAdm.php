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
  <title>Criar Chamado (Admin) - HelpDesk+</title>
  <link rel="stylesheet" href="../assets/css/global.css">
  <style>
    .form-container {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 2rem;
      max-width: 1200px;
    }
    
    .preview-box {
      background: var(--background-light);
      padding: 1.5rem;
      border-radius: 10px;
      box-shadow: 0 2px 8px var(--shadow);
      height: fit-content;
    }
    
    .preview-box h2 {
      margin-bottom: 1rem;
      color: var(--text-primary);
    }
    
    .preview-item {
      margin-bottom: 1rem;
      padding: 0.75rem;
      background: var(--background);
      border-radius: 6px;
    }
    
    .preview-item strong {
      color: var(--text-secondary);
      display: block;
      margin-bottom: 0.25rem;
      font-size: 0.9rem;
    }
    
    .preview-item span {
      color: var(--text-primary);
      font-size: 1rem;
    }
    
    @media (max-width: 768px) {
      .form-container {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <nav>
    <div class="nav-container">
      <a href="../index.php" class="logo">HelpDesk</a>
      <div class="nav-links">
        <a href="../index.php">Dashboard</a>
        <a href="createChamado.php">Novo Chamado</a>
        <a href="createChamadoAdm.php">Novo Chamado (Admin)</a>
        <a href="createCliente.php">Novo Cliente</a>
        <a href="createFuncionario.php">Novo Funcionário</a>
      </div>
    </div>
  </nav>

  <div class="container">
    <div class="page-header">
      <h1>Criar Chamado (Administrador)</h1>
      <p>Formulário completo para criação de chamados com todas as opções</p>
    </div>

    <div class="form-container">
      <!-- Lado esquerdo (visualização) -->
      <div class="preview-box">
        <h2>Pré-visualização</h2>
        <div class="preview-item">
          <strong>Número do BO:</strong>
          <span id="boDisplay">—</span>
        </div>
        <div class="preview-item">
          <strong>Cliente Selecionado:</strong>
          <span id="clienteDisplay">—</span>
        </div>
      </div>

      <!-- Lado direito (formulário) -->
      <div class="card">
        <form action="createChamadoAdm.php" method="post">
          <div class="form-group">
            <label for="bo">Número do BO</label>
            <input type="text" id="bo" name="bo" placeholder="Descreva o BO" required>
          </div>

          <div class="form-group">
            <label for="cliente">Cliente</label>
            <select name="cliente" id="cliente" required>
              <option value="">Selecione o cliente</option>
              <?php
                include '../conexao.php';
                $result = select("cliente");

                if (mysqli_num_rows($result) > 0) {
                  while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['codigo']."'>" . $row['nome'] . "</option>";
                  }
                } else {
                  echo "<option value=''>Nenhum cliente disponível</option>";
                }
              ?>
            </select>
          </div>

          <div class="form-group">
            <label for="cargo">Cargo</label>
            <select name="cargo" id="cargo" required>
              <option value="">Selecione o cargo</option>
              <?php
                $result = select("Cargo");

                if (mysqli_num_rows($result) > 0) {
                  while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['codigo']."'>" . $row['nome'] . "</option>";
                  }
                } else {
                  echo "<option value=''>Nenhum cargo disponível</option>";
                }
              ?>
            </select>
          </div>

          <div class="form-group">
            <label for="conta">Conta (Atendente)</label>
            <select name="conta" id="conta" required>
              <option value="">Selecione a conta</option>
              <?php
                $result = selectInner(["Conta_Sistema", 'funcionario', 'cargo'], ['Conta_Sistema.codigo', 'funcionario.nome', 'funcionario.cpf', 'cargo.nome as cargo']);

                if (mysqli_num_rows($result) > 0) {
                  while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['codigo']."'>" . $row['nome'] . ' - ' . $row['cpf'] . "</option>";
                  }
                } else {
                  echo "<option value=''>Nenhuma conta disponível</option>";
                }
              ?>
            </select>
          </div>

          <div class="form-group">
            <label for="funcionario">Funcionário</label>
            <select name="funcionario" id="funcionario" required>
              <option value="">Selecione o funcionário</option>
            </select>
          </div>

          <div class="form-group">
            <label for="status">Status do Chamado</label>
            <select name="status" id="status" required>
              <option value="">Selecione o status</option>
              <option value="aberto">Aberto</option>
              <option value="fechado">Fechado</option>
            </select>
          </div>

          <button type="submit" class="btn btn-primary">Enviar Chamado</button>
          <a href="../index.php" class="btn btn-secondary" style="margin-left: 1rem;">Cancelar</a>
        </form>
      </div>
    </div>
  </div>
  <script type="module" src="chamadoAdm.js"></script>
</body>
</html>

<?php

    foreach($_POST as $key => $value){
      if(!validaCampo($key)){
        die('campo inválido');
      }
    }

    insert(['bo', 'Id_cliente', 'Id_cargo', 'Id_conta','Id_funcionario', 'status'], $_POST, "Chamado");

?>