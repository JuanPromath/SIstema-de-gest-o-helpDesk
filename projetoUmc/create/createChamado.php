<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav>

    <a href="../index.php">Home</a>

    </nav>
    <h1>form criação chamado</h1>

    <form action="createChamado.php" method="post">

        <input type="text" id='bo' name="bo" placeholder="bo">
        <input type="text" id="cliente" name="cliente" placeholder="cpf cliente">
        <select name="cargo" id="cargo">
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