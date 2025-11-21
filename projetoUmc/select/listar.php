<?php
    require_once '../conexao.php';
    header('Content-Type: application/json');
    $result = select('cargo', ["*"]);
    $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $row = json_encode($row);
    echo $row;
?>