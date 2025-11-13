<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Criação de Funcionário</title>
  <link rel="stylesheet" href="styleFuncionario.css">
</head>
<body>

  <nav>
    <a href="../index.php">Home</a>
  </nav>

  <main class="container">
    <h1>Formulário de Criação de Funcionário</h1>

    <form action="createFuncionario.php" method="post" class="form-box">

      <label for="nome">Nome:</label>
      <input type="text" id="nome" name="nome" placeholder="Digite o nome" required>

      <label for="cpf">CPF:</label>
      <input type="text" id="cpf" name="cpf" placeholder="Digite o CPF" maxlength="11" required>

      <label for="email">Email:</label>
      <input type="email" id="email" name="email" placeholder="Digite o email" required>

      <label for="cargo">Cargo:</label>
      <select id="cargo" name="cargo" required>
        <option value="">Selecione o cargo</option>
        <option value="atendente">Atendente</option>
        <option value="gerente">tecnico</option>
        <option value="outro">Outro</option>
      </select>

      <button type="submit">Cadastrar Funcionário</button>
    </form>
  </main>

</body>
</html>

            <?php

                include '../conexao.php';

                $result = select("Cargo");

                if (mysqli_num_rows($result) > 0) {
                            
                    while ($row = mysqli_fetch_assoc($result)) {
                        print_r("<option class='bucefalos' value='" . $row['codigo']."'>" . $row['nome']);
                    }

                }else {
                    print_r("sem cargos");//tem que virar excessão
                }

            ?>

        </select>

        <button type="submit">enviar</button>

    </form>

</body>
</html>

<?php

    if(!validaCampo('nome') && !validaCampo('email') && !validaCampo('cpf')){
        die('campos inválidos');
    }

    insert(['nome','cpf','email','id_cargo'], $_POST, 'funcionario');



?>
