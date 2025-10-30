<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <nav>

    <a href="../index.php">Home</a>

    </nav>

    <h1>form criação funcionario</h1>

    <form action="createFuncionario.php" method="post">

        <input type="text" id='nome' name="nome" placeholder="nome">
        <input type="text" sizeof='11' id='cpf' name="cpf" placeholder="cpf">
        <input type="email" placeholder="email" id="email" name="email">
        <input type="text" placeholder="cargo" id="cargo" name="cargo">

        <select name="cargo" id="cargo">
            <?php

                include '../conexao.php';

                $result = select("Cargo");

                if (mysqli_num_rows($result) > 0) {
                            
                    while ($row = mysqli_fetch_assoc($result)) {
                        print_r("<option value='" . $row['codigo']."'>" . $row['nome']);
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