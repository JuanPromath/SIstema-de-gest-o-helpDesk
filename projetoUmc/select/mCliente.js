import loadTable from './main.js';

const listaCard = document.getElementById("lista-cliente");
const template = document.getElementById("template-cliente");
loadTable('Cliente', listaCard, template, ["cliente-codigo", "cliente-nome", "cliente-cpf", "cliente-email"]);
