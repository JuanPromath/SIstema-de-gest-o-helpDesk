<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Criar Funcionário - HelpDesk+</title>
  <link rel="stylesheet" href="../assets/css/global.css">
</head>
<body>
  <?php session_start(); ?>
  <nav>
    <div class="nav-container">
      <a href="../index.php" class="logo">HelpDesk</a>
      <div class="nav-links">
        <a href="../index.php">Dashboard</a>
        <a href="createChamado.php">Novo Chamado</a>
        <a href="createCliente.php">Novo Cliente</a>
        <a href="createFuncionario.php">Novo Funcionário</a>
      </div>
    </div>
  </nav>

  <div class="container">
    <div class="page-header">
      <h1>Criar Novo Funcionário</h1>
      <p>Preencha os dados do funcionário abaixo</p>
    </div>

    <div class="form-container">
      <form action="createFuncionario.php" method="post">
        <div class="form-group">
          <label for="nome">Nome Completo</label>
          <input type="text" id="nome" name="nome" placeholder="Digite o nome completo" required>
        </div>

        <div class="form-group">
          <label for="cpf">CPF</label>
          <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" maxlength="14" required>
        </div>

        <div class="form-group">
          <label for="email">E-mail</label>
          <input type="email" id="email" name="email" placeholder="funcionario@email.com" required>
        </div>

        <div class="form-group">
          <label for="cargo">Cargo</label>
          <select id="cargo" name="cargo" required>
            <option value="">Selecione o cargo</option>
            <?php
              include '../conexao.php';
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

        <button type="submit" class="btn btn-primary">Cadastrar Funcionário</button>
        <a href="../index.php" class="btn btn-secondary" style="margin-left: 1rem;">Cancelar</a>
      </form>
    </div>
  </div>
</body>
</html>

<?php

    if(!validaCampo('nome') && !validaCampo('email') && !validaCampo('cpf')){
        die('campos inválidos');
    }

    insert(['nome','cpf','email','id_cargo'], $_POST, 'funcionario');



?>
