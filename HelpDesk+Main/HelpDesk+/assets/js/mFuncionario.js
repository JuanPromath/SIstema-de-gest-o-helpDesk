
import loadTable from './main.js';

const listaCard = document.getElementById("lista-funcionario");
const template = document.getElementById("template-funcionario");
const listaIDSinput = ['func-nome', 'func-cpf', 'func-email', 'func-cargoID'];
loadTable('funcionario', listaCard, template, [
	"funcionario-nome",
	"funcionario-codigo",
	"funcionario-cargo",
	"funcionario-cargoID",
	"funcionario-cpf",
	"funcionario-email"
], listaIDSinput);