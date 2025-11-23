<?php
    require_once '../conexao.php';
    header('Content-Type: application/json');
    $dados = json_decode(file_get_contents('php://input'), true);

    $result = '';

    if($dados['table'] == 'funcionario'){
        $result = selectInner(['funcionario','cargo'], ['funcionario.nome','funcionario.codigo','cargo.nome as cargo', 'id_cargo as cargoID','cpf', 'email']);
    }else if($dados['table'] == 'Conta_Sistema'){
        $result = selectInner(["Conta_Sistema", 'funcionario'], ['Conta_Sistema.codigo', 'funcionario.nome', 'funcionario.cpf', 'Id_funcionario as funcionarioID', 'funcionario.email', 'senha']);
    }else if($dados['table'] == 'chamado'){
        $query = 'SELECT chamado.codigo, bo, status, Id_cliente, cliente.nome as nome_cliente, cliente.cpf as cliente_cpf, 
            chamado.Id_funcionario, fm.nome as nome_funcionario, fm.cpf as cpf_funcionario, 
            chamado.Id_cargo, cargo.nome as cargo, 
            Id_conta, ad.nome as atendente_nome, ad.cpf as atendente_cpf FROM chamado 
            INNER JOIN Cliente ON chamado.Id_cliente = cliente.codigo
            INNER JOIN funcionario as fm on chamado.Id_funcionario = fm.codigo
            INNER JOIN conta_sistema on Id_conta = conta_sistema.codigo
            INNER JOIN cargo on chamado.id_cargo = cargo.codigo
            INNER JOIN funcionario as ad on conta_sistema.Id_funcionario = ad.codigo';
        $result = mysqli_query($conn, $query);
    }else{
        $result = select($dados['table'], ["*"]);
    }

    $result2 = select($dados['table'], ["*"]);

    $row = [mysqli_fetch_all($result, MYSQLI_ASSOC), mysqli_fetch_all($result2, MYSQLI_ASSOC)];
    $row = json_encode($row);
    echo $row;
?>