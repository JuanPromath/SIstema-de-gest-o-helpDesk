const ListaChamadosAberto = document.getElementById('chamados-fechados');
const ListaChamadosFechado = document.getElementById('chamados-abertos');
const templateCardChamado = Document.getElementById('chamado-template');

async function pegarChamadosPorFuncionario(){
    const res = await fetch('chamadoPorFunc.php', {
        method:'post',
        headers:{'Content-Type':'application/json'},
        body:JSON.stringify(payload)
      })

}


