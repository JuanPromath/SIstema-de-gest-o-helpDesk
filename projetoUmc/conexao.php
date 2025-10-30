<?php

    $servername = "localhost";
    $database = "projetoHelpDesk";
    $username = "root";
    $password = "";

    $conn = mysqli_connect($servername, $username, $password, $database);

    if(!$conn){
        die("vc é burro: " . mysqli_connect_error());
    }

    function validaCampo($campo){

        if(!isset($_POST[$campo]) || empty($_POST[$campo])){
            return false;
        }

        return true;

    };

    function select($table, $campos=["*"]){

        GLOBAL $conn;
        $select = "SELECT ";

        for($i = 0; $i < sizeof($campos);$i++){
            
            if($i == sizeof($campos) - 1){
                $select .= $campos[$i] . " FROM " . $table . ";";
            }else{
                $select .= $campos[$i] . ", ";
            }

        }

        $result = mysqli_query($conn, $select);
        return $result;
    }

    function insert($campos, $valores, $table){

        GLOBAL $conn;

        $insert = "INSERT INTO " . $table . " (";
        for($i = 0; $i < sizeof($campos);$i++){
            
            if($i == sizeof($campos) - 1){
                $insert .= $campos[$i] . ") VALUES ('";
            }else{
                $insert .= $campos[$i] . ", ";
            }

        }
        //echo $insert;
        $index = 0;
        foreach($valores as $campo => $valor){
            
            if($index == sizeof($valores) - 1){
                $insert .= $valor . "');";
            }else{
                $insert .= $valor . "', '";
            }

            $index++;
        }

        echo $insert;
        $resposta = mysqli_query($conn,$insert);
    }

?>