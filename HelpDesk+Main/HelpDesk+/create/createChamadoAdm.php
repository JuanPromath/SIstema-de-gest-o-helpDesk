
$protect = true;
if ($protect) require_once '../require_login.php';
<?php
$feedback = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  include '../conexao.php';
  $campos = ['bo', 'cliente', 'cargo', 'conta', 'funcionario', 'status'];
  foreach($campos as $key) {
    if (!validaCampo($key)) {
      $feedback = '<div class="alert alert-danger mt-3"><i class="bi bi-exclamation-triangle"></i> Preencha todos os campos obrigatórios.</div>';
      break;
    }
  }
  if (!$feedback) {
    insert(['bo', 'Id_cliente', 'Id_cargo', 'Id_conta','Id_funcionario', 'status'], $_POST, "Chamado");
    $feedback = '<div class="alert alert-success mt-3"><i class="bi bi-check-circle"></i> Chamado cadastrado com sucesso!</div>';
  }
}

ob_start();
include '../conexao.php';
$clienteOptions = '';
$result = select("cliente");
if (mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $clienteOptions .= "<option value='" . $row['codigo'] . "'>" . htmlspecialchars($row['nome']) . "</option>";
  }
} else {
  $clienteOptions = '<option value="">Nenhum cliente cadastrado</option>';
}
$cargoOptions = '';
$result = select("Cargo");
if (mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $cargoOptions .= "<option value='" . $row['codigo'] . "'>" . htmlspecialchars($row['nome']) . "</option>";
  }
} else {
  $cargoOptions = '<option value="">Nenhum cargo cadastrado</option>';
}
$contaOptions = '';
$result = selectInner(["Conta_Sistema", 'funcionario', 'cargo'], ['Conta_Sistema.codigo', 'funcionario.nome', 'funcionario.cpf', 'cargo.nome as cargo']);
if (mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $contaOptions .= "<option value='" . $row['codigo'] . "'>" . htmlspecialchars($row['nome']) . ' - ' . htmlspecialchars($row['cpf']) . "</option>";
  }
} else {
  $contaOptions = '<option value="">Nenhuma conta cadastrada</option>';
}
$funcionarioOptions = '';
$result = select("funcionario", ['funcionario.codigo','funcionario.nome', 'cargo.nome as cargo', 'cpf', 'email']);
if (mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $funcionarioOptions .= "<option value='" . $row['codigo'] . "'>" . htmlspecialchars($row['nome']) . ' - ' . htmlspecialchars($row['cargo']) . "</option>";
  }
} else {
  $funcionarioOptions = '<option value="">Nenhum funcionário cadastrado</option>';
}
ob_end_clean();

$content = '<div class="row justify-content-center">'
  .'<div class="col-12 col-lg-10">'
  .'<div class="card shadow-sm border-0">'
  .'<div class="card-body p-4">'
  .'<h2 class="mb-4 fw-bold text-center"><i class="bi bi-clipboard-plus text-primary"></i> Abrir Chamado (Adm)</h2>'
  .'<div class="row">'
    .'<div class="col-md-5 mb-4 mb-md-0">'
      .'<div class="bg-light rounded p-3 h-100">'
        .'<h5 class="fw-semibold mb-3"><i class="bi bi-eye"></i> Visualização</h5>'
        .'<ul class="list-group">'
          .'<li class="list-group-item"><strong>Número do BO:</strong> <span id="boDisplay">—</span></li>'
          .'<li class="list-group-item"><strong>Cliente:</strong> <span id="clienteDisplay">—</span></li>'
        .'</ul>'
      .'</div>'
    .'</div>'
    .'<div class="col-md-7">'
      .'<form action="createChamadoAdm.php" method="post" id="chamadoAdmForm">'
        .'<div class="mb-3">'
          .'<label for="bo" class="form-label fw-semibold">BO</label>'
          .'<div class="input-group">'
            .'<span class="input-group-text"><i class="bi bi-file-earmark-text"></i></span>'
            .'<input type="text" class="form-control" id="bo" name="bo" placeholder="Descreva o BO" required>'
          .'</div>'
        .'</div>'
        .'<div class="mb-3">'
          .'<label for="cliente" class="form-label fw-semibold">Cliente</label>'
          .'<div class="input-group">'
            .'<span class="input-group-text"><i class="bi bi-person"></i></span>'
            .'<select class="form-select" name="cliente" id="cliente" required>'
              .'<option value="">Selecione o cliente</option>'
              .$clienteOptions
            .'</select>'
          .'</div>'
        .'</div>'
        .'<div class="mb-3">'
          .'<label for="cargo" class="form-label fw-semibold">Cargo</label>'
          .'<div class="input-group">'
            .'<span class="input-group-text"><i class="bi bi-briefcase"></i></span>'
            .'<select class="form-select" name="cargo" id="cargo" required>'
              .'<option value="">Selecione o cargo</option>'
              .$cargoOptions
            .'</select>'
          .'</div>'
        .'</div>'
        .'<div class="mb-3">'
          .'<label for="conta" class="form-label fw-semibold">Conta</label>'
          .'<div class="input-group">'
            .'<span class="input-group-text"><i class="bi bi-person-circle"></i></span>'
            .'<select class="form-select" name="conta" id="conta" required>'
              .'<option value="">Selecione a conta</option>'
              .$contaOptions
            .'</select>'
          .'</div>'
        .'</div>'
        .'<div class="mb-3">'
          .'<label for="funcionario" class="form-label fw-semibold">Funcionário</label>'
          .'<div class="input-group">'
            .'<span class="input-group-text"><i class="bi bi-person-badge"></i></span>'
            .'<select class="form-select" name="funcionario" id="funcionario" required>'
              .'<option value="">Selecione o funcionário</option>'
              .$funcionarioOptions
            .'</select>'
          .'</div>'
        .'</div>'
        .'<div class="mb-3">'
          .'<label for="status" class="form-label fw-semibold">Status</label>'
          .'<div class="input-group">'
            .'<span class="input-group-text"><i class="bi bi-flag"></i></span>'
            .'<select class="form-select" name="status" id="status" required>'
              .'<option value="">Selecione o estado do chamado</option>'
              .'<option value="aberto">Aberto</option>'
              .'<option value="fechado">Fechado</option>'
            .'</select>'
          .'</div>'
        .'</div>'
        .'<button type="submit" class="btn btn-primary w-100"><i class="bi bi-plus-circle"></i> Abrir Chamado</button>'
      .'</form>'
      .$feedback
      .'<a href="../index.php" class="btn btn-link mt-3 w-100"><i class="bi bi-arrow-left"></i> Voltar para o início</a>'
    .'</div>'
  .'</div>'
  .'</div>'
  .'</div>'
  .'</div>';
include '../template.php';
?>
<script>
// Preview dinâmico dos campos
document.addEventListener('DOMContentLoaded', function() {
  const boInput = document.getElementById('bo');
  const clienteSelect = document.getElementById('cliente');
  const boDisplay = document.getElementById('boDisplay');
  const clienteDisplay = document.getElementById('clienteDisplay');
  if (boInput && boDisplay) {
    boInput.addEventListener('input', function() {
      boDisplay.textContent = boInput.value || '—';
    });
  }
  if (clienteSelect && clienteDisplay) {
    clienteSelect.addEventListener('change', function() {
      const selected = clienteSelect.options[clienteSelect.selectedIndex];
      clienteDisplay.textContent = selected.text || '—';
    });
  }
});
</script>

<?php

    foreach($_POST as $key => $value){
      if(!validaCampo($key)){
        die('campo inválido');
      }
    }

    insert(['bo', 'Id_cliente', 'Id_cargo', 'Id_conta','Id_funcionario', 'status'], $_POST, "Chamado");

?>