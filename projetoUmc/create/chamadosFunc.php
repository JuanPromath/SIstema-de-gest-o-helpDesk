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

    <template id='chamado-template'>

        <div id='chamado-card'>
            <h3 class = "chamado-bo"></h3>
                <p>status: <span class = "chamado-status"></span></p>
                <p>nome cliente: <span class = "chamado-nome-cliente"></span></p>
                <select name="status" id="status">

                    <option value="aberto">aberto</option>
                    <option value="fechado">fechado</option>

                </select>
                <button id='atualizar'>atualizar status</button>

            </div>

        </div>
    </template>

    <script type="module" src="chamadoFunc.js"></script>

</body>
</html>