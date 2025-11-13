<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Criação de Chamado</title>
  <link rel="stylesheet" href="styleChamado.css">
</head>
<body>

  <nav>s
    <a href="../index.php">Home</a>
  </nav>

  <main class="container">
    <h1>Formulário de Criação de Chamado</h1>

    <section class="form-container">
      <!-- Lado esquerdo (visualização) -->
      <div class="display-box">
        <h2>Visualização</h2>
        <div class="preview">
          <p><strong>Número do BO:</strong> <span id="boDisplay">—</span></p>
          <p><strong>CPF do Cliente:</strong> <span id="cpfDisplay">—</span></p>
        </div>
      </div>

      <!-- Lado direito (formulário) -->
      <form action="createChamado.php" method="post" class="form-inputs">
        <label for="bo">Número de BO:</label>
        <input type="text" id="bo" name="bo" placeholder="Digite o número do BO" required>

        <label for="cliente">CPF do Cliente:</label>
        <input type="text" id="cliente" name="cliente" placeholder="Digite o CPF do cliente" required>

        <label for="cargo">Cargo:</label>
        <select name="cargo" id="cargo">
          <option value="">Selecione o cargo</option>
          <option value="atendente">Atendente</option>
          <option value="suporte">Suporte</option>
          <option value="gerente">Gerente</option>
        </select>

        <button type="submit">Enviar Chamado</button>
      </form>
    </section>
  </main>

  <script src="script.js"></script>
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
        <select name="funcionario" id="funcionario">
            <?php

                $result = select("funcionario",['funcionario.codigo', 'funcionario.nome', 'cargo.nome as cargo']);

                if (mysqli_num_rows($result) > 0) {
                    var_dump($result);
                    while ($row = mysqli_fetch_assoc($result)) {
                        print_r("<option class='bucefalos' value='" . $row['codigo']."'>" . $row['nome'] . ' - ' . $row['cargo']);
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

    if(!validaCampo('senha') && !validaCampo('funcionario')){
        die('campos inválidos');
    }
    $chamado = [];

    $chamado.

    insert(['bo', 'status', 'Id_funcionario'], $_POST, "Chamado");

?>