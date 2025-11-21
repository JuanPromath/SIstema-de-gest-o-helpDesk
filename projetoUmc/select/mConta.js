import loadTable from './main.js';

const listaCard = document.getElementById("lista-conta");
const template = document.getElementById("template-conta");
loadTable('Conta_Sistema', listaCard, template, ["cliente-codigo", "cliente-nome", "cliente-cpf", "cliente-email"]);