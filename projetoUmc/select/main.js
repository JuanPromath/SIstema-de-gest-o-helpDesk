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
  
      //editarBtn.addEventListener('click', ()=> abrirEdicao(item));
      //excluirBtn.addEventListener('click', ()=> excluirChamado(item.id));
  
      listaChamados.appendChild(node);
    });
  }


