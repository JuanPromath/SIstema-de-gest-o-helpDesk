import loadTable from './main.js';

const listaCard = document.getElementById("lista-funcionario");
const template = document.getElementById("template-funcionario");
loadTable('funcionario', listaCard, template, ["funcionario-nome", "funcionario-codigo", "funcionario-cargo", "funcionario-cargoID", "funcionario-cpf", "funcionario-email"]);