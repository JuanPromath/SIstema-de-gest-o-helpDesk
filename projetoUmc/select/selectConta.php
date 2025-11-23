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

    <div id="lista-conta"></div>
    <div id='edit-form'></div>

    <template id="template-conta">
        <div class="card-conta">

            <h3 class='conta-codigo'></h3>
            <p class='conta-email'></p>
            <p class='conta-senha'></p>
            <p class='conta-nome'></p>
            <p class='conta-cpf'></p>
            <p class='conta-idF'></p>
            <div class="actions">
                <button class="btn-editar">Editar</button>
                <button class="btn-excluir">Excluir</button>
            </div>
        </div>
    </template>

        
    <template id='edit-template'>

        <label for="">senha: </label>
        <input type="text" id='conta-senha'>
        <label for="">funcionario id: </label>
        <input type="text" id='conta-funcId'>

    </template>
    
    <script type="module" src="mConta.js"></script>

</body>
</html>
