
import loadTable from './main.js';

const listaCard = document.getElementById("lista-chamado");
const template = document.getElementById("template-chamado");
const listaIDSinput = [];
loadTable('chamado', listaCard, template, [
	"chamado-codigo",
	"chamado-bo",
	"chamado-status",
	"chamado-data_abertura",
	"chamado-nome_cliente",
	"chamado-cpf_cliente",
	"chamado-nome_funcionario",
	"chamado-cargo"
], listaIDSinput);