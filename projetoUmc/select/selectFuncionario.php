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

            $result = selectInner(['funcionario','cargo'], ['funcionario.nome','funcionario.codigo','cargo.nome as cargo', 'id_cargo as cargoID','cpf', 'email']);

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

    </div>

    <div id='edit-form'></div>

    <div id='debug'>teste</div>

    <template id="template-funcionario">
        <div class="funcionario-card">
            <h3 class = "funcionario-nome"></h3>
            <p class = "funcionario-codigo"></p>
            <p class = "funcionario-cpf"></p>
            <p class = "funcionario-email"></p>
            <p class = "funcionario-cargo"></p>
            <p class = "funcionario-cargoID"></p>
            <div class="actions">
                <button class="btn-editar">Editar</button>
                <button class="btn-excluir">Excluir</button>
            </div>
        </div>
    </template>
    
    <template id='edit-template'>

        <label for="">nome: </label>
        <input type="text" id='func-nome'>
        <label for="">email: </label>
        <input type="email" id='func-email'>
        <label for="">cpf: </label>
        <input type="text" id='func-cpf'>
        <label for="">id_cargo: </label>
        <input type="text" id='func-cargoID'>

    </template>
    
    <script type="module" src="mFuncionario.js"></script>

</body>
</html>