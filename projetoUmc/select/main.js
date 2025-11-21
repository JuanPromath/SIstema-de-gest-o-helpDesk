function teste(){

    console.log("restr");
    

}

carregarChamados();

async function carregarChamados(){
      const res = await fetch('listar.php');
      const dados = await res.json();
      console.log(dados);
      montarLista(dados);
}

function montarLista(dados){
    const listaChamados = document.getElementById("lista-chamados");
    listaChamados.innerHTML = '';
    const template = document.getElementById("cargo-template");
    if(!dados || dados.length === 0){ listaChamados.textContent = 'Nenhum chamado encontrado.'; return; }
    dados.forEach(item => {
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
          id: item.codigo,
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

