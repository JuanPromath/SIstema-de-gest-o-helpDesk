
//carregarCargos();

export default async function loadTable(table, listaCard, template, classeNome, listaIDSinput){
    console.log(JSON.stringify({table}));
    const res = await fetch('listar.php',{
      method:'POST',
      headers:{'Content-Type':'application/json'},
      body: JSON.stringify({table})
    })
    const dados = await res.json();
    console.log(dados);
    montarLista(dados, listaCard, template, classeNome, table, listaIDSinput);//dados, listCard é a div que a lista vai ser feita
}

async function carregarCargos(){//faz uma requisição ao back-end para pegar todos os cargos
      const res = await fetch('listar.php');//rota php que faz a consulta no bd
      const dados = await res.json();
      console.log(dados);//imprime oq foi captado
      montarLista(dados);//monta uma lista com esses dados
}

function montarLista(dados, listaCard, template, classeNome, table, listaInputsID){//monta uma lista de cards a partir de cada registro de dados
  //const listaChamados = document.getElementById("lista-cargo");//div mãe dessa lista
  listaCard.innerHTML = '';
  if(!dados || dados.length === 0){
    listaCard.textContent = 'Nenhum chamado encontrado.';
    return;
  }
  const debug = document.getElementById('debug')
  console.log(debug);
  let dadosMain = dados[0];

  dadosMain.forEach(item => {//itera cada item de dados, e faz um card
    //console.table(item);  
    const node = template.content.cloneNode(true);
    let index = dadosMain.findIndex(current => current === item);
    let i = 0;
    for(const prop in item){
      node.querySelector(`.${classeNome[i]}`).textContent = item[prop];
      i++;
    }
    //node.querySelector('.cargo-codigo').textContent = item.codigo;
    //node.querySelector('.cargo-nome').textContent = item.nome;
    const editarBtn = node.querySelector('.btn-editar');
    const excluirBtn = node.querySelector('.btn-excluir');

    editarBtn.addEventListener('click', ()=> montarformEdit(dados[1][index], listaInputsID));
    excluirBtn.addEventListener('click', ()=> excluirChamado(item.codigo, listaCard, template, classeNome, table, listaInputsID));

    listaCard.appendChild(node);
  });
}

/*function montarLista(dados){//monta uma lista de cards a partir de cada registro de dados
    const listaChamados = document.getElementById("lista-cargo");//div mãe dessa lista
    listaChamados.innerHTML = '';
    const template = document.getElementById("cargo-template");//pega o template
    if(!dados || dados.length === 0){
      listaChamados.textContent = 'Nenhum chamado encontrado.';
      return;
    }
    dados.forEach(item => {//itera cada item de dados, e faz um card
      console.table(item);  
      const node = template.content.cloneNode(true);
      node.querySelector('.cargo-codigo').textContent = item.codigo;
      node.querySelector('.cargo-nome').textContent = item.nome;
      const editarBtn = node.querySelector('.btn-editar');
      const excluirBtn = node.querySelector('.btn-excluir');
  
      editarBtn.addEventListener('click', ()=> montarformEdit(item));
      excluirBtn.addEventListener('click', ()=> excluirChamado(item.codigo));
  
      listaChamados.appendChild(node);
    });
  }*/

  function montarformEdit(item, listaInputsID){
      console.table(item);
      const formEdit = document.getElementById("edit-form");
      formEdit.innerHTML = '';
      const template = document.getElementById("edit-template");
      const node = template.content.cloneNode(true);
      let chaves =  Object.keys(item);
      let i = 1
      listaInputsID.forEach(id =>{
        console.log(node.querySelector(id));
        node.querySelector(`#${id}`).value = item[chaves[i]];
        i++;
      });
      //const feedbackGeral = node.getElementById("feedback-geral");
      //input = node.querySelector('#cargo');
      //const form = node.querySelector("form");
      //console.log("formulario");
      //console.log(form);
      /*form.addEventListener('submit', async (e) => {
        e.preventDefault();
        nomeInput = input.value;
        const payload = {
          codigo: item.codigo,
          nome : nomeInput.trim()
        }

        try{
          const res = await fetch('update.php', {
            method:'POST',
            headers:{'Content-Type':'application/json'},
            body: JSON.stringify(payload)
          });
          console.log(JSON.stringify(payload));
          if(!res.ok) throw new Error('Erro servidor '+res.status);
          const j = await res.json();
          feedbackGeral.textContent = j.mensagem || 'Operação realizada.';
          form.reset();
          //loadTable(dados, listaCard, template, classeNome,)
        }catch(err){ console.error(err); feedbackGeral.textContent = 'Erro ao enviar os dados.'; }



      });*/

      formEdit.appendChild(node);
  
  }

  async function excluirChamado(id, listaCard, template, classeNome, table, listaInputsID){
    console.log(JSON.stringify({ id, table }));
    if(!confirm('Excluir o registro #' + id + '?')) return;
    try{
      const res = await fetch('excluir.php', {
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({ id, table })
      });
      if(!res.ok) throw new Error('Erro: '+res.status);
      console.log("remonta");
      console.table(table);
      loadTable(table, listaCard, template, classeNome, listaInputsID);
    }catch(e){ 
      console.error(e); 
      alert('Erro ao excluir.'); 
    }
  }

