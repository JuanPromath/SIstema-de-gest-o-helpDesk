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

    <h1>Cadastro conta</h1>

    <form action="createConta.php" method="post">

        <input type="text"  id='senha' name="senha" placeholder="senha">
        <select name="funcionario" id="funcionario">

        <?php

            include '../conexao.php';

            $result = select("funcionario", ['funcionario.codigo', 'funcionario.nome']);

            if (mysqli_num_rows($result) > 0) {
                        
                while ($row = mysqli_fetch_assoc($result)) {
                        print_r("<option value='" . $row['codigo']."'>" . $row['nome']);
                }

                }else {
                    print_r("sem funcionarios");//tem que virar excessão
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

    insert(['senha', 'Id_funcionario'], $_POST, "Conta_Sistema");

?>