const ListaChamadosAberto = document.getElementById('chamados-abertos');
const ListaChamadosFechado = document.getElementById('chamados-fechados');
const templateCardChamado = document.getElementById('chamado-template');

async function pegarChamadosPorFuncionario(){
    const res = await fetch('chamadoPorFunc.php');
    const dados = await res.json();
    console.log(dados);
    montarListaChamado(dados);
}

async function atualizarStatus(item, novoStatus){
    console.table(item);
    console.log(novoStatus);
    const payload = {
        codigo: item.codigo,
        status : novoStatus
    }
    const res = await fetch('../select/update.php', {
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({table : 'chamado', payload})
      });
    console.log(JSON.stringify({table : 'chamado', payload}));
    console.log(res.json());
    pegarChamadosPorFuncionario();

}

function montarListaChamado(dados){
    console.log(dados);
    ListaChamadosAberto.innerHTML = '';
    ListaChamadosFechado.innerHTML = '';
    dados.forEach(item => {
        const node = templateCardChamado.content.cloneNode(true);
        node.querySelector(".chamado-bo").textContent = item['bo'];
        node.querySelector(".chamado-nome-cliente").textContent = item['nome'];
        node.querySelector(".chamado-status").textContent = item['status'];
        const selectStatus = node.getElementById("status");
        selectStatus.selectedIndex = item['status'] == 'aberto' ? 0 : 1;
        node.getElementById("atualizar").addEventListener('click',() => atualizarStatus(item, selectStatus.value));
        if(selectStatus.selectedIndex == 1){
            ListaChamadosAberto.appendChild(node);
        }else{
            ListaChamadosFechado.appendChild(node);
        }

    })    
}


pegarChamadosPorFuncionario();


