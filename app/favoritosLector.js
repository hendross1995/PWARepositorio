import {TablasDatos} from './funcionesGlobales.js';

window.addEventListener('load',iniciarEventos,true)

var tabla;

function iniciarEventos(){ 
  tabla = imprimirTablaFavoritos("")
}
  
function imprimirTablaFavoritos(a){
 return TablasDatos({
  tabla  : 'tabla_favoritos',
  url    : 'cargar_favoritos',
  data   : {id : a},
  columnas :  [ 
                { data: 'num', sClass: 'centro'},
                { data: 'ficha', sClass: 'centro'},
                { data: 'nombre'},
                { data: 'total', sClass: 'centro'},
              ],
  });
}
 