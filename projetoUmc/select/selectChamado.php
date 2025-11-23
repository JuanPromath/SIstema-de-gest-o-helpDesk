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

            $result = select("chamado");

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
                print_r("sem chamado");//tem que virar excessão
            }


        ?>
    </ul>

    <div id="lista-chamado"></div>
    <div id='edit-form'></div>

    <template id='template-chamado'>
        <div class='chamado-card'>

        <h3 class = "chamado-bo"></h3>
            <p>codigo: <span class = "chamado-codigo"></span></p>
            <p>status: <span class = "chamado-status"></span></p>
            <p>idCliente: <span class = "chamado-IdCliente"></span></p>
            <p>nome cliente: <span class = "chamado-nome-cliente"></span></p>
            <p>cpf cliente: <span class = "chamado-cpf-cliente"></span></p>
            <p>id funcionario <span class = "chamado-Idfuncionario"></span></p>
            <p>nome Funcionario <span class = "chamado-nome-funcionario"></span></p>
            <p>cpf Funcionario <span class = "chamado-cpf-funcionario"></span></p>
            <p>Id conta <span class = "chamado-IdConta"></span></p>
            <p>nome atendente <span class = "chamado-nome-atendente"></span></p>
            <p>cpf atendente <span class = "chamado-cpf-atendente"></span></p>
            <p>id cargo<span class = "chamado-cargoID"></span></p>
            <p>cargo <span class = "chamado-cargo"></span></p>
            <div class="actions">
                <button class="btn-editar">Editar</button>
                <button class="btn-excluir">Excluir</button>
            </div>

        </div>

    </template>

    <template id='edit-template'>
        <form action="">
            <label for="">bo: </label>
            <input type="text" id='bo'>
            <label for="">status: </label>
            <input type="text" id='status'>
            <label for="">id cliente: </label>
            <input type="text" id='id-cliente'>
            <label for="">id funcionario: </label>
            <input type="text" id='Id_funcionario'>
            <label for="">id conta: </label>
            <input type="text" id='Id_conta'>
            <label for="">id cargo: </label>
            <input type="text" id='id_cargo'>
            
            <div id="feedback-geral" role="status"></div>
            <button type="submit">Editar</button>
    
        </form>

    </template>

    <script type="module" src="mChamado.js"></script>
    
</body>
</html>