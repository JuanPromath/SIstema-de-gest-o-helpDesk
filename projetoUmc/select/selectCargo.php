<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Cargo</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>

    <ul>
        <?php

            include '../conexao.php';

            $result = select("cargo");

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
                print_r("sem cargos");//tem que virar excessão
            }


        ?>
    <ul>

    <div id="lista-cargo">Carregando...</div><!-- onde vai ter os cards-->
    <div id="edit-form">teste</div><!-- onde vai ter o formulário de edição-->
    
    <template id="edit-template"><!--template do form de edição-->
        <form action="">

        <input type="text" id='cargo' name="nome" placeholder="cargo">
        
        <div id="feedback-geral" role="status"></div>
        <button type="submit">Editar</button>
    
        </form>


    </template>

    <template id="cargo-template"><!--template do card de cada registro de cargo-->
        <div class="cargo-card">
            <h3 class="cargo-codigo"></h3>
            <p class="cargo-nome"></p>
            <div class="actions">
                <button class="btn-editar">Editar</button>
                <button class="btn-excluir">Excluir</button>
            </div>
        </div>
    </template>

    <script src="main.js"></script>
</body>
</html>