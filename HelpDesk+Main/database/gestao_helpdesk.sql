    USE HelpDeskMais;

    SELECT * FROM cliente;
    SELECT * FROM funcionario;
    SELECT * FROM chamado;
    SELECT * FROM cargo;

    SELECT cliente.nome as nome_cliente, cliente.cpf as cliente_cpf, fm.nome as nome_funcionario, fm.cpf as cpf_funcionario, cargo.nome as cargo, ad.nome as atendente_nome, ad.cpf as atendente_cpf FROM chamado 
    INNER JOIN Cliente ON chamado.Id_cliente = cliente.codigo
    INNER JOIN funcionario as fm on chamado.Id_funcionario = fm.codigo
    INNER JOIN conta_sistema on Id_conta = conta_sistema.codigo
    INNER JOIN cargo on chamado.id_cargo = cargo.codigo
    INNER JOIN funcionario as ad on conta_sistema.Id_funcionario = ad.codigo