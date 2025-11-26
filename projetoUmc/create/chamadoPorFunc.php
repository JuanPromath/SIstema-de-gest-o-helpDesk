<?php
    session_start();
    include_once '../conexao.php';
    header('Content-Type: application/json');

    $result = selectInnerWhere(['chamado', 'cliente'],['chamado.codigo','bo',"status","cliente.nome"], 'chamado.Id_funcionario = "' . $_SESSION['funcionarioID'] . '"');

    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);

    echo json_encode($result);

?>