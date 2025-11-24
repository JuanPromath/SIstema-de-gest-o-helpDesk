// Quando o usuário digitar nos campos, o conteúdo aparece do outro lado
const boInput = document.getElementById('bo');
const cpfInput = document.getElementById('cliente');

const selectCargo = document.getElementById('cargo');
const selectFuncionario = document.getElementById('funcionario');

const infoCliente = document.getElementById('info-cliente');
const infoClienteTemplate = document.getElementById('template-cliente-info');

const boDisplay = document.getElementById('boDisplay');
const cpfDisplay = document.getElementById('cpfDisplay');

const cargoSelect = document.getElementById('cargo');

async function encontrarTodosFuncionarios(){
  const res = await fetch('todosFunc.php')
  const dados = await res.json();
  console.log(dados);

  selectFuncionario.innerHTML = '';
  let optionFirst = document.createElement('option');
  optionFirst.text = 'Selecione um funcionario';
  optionFirst.value = '';
  selectFuncionario.add(optionFirst);
  dados['res'].forEach(element => {
    console.log(element);
    let option = document.createElement('option');
    option.text = `${element['nome']} - ${element['email']}`;
    option.value = element['codigo'];
    selectFuncionario.add(option);
  });
}

async function encontrarFuncionarioPorID(id){
  const res = await fetch('funcPorID.php', {
    method:'post',
    headers:{'Content-Type':'application/json'},
    body:JSON.stringify({id})
})
  const dados = await res.json();
  console.log(dados);
  for(let i = 0; i < cargoSelect.options.length;i++){
    current = cargoSelect.options[i];
    if(current.value == dados['res']['id_cargo']){
      cargoSelect.selectedIndex = i;
      break;
    }
  }
  console.log(cargoSelect.options);
}

encontrarTodosFuncionarios();

async function encontrarFuncionarioCargo(idCargo){
  const res = await fetch('encontrafuncWhere.php', {
    method:'post',
    headers:{'Content-Type':'application/json'},
    body:JSON.stringify({idCargo})
})
  const dados = await res.json();
  console.log(dados);

  selectFuncionario.innerHTML = '';
  let optionFirst = document.createElement('option');
  optionFirst.text = 'Selecione um funcionario';
  optionFirst.value = '';
  selectFuncionario.add(optionFirst);

  if(dados['code'] == '200'){//faz a lista só com funcionario do cargo selecionado

    dados['res'].forEach(element => {
      console.log(element);
      let option = document.createElement('option');
      option.text = `${element['nome']} - ${element['email']}`;
      option.value = element['codigo'];
      selectFuncionario.add(option);
    });
  }else{//faz a lista com todos os funcionarios
    selectCargo.selectedIndex = 0;
    encontrarTodosFuncionarios();
  }

}

async function encontrarCliente(cpf){
    const res = await fetch('encontrarCliente.php', {
        method:'post',
        headers:{'Content-Type':'application/json'},
        body:JSON.stringify({cpf})
    })
    const dados = await res.json();
    console.log(dados);

    infoCliente.innerHTML = '';

    if(dados['code'] == '404'){
      infoCliente.innerText = dados['res'];
    }else if(dados['code'] == '200'){
      const node = infoClienteTemplate.content.cloneNode(true);
      node.querySelector('#nome-cliente').innerText = dados['res']['nome'];
      node.querySelector('#email-cliente').innerText = dados['res']['email'];
      infoCliente.appendChild(node);
    }

}

cpfInput.addEventListener('change', () =>{
  encontrarCliente(cpfInput.value);
})

selectCargo.addEventListener('change', () =>{
  console.log(selectCargo.value);
  encontrarFuncionarioCargo(selectCargo.value);
})

selectFuncionario.addEventListener('change',()=>{
    console.log(cargoSelect.options);
    encontrarFuncionarioPorID(selectFuncionario.value);
})

boInput.addEventListener('input', () => {
  boDisplay.textContent = boInput.value || '—';
});

cpfInput.addEventListener('input', () => {
  cpfDisplay.textContent = cpfInput.value || '—';
});

