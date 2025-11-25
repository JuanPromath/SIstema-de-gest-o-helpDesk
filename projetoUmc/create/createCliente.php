<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Criar Cliente - HelpDesk+</title>
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
      <h1>Criar Novo Cliente</h1>
      <p>Preencha os dados do cliente abaixo</p>
    </div>

    <div class="form-container">
      <form action="createCliente.php" method="post">
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
          <input type="email" id="email" name="email" placeholder="cliente@email.com" required>
        </div>

        <button type="submit" class="btn btn-primary">Cadastrar Cliente</button>
        <a href="../index.php" class="btn btn-secondary" style="margin-left: 1rem;">Cancelar</a>
      </form>
    </div>
  </div>
</body>
</html>


<?php

    include '../conexao.php';

    if(!validaCampo('nome') && !validaCampo('email') && !validaCampo('cpf')){
        die('campos inválidos');
    }

    insert(['nome', 'cpf', 'email'], $_POST, "Cliente");

?>