
carregarCargos();

async function carregarCargos(){//faz uma requisição ao back-end para pegar todos os cargos
      const res = await fetch('listar.php');//rota php que faz a consulta no bd
      const dados = await res.json();
      console.log(dados);//imprime oq foi captado
      montarLista(dados);//monta uma lista com esses dados
}

function montarLista(dados){//monta uma lista de cards a partir de cada registro de dados
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
  }

  function montarformEdit(item){
      const feedbackGeral = document.getElementById("feedback-geral");
      const formEdit = document.getElementById("edit-form");
      formEdit.innerHTML = '';
      const template = document.getElementById("edit-template");
      const node = template.content.cloneNode(true);
      node.querySelector('#cargo').value = item.nome;
      input = node.querySelector('#cargo');
      const form = node.querySelector("form");
      form.addEventListener('submit', async (e) => {
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
          carregarChamados();
        }catch(err){ console.error(err); feedbackGeral.textContent = 'Erro ao enviar os dados.'; }



      });

      formEdit.appendChild(node);
  
  }

  async function excluirChamado(id){
    if(!confirm('Excluir o chamado #' + id + '?')) return;
    try{
      const res = await fetch('excluir.php', {
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({ id })
      });
      if(!res.ok) throw new Error('Erro: '+res.status);
      await res.json();
      carregarChamados();
    }catch(e){ console.error(e); alert('Erro ao excluir.'); }
  }

