
import loadTable from './main.js';

const listaCard = document.getElementById("lista-cargo");
const template = document.getElementById("cargo-template");
const listaIDSinput = ['cargo'];
loadTable('cargo', listaCard, template, ["cargo-codigo", "cargo-nome"], listaIDSinput);