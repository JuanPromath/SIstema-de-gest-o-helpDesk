import loadTable from './main.js';

const listaCard = document.getElementById("lista-chamado");
const template = document.getElementById("template-chamado");
let listaIDSinput = [];
loadTable('chamado', listaCard, template, ["conta-codigo", "conta-nome", "conta-cpf", "conta-idF", 'conta-email','conta-senha']);