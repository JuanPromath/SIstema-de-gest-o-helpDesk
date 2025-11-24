<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    

    <div id='chamados-fechados'></div>
    <div id='chamados-abertos'></div>

    <template id= 'chamado-template'>

        <div id='chamado-card'>
            <h3 class = "chamado-bo"></h3>
                <p>status: <span class = "chamado-status"></span></p>
                <p>nome cliente: <span class = "chamado-nome-cliente"></span></p>
                <div class="actions">
                    <button class="btn-editar">Editar</button>
                    <button class="btn-excluir">Excluir</button>
                </div>

            </div>

        </div>
    </template>

</body>
</html>