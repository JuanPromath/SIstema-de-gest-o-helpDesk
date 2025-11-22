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

            $result = select("cliente");

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
    </ul>

    <div id="lista-cliente">

            teste

    </div>
    
    <template id="template-cliente">
        <div class="cliente-card">
            <h3 class = "cliente-nome"></h3>
            <p class = "cliente-codigo"></p>
            <p class = "cliente-cpf"></p>
            <p class = "cliente-email"></p>
            <div class="actions">
                <button class="btn-editar">Editar</button>
                <button class="btn-excluir">Excluir</button>
            </div>
        </div>

    </template>

    <script type="module" src="mCliente.js"></script>

</body>
</html>