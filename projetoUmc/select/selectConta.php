<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Cargo</title>
</head>
<body>

    <ul>
        <?php

            include '../conexao.php';
            //selectInner(["Conta_Sistema", 'funcionario']);
            $result = selectInner(["Conta_Sistema", 'funcionario', 'cargo'], ['Conta_Sistema.codigo', 'funcionario.nome', 'funcionario.cpf', 'cargo.nome as cargo']);

            if (mysqli_num_rows($result) > 0) {
                        
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<li>';
                    $msg = '';
                    foreach($row as $key => $value){
                        $msg .= $key . ": " . $value;
                    }
                    var_dump($msg);
                    
                }

            }else {
                print_r("sem clientes");//tem que virar excessão
            }


        ?>
    <ul>
    
</body>
</html>
