import loadTable from './main.js';

const listaCard = document.getElementById("lista-cargo");
const template = document.getElementById("cargo-template");
loadTable('cargo', listaCard, template, ["cargo-codigo", "cargo-nome"]);