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

    function update($table, $alteracoes, $condition){//alteracoes = [nome => "nome-Novo"]

        global $conn;

        $query = "UPDATE $table SET ";
        $index = 0;

        foreach($alteracoes as $key => $value){

            $value = "'" . $value . "'";

            if($index == sizeof($alteracoes) - 1){
                $query .= $key . ' = ' . $value;
            }else{
                $query .= $key . ' = ' . $value . ', ';
            }

            $index++;
        }

        $query .= ' WHERE ' . $condition;

        $result = mysqli_query($conn, $query);
        return $result;

    }

    function select($table,$campos=["*"]){

        GLOBAL $conn;
        $select = "SELECT ";

        for($i = 0; $i < sizeof($campos);$i++){
            
            if($i == sizeof($campos) - 1){
                $select .= $campos[$i] . " FROM " . $table;
            }else{
                $select .= $campos[$i] . ", ";
            }

        }
        if($table == 'funcionario'){
            $select .= ' INNER JOIN cargo on cargo.codigo = funcionario.id_cargo;';
        }

        $result = mysqli_query($conn, $select);
        return $result;
    }

    function selectWhere($table,$campos=["*"],$condition){

        GLOBAL $conn;
        $select = "SELECT ";

        for($i = 0; $i < sizeof($campos);$i++){
            
            if($i == sizeof($campos) - 1){
                $select .= $campos[$i] . " FROM " . $table;
            }else{
                $select .= $campos[$i] . ", ";
            }

        }
        $select .= " WHERE $condition";

        $result = mysqli_query($conn, $select);
        return $result;
    }


    //DELETE FROM nome_da_tabela WHERE condição; 
    function delete($table, $condition){

        GLOBAL $conn;

        $query = "DELETE FROM " . $table . " WHERE $condition";
        $result = mysqli_query($conn, $query);
        return $result;

    }

    function selectInner($table,$campos=["*"], $teste=false){
        
        GLOBAL $conn;
        $select = "SELECT ";

        for($i = 0; $i < sizeof($campos);$i++){
            
            if($i == sizeof($campos) - 1){
                $select .= $campos[$i] . " FROM " . $table[0];
            }else{
                $select .= $campos[$i] . ", ";
            }

        }
        
        for($i = 0; $i < sizeof($table) - 1; $i++){
            $next = $i + 1;
            $select .= ' INNER JOIN '. $table[$next] . ' on '. $table[$next] . '.codigo = ' . $table[$i].'.id_'.$table[$next];
        }
        //echo $select;
        if($teste){
            $result = mysqli_query($conn, $select);
        }else{
            $result = mysqli_query($conn, $select);
        }
        return $result;

    }

    function insert($campos, $valores, $table){

        GLOBAL $conn;

        $insert = "INSERT INTO " . $table . " (";
        for($i = 0; $i < sizeof($campos);$i++){
            
            if($i == sizeof($campos) - 1){
                $insert .= $campos[$i] . ") VALUES (";
            }else{
                $insert .= $campos[$i] . ", ";
            }

        }
        //echo $insert;
        $index = 0;
        foreach($valores as $campo => $valor){

            echo $campo;
            echo $index;

            if($campo == 'cargo' || $campo == 'funcionario'){
                $valor = $valor;
            }else{
                $valor = "'" . $valor . "'";
            }
            
            if($index == sizeof($valores) - 1){
                $insert .= $valor . ");";
            }else{
                $insert .= $valor . ', ';
            }

            $index++;
        }

        echo $insert;
        $resposta = mysqli_query($conn,$insert);
    }

?>