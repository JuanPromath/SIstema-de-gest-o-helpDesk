
$protect = true;
if ($protect) require_once '../require_login.php';
<?php
$feedback = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../conexao.php';
    if (!validaCampo('senha') || !validaCampo('funcionario')) {
        $feedback = '<div class="alert alert-danger mt-3"><i class="bi bi-exclamation-triangle"></i> Preencha todos os campos obrigatórios.</div>';
    } else {
        $senhaHash = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        $dados = [
            'senha' => $senhaHash,
            'Id_funcionario' => $_POST['funcionario']
        ];
        insert(['senha', 'Id_funcionario'], $dados, "Conta_Sistema");
        $feedback = '<div class="alert alert-success mt-3"><i class="bi bi-check-circle"></i> Conta cadastrada com sucesso!</div>';
    }
}

ob_start();
include '../conexao.php';
$funcionarioOptions = '';
$result = select("funcionario", ['funcionario.codigo', 'funcionario.nome']);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $funcionarioOptions .= "<option value='" . $row['codigo'] . "'>" . htmlspecialchars($row['nome']) . "</option>";
    }
} else {
    $funcionarioOptions = '<option value="">Nenhum funcionário cadastrado</option>';
}
ob_end_clean();

$content = '<div class="row justify-content-center">'
    .'<div class="col-12 col-md-8 col-lg-6">'
    .'<div class="card shadow-sm border-0">'
    .'<div class="card-body p-4">'
    .'<h2 class="mb-4 fw-bold text-center"><i class="bi bi-person-circle text-primary"></i> Cadastro de Conta</h2>'
    .'<form action="createConta.php" method="post">'
        .'<div class="mb-3">'
            .'<label for="senha" class="form-label fw-semibold">Senha</label>'
            .'<div class="input-group">'
                .'<span class="input-group-text"><i class="bi bi-key"></i></span>'
                .'<input type="password" class="form-control" id="senha" name="senha" placeholder="Digite a senha" required>'
            .'</div>'
        .'</div>'
        .'<div class="mb-3">'
            .'<label for="funcionario" class="form-label fw-semibold">Funcionário</label>'
            .'<div class="input-group">'
                .'<span class="input-group-text"><i class="bi bi-person"></i></span>'
                .'<select class="form-select" name="funcionario" id="funcionario" required>'
                    .'<option value="">Selecione o funcionário</option>'
                    .$funcionarioOptions
                .'</select>'
            .'</div>'
        .'</div>'
        .'<button type="submit" class="btn btn-primary w-100"><i class="bi bi-plus-circle"></i> Cadastrar Conta</button>'
    .'</form>'
    .$feedback
    .'<a href="../index.php" class="btn btn-link mt-3 w-100"><i class="bi bi-arrow-left"></i> Voltar para o início</a>'
    .'</div>'
    .'</div>'
    .'</div>'
    .'</div>';
include '../template.php';
?>

<?php

    if(!validaCampo('senha') && !validaCampo('funcionario')){
        die('campos inválidos');
    }

    insert(['senha', 'Id_funcionario'], $_POST, "Conta_Sistema");

?>