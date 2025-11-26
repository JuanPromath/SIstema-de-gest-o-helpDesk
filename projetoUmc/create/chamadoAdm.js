const selectFuncionario = document.getElementById('funcionario');
const selectCargo = document.getElementById('cargo');


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
    for(let i = 0; i < selectCargo.options.length;i++){
      let current = selectCargo.options[i];
      if(current.value == dados['res']['id_cargo']){
        selectCargo.selectedIndex = i;
        break;
      }
    }
    console.log(selectCargo.options);
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

  selectCargo.addEventListener('change', () =>{
    console.log(selectCargo.value);
    encontrarFuncionarioCargo(selectCargo.value);
  })
  
  selectFuncionario.addEventListener('change',()=>{
      console.log(selectCargo.options);
      encontrarFuncionarioPorID(selectFuncionario.value);
  })