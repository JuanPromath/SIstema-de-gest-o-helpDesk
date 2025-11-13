<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Criação de Cliente</title>
  <link rel="stylesheet" href="styleCliente.css">
</head>
<body>

  <nav>
    <a href="../index.php" rel="prev">Home</a>
  </nav>

  <main class="container">
    <h1>Formulário de Criação de Cliente</h1>

    <form action="createCliente.php" method="post" class="form-box">

      <label for="nome">Nome:</label>
      <input type="text" id="nome" name="nome" placeholder="Digite o nome" required>

      <label for="cpf">CPF:</label>
      <input type="text" id="cpf" name="cpf" placeholder="Digite o CPF" required>

      <label for="email">Email:</label>
      <input type="email" id="email" name="email" placeholder="Digite o email" required>

      <button type="submit">Enviar</button>

    </form>
  </main>

</body>
</html>


<?php

    include '../conexao.php';

    if(!validaCampo('nome') && !validaCampo('email') && !validaCampo('cpf')){
        die('campos inválidos');
    }

    insert(['nome', 'cpf', 'email'], $_POST, "Cliente");

?>