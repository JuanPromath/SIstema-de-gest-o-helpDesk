import loadTable from './main.js';

const listaCard = document.getElementById("lista-conta");
const template = document.getElementById("template-conta");
loadTable('Conta_Sistema', listaCard, template, ["conta-codigo", "conta-nome", "conta-cpf", "conta-idF", 'conta-email','conta-senha']);