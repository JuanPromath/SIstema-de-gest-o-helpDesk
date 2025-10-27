<?php

    $servername = "localhost";
    $database = "projetoHelpDesk";
    $username = "root";
    $password = "";

    $conn = mysqli_connect($servername, $username, $password, $database);

    if(!$conn){
        die("vc é burro: " . mysqli_connect_error());
    }

?>