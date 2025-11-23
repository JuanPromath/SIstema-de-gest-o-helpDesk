import loadTable from './main.js';

const listaCard = document.getElementById("lista-chamado");
const template = document.getElementById("template-chamado");
let listaIdsCard = ["chamado-codigo", "chamado-bo","chamado-status","chamado-IdCliente", "chamado-nome-cliente", "chamado-cpf-cliente", "chamado-Idfuncionario", "chamado-nome-funcionario", "chamado-cpf-funcionario", "chamado-cargoID", "chamado-cargo","chamado-IdConta", "chamado-nome-atendente", "chamado-cpf-atendente"];
let listaIDSinput = ['bo', 'status', 'id-cliente', 'Id_funcionario', 'Id_conta', 'id_cargo'];
loadTable('chamado', listaCard, template, listaIdsCard, listaIDSinput);