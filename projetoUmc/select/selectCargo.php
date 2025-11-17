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

            teste('cargo');

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

    <div id="lista-chamados">Carregando...</div>

    <template id="cargo-template">
        <div class="cargo-card">
            <h3 class="cargo-codigo"></h3>
            <p class="cargo-nome"></p>
            <div class="actions">
                <button class="btn-editar">Editar</button>
                <button class="btn-excluir">Excluir</button>
            </div>
        </div>
    </template>

    <button onclick="teste()">teste</button>

    <script src="main.js"></script>
</body>
</html>