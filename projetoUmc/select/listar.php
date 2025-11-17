<?php
    require_once '../conexao.php';
    header('Content-Type: application/json');
    $all = teste('cargo');
    echo $all;
?>