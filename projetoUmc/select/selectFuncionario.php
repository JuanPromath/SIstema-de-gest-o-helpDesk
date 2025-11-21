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

            $result = select("funcionario", ['funcionario.nome', 'cargo.nome as cargo', 'cpf', 'email']);

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

    <div id="lista-funcionario">
n
    </div>

    <template id="template-funcionario">
        <div class="funcionario-card">
            <h3 class = "funcionario-nome"></h3>
            <p class = "funcionario-codigo"></p>
            <p class = "funcionario-cpf"></p>
            <p class = "funcionario-email"></p>
            <p class = "funcionario-cargo"></p>
            <p class = "funcionario-cargoID"></p>
        </div>

    </template>
    
    <script type="module" src="mFuncionario.js"></script>

</body>
</html>