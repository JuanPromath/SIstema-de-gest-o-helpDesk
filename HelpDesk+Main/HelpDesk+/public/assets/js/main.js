// Arquivo main.js copiado para public assets

export default async function loadTable(table, listaCard, template, classeNome, listaIDSinput) {
  try {
    if (!table || !listaCard || !template || !classeNome) {
      console.error('Parâmetros obrigatórios ausentes em loadTable');
      return;
    }
    const res = await fetch('select/listar.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ table })
    });
    if (!res.ok) throw new Error('Erro ao buscar dados: ' + res.status);
    const dados = await res.json();
    montarLista(dados.dados || dados, listaCard, template, classeNome, table, listaIDSinput);
  } catch (err) {
    console.error('Erro em loadTable:', err);
    listaCard.textContent = 'Erro ao carregar dados.';
  }
}

async function carregarCargos(){
      const res = await fetch('select/listar.php');
      const dados = await res.json();
      console.log(dados);
      montarLista(dados);
}

function montarLista(dados, listaCard, template, classeNome, table, listaInputsID) {
    listaCard.innerHTML = '';
    if (!Array.isArray(dados) || dados.length === 0) {
        listaCard.textContent = 'Nenhum registro encontrado.';
        return;
    }
    dados.forEach((item, index) => {
        const node = template.content.cloneNode(true);
        let i = 0;
        for (const prop in item) {
            if (classeNome[i] && node.querySelector(`.${classeNome[i]}`)) {
                node.querySelector(`.${classeNome[i]}`).textContent = item[prop];
            }
            i++;
        }
        const editarBtn = node.querySelector('.btn-editar');
        const excluirBtn = node.querySelector('.btn-excluir');
        if (editarBtn) {
            editarBtn.addEventListener('click', () => montarformEdit(item, listaInputsID, table, listaCard, template, classeNome));
        }
        if (excluirBtn) {
            excluirBtn.addEventListener('click', () => excluirChamado(item.codigo, listaCard, template, classeNome, table, listaInputsID));
        }
        listaCard.appendChild(node);
    });
}

function montarformEdit(item, listaInputsID, table, listaCard, template, classeNome) {
    const formEdit = document.getElementById("edit-form");
    formEdit.innerHTML = '';
    const templateEdit = document.getElementById("edit-template");
    const node = templateEdit.content.cloneNode(true);
    let chaves = Object.keys(item);
    let inputsList = [];
    listaInputsID.forEach((id, idx) => {
        const input = node.querySelector(`#${id}`);
        if (input) {
            input.value = item[chaves[idx + 1]] || '';
            inputsList.push(input);
        }
    });
    const feedbackGeral = node.getElementById ? node.getElementById("feedback-geral") : node.querySelector("#feedback-geral");
    const form = node.querySelector("form");
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const payload = { codigo: item.codigo };
        inputsList.forEach((input, idx) => {
            payload[chaves[idx + 1]] = input.value.trim();
        });
        try {
            const res = await fetch('select/update.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ table, payload })
            });
            if (!res.ok) throw new Error('Erro servidor ' + res.status);
            const j = await res.json();
            if (feedbackGeral) feedbackGeral.textContent = j.mensagem || 'Operação realizada.';
            form.reset();
            loadTable(table, listaCard, template, classeNome, listaInputsID);
        } catch (err) {
            console.error(err);
            if (feedbackGeral) feedbackGeral.textContent = 'Erro ao enviar os dados.';
        }
    });
    formEdit.appendChild(node);
}

async function excluirChamado(id, listaCard, template, classeNome, table, listaInputsID) {
    if (!confirm('Excluir o registro #' + id + '?')) return;
    try {
        const res = await fetch('select/excluir.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id, table })
        });
        if (!res.ok) throw new Error('Erro: ' + res.status);
        loadTable(table, listaCard, template, classeNome, listaInputsID);
    } catch (e) {
        console.error(e);
        alert('Erro ao excluir.');
    }
}
