<?php
    // Verificar se o usuário está logado
    session_start();
    function irparalogin($login) {

        if(!isset($_SESSION['codigo']) || !isset($_SESSION['email'])) {
            header('location: ' . $login);
            exit;
        }    

    };

    function verificaPermissao($proibidoPara, $forbidden){
        for($i=0 ; $i < sizeof($proibidoPara);$i++){
            if($_SESSION['nivel_acesso'] == $proibidoPara[$i]){
                header('location: ' . $forbidden);
                exit;
            }
        }
    }

?>