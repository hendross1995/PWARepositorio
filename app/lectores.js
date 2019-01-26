import {TablasDatos} from './funcionesGlobales.js';

window.addEventListener('load',iniciarEventos,true)

var tabla;

function iniciarEventos(){ 

  tabla = imprimirTablaUsuariosLectores("")
}


function imprimirTablaUsuariosLectores(e){
 return TablasDatos({
  tabla  : 'tabla_usuarios_lectores',
  url    : 'mostrar_lectores',
  data   : {id : e},
  columnas :  [ 
                {
                  className      : 'details-control centro sombra',
                  defaultContent : '<i class="fa fa-angle-right desglosar_menu_tabla"></i>'
                },
      
                { data: 'num', sClass: "centro"},
                { data: 'nombres'},
                { data: 'usuario'},
                { data: 'genero', sClass: "centro"},
                { data: 'fecha_creacion', sClass: "centro"},
                
                { data: 'cedula', sClass: "oculto"},
                { data: 'provincia', sClass: "oculto"}, 
                { data: 'canton', sClass: "oculto"}, 
                { data: 'parroquia', sClass: "oculto"}, 
                { data: 'direccion', sClass: "oculto"}, 
                { data: 'convencional', sClass: "oculto"}, 
                { data: 'celular', sClass: "oculto"}, 
              ],
  });
}

$('#tabla_usuarios_lectores tbody').on('click', 'td.details-control', function () {
   var tr  = $(this).closest('tr'),
       row = tabla.row(tr);
   if (row.child.isShown()) {
     tr.next('tr').removeClass('details-row');
     row.child.hide();
     tr.removeClass('shown'); 
     tr.find('td:first i').remove();
     tr.find('td:first').append('<i class="fa fa-angle-right desglosar_menu_tabla"></i>');
   }
   else {
     row.child(format(row.data())).show();
     tr.next('tr').addClass('details-row');
     tr.addClass('shown');
     tr.find('td:first i').remove();
     tr.find('td:first').append('<i class="fa fa-angle-down desglosar_menu_tabla"></i>');
   }
});

function format ( d ) {
                      
    return '<table class="table table-hover table-bordered table-striped">'+
        '<tr><td><b>Cédula:</b></td><td>'+(d.cedula == '' || d.cedula == null ?'No registrado': d.cedula)+'</td></tr>'+
        '<tr><td><b>Provincia:</b></td><td>'+(d.provincia == '' || d.provincia == null ?'No registrado': d.provincia)+'</td></tr>'+
        '<tr><td><b>Cantón:</b></td><td>'+(d.canton == '' || d.canton == null ?'No registrado': d.canton)+'</td></tr>'+
        '<tr><td><b>Parroquia:</b></td><td>'+(d.parroquia == '' || d.parroquia == null ?'No registrado': d.parroquia)+'</td></tr>'+
        '<tr><td><b>Dirección:</b></td><td>'+(d.direccion == '' || d.direccion == null ?'No registrado': d.direccion)+'</td></tr>'+
        '<tr><td><b>Convencional:</b></td><td>'+(d.convencional == '' || d.convencional == null ?'No registrado': d.convencional)+'</td></tr>'+
        '<tr><td><b>Celular:</b></td><td>'+(d.celular == '' || d.celular == null ?'No registrado': d.celular)+'</td></tr>'
        
    '</table>';
}
  
function quitarError(e){
  if(e.target.classList.contains("errorForm")){
      e.target.classList.remove("errorForm")
  }
}
