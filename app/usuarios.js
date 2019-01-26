import { erroresFormulario,camposVaciosFormularios,
        query, exitoFormulario,TablasDatos,removerClassErrorForm,avisoFormulario} from './funcionesGlobales.js';

window.addEventListener('load',iniciarEventos,true)

var tabla;
var accionUsuario = null
var idUsuario = null
 
function iniciarEventos(){ 

  var boton_modificar_usuario = query(".tabla_usuarios")
  var boton_registrar_usuario = query(".boton_registrar_usuario")
  var actualizar_usuario = query("#actualizar_usuario")
  var errorInput = query("#FrmActualizarUsuario")
  var cargarCanton = query("#provincia_usuario")
  var cargarParroquia = query("#canton_usuario")

  actualizar_usuario.addEventListener('click',actualizarUsuario)
  boton_modificar_usuario.addEventListener('click',btnModificarUsuario)
  boton_registrar_usuario.addEventListener('click',btnRegistrarUsuario)
  errorInput.addEventListener('click',quitarError)
  cargarCanton.addEventListener('change',filtrarPorCanton)
  cargarParroquia.addEventListener('change',filtrarPorParroquia)

  tabla = imprimirTablaUsuarios("")
}

function btnRegistrarUsuario(e){
  accionUsuario = "registrar"
  query(".titulo_usuario").textContent = "Registrar Usuario"
  query(".actualizar_usuario").textContent = "REGISTRAR"
  query("#FrmActualizarUsuario").reset();
  query(".estado_usuario").checked  = true 
  query("#cedula_usuario").focus()  
  removerClassErrorForm({formulario:query("#FrmActualizarUsuario")})
}



function btnModificarUsuario(e){
  var clase = e.target.classList;
  var editar = e.target;
  if (clase.contains("fa-edit")) editar = e.target.parentElement

  if(clase.contains("boton_modificar_usuario") || clase.contains("fa-edit")){
    
    idUsuario = editar.id   
    accionUsuario = "modificar" 
    query(".titulo_usuario").textContent = "Modificar Usuario";
    query(".actualizar_usuario").textContent = "MODIFICAR" 
    query("#FrmActualizarUsuario").reset();
      
     
    var celdas =  editar.parentElement.parentElement.cells;    
    var check = celdas[4].textContent == "ACTIVO" ? true : false;
    var select_archivo = document.querySelector("#provincia_usuario").children
    var select_rol = document.querySelector("#rol_usuario").children
      
    Array.from(select_archivo).forEach(function(item){
       if(item.textContent == celdas[10].textContent){
           item.selected = true
           cargarCanton(item.value,celdas[11].textContent,celdas[12].textContent)
           return 
    }})  
      
      Array.from(select_rol).forEach(function(item){
       if(item.textContent == celdas[3].textContent){
           item.selected = true
           return 
    }})  

      
    query("#correo_usuario").value = celdas[2].textContent
    query("#estado_usuario").checked = check; 
    query("#cedula_usuario").value =  celdas[6].textContent
    query("#apellidos_usuario").value = celdas[7].textContent
    query("#nombres_usuario").value = celdas[8].textContent 
    query("#sexo_usuario").value = celdas[9].textContent
    query("#direccion_usuario").value = celdas[13].textContent
    query("#convencional_usuario").value = celdas[14].textContent  
    query("#celular_usuario").value = celdas[15].textContent 
      
    
    removerClassErrorForm({formulario:query("#FrmActualizarUsuario")})
  }
}


function cargarCanton(id,texto,texto2){
  let datos = new FormData();
  let usuario = query(".actualizar_usuario")
  let cancelarActUsuario = query(".boton_cancelar_actualizar_usuario")
    
  datos.append('provincia',id);
  usuario.disabled = true;
  cancelarActUsuario.disabled = true      
  
 $("#canton_usuario").html("<option value='0' selected> Cargando datos.</option>").prop('disabled',true);
  fetch('cargar_cantones',{  
    method: 'POST',  
    body: datos}) 
  .then(response => response.json())
  .then(data => {  
     let select = document.querySelector("#canton_usuario").children
      $("#canton_usuario").html(data).prop('disabled',false);
     Array.from(select).forEach(function(item){
       if(item.textContent == texto){
           item.selected = true;
           CargarParroquia(item.value,texto2)
           return}})
  
  }).catch(error => {
     avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
     $("#canton_usuario").html('<option value="0">Error</option>').prop('disabled',false);
     usuario.disabled = false
     cancelarActUsuario.disabled = false  
     query("#nombre_usuario").focus()
  });
}

function CargarParroquia(id,texto){
  let datos = new FormData();
  let usuario = query(".actualizar_usuario")
  let cancelarActUsuario = query(".boton_cancelar_actualizar_usuario")
    
  datos.append('canton',id);
  usuario.disabled = true;
  cancelarActUsuario.disabled = true     
  
  $("#parroquia_usuario").html("<option value='0' selected> Cargando datos...</option>").prop('disabled',true);
  fetch('cargar_parroquias',{  
    method: 'POST',
    body: datos}) 
  .then(response => response.json())
  .then(data => {  
     let select = document.querySelector("#parroquia_usuario").children
     $("#parroquia_usuario").html(data).prop('disabled',false)
     Array.from(select).forEach(function(item){
       if(item.textContent == texto){item.selected = true;return}})
     usuario.disabled = false
     cancelarActUsuario.disabled = false 
  
  }).catch(error => {
     avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
     $("#parroquia_usuario").html('<option value="0">Error</option>').prop('disabled',false);
     usuario.disabled = false
     cancelarActUsuario.disabled = false  
     query("#nombre_usuario").focus()
  });
}



function filtrarPorCanton(e){
  e.preventDefault();
  let datos = new FormData();
  let usuario = query(".actualizar_usuario")
  let cancelarActusuario = query(".boton_cancelar_actualizar_usuario")
    
  datos.append('provincia',e.target.value);
  usuario.disabled = true;
  cancelarActusuario.disabled = true     
  
 $("#canton_usuario").html("<option value='0' selected> Cargando datos.</option>").prop('disabled',true);
  fetch('cargar_cantones',{  
    method: 'POST', 
    body: datos}) 
  .then(response => response.json())
  .then(data => {  
     console.log(data)
      $("#canton_usuario").html(data).prop('disabled',false);
      usuario.disabled = false
      cancelarActusuario.disabled = false 
  }).catch(error => {
     avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
     $("#canton_usuario").html('<option value="0">Error</option>').prop('disabled',false);
     usuario.disabled = false
     cancelarActusuario.disabled = false  
     query("#nombre_usuario").focus()
  });
}

function filtrarPorParroquia(e){
  e.preventDefault();
    
  let datos = new FormData();
  let usuario = query(".actualizar_usuario")
  let cancelarActusuario = query(".boton_cancelar_actualizar_usuario")
    
  datos.append('canton',e.target.value);
  usuario.disabled = true;
  cancelarActusuario.disabled = true     
  
  $("#parroquia_usuario").html("<option value='0' selected> Cargando datos...</option>").prop('disabled',true);
  fetch('cargar_parroquias',{  
    method: 'POST',
    body: datos}) 
  .then(response => response.json())
  .then(data => {  
     $("#parroquia_usuario").html(data).prop('disabled',false)
     usuario.disabled = false
     cancelarActusuario.disabled = false 
  
  }).catch(error => {
     avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
     $("#parroquia_usuario").html('<option value="0">Error</option>').prop('disabled',false);
     usuario.disabled = false
     cancelarActusuario.disabled = false  
     query("#nombre_usuario").focus()
  });
}


function actualizarUsuario(e){
  e.preventDefault();
  var datos = new FormData(query("#FrmActualizarUsuario"));
  var UsuarioActualizar = {};
  const data = {
    formulario: query("#FrmActualizarUsuario")
  } 
  UsuarioActualizar = validarDatosUsuario(data)
  
  if(UsuarioActualizar["acceso"] === true){
     
    datos.get("estado_usuario")===null? datos.append("estado_usuario",null):''
    datos.append("codigo",idUsuario)
    datos.append("accion",accionUsuario)

    var Usuario = query(".actualizar_usuario")
    var cancelarActUsuario = query(".boton_cancelar_actualizar_usuario")
    var cerrarUsuario = query(".FrmActualizarUsuario")

    var titulo = Usuario.textContent;
    Usuario.textContent = Usuario.textContent.replace("AR","ANDO...")
    Usuario.disabled = true;
    cancelarActUsuario.disabled = true

    fetch('actualizar_usuarios',{
      method: 'POST',
      body: datos})
    .then(response => response.json()) 
    .then(data => {
      if(data.estado){
        avisoFormulario({aviso: "actualizarUsuario",mensaje: data.observacion,alerta:""})
        $("#frm_actualizar_usuarios").modal("hide")
        tabla.ajax.reload();
      }else{
        avisoFormulario({aviso: "actualizarUsuario", mensaje: data.observacion,alerta:"text-warning"})
      }
       console.log(data)
       Usuario.disabled = false
       cancelarActUsuario.disabled = false
       Usuario.textContent = titulo
    }).catch(error => {
      avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
      Usuario.textContent = titulo
      Usuario.disabled = false
      cancelarActUsuario.disabled = false
    });
  }
}

function validarDatosUsuario(inf){
  var datos = inf || {}
  var acceso = true;
  var excepto = [""]

  if(camposVaciosFormularios({formulario:datos["formulario"],excepto:excepto}))acceso = false

  return {
    acceso: acceso
  }
}

//************CARGAR PROVINCIAS Y ROLES*************//

(function(){

  $("#provincia_usuario").html("<option value='0' selected> Cargando datos.</option>").prop('disabled',true);
  fetch('cargar_provincias') 
  .then(response => response.json())
  .then(data => {  
    $("#provincia_usuario").html(data).prop('disabled',false);
  }).catch(error => {
    avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
    $("#provincia_usuario").html('<option value="0">Error</option>').prop('disabled',false);
  });
    
$("#rol_usuario").html("<option value='0' selected> Cargando datos.</option>").prop('disabled',true);
  fetch('cargar_roles') 
  .then(response => response.json())
  .then(data => {  
    $("#rol_usuario").html(data).prop('disabled',false);
  }).catch(error => {
    avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
    $("#rol_usuario").html('<option value="0">Error</option>').prop('disabled',false);
  });    
    
})()

//******************************************//

function imprimirTablaUsuarios(vacio){
 return TablasDatos({
  tabla  : 'tabla_usuarios',
  url    : 'mostrar_usuarios',
  data   : {id : vacio},
  columnas :  [ 
                {
                  className      : 'details-control centro sombra',
                  defaultContent : '<i class="fa fa-angle-right desglosar_menu_tabla"></i>'
                },
      
                { data: 'num', sClass: "centro"},
                { data: 'usuario', sClass: "centro"},
                { data: 'rol'},
                { data: 'estado', sClass: "centro"},
                { data: 'accion', sClass: "centro"},
                
                { data: 'cedula', sClass: "oculto"},
                { data: 'nombres', sClass: "oculto"}, 
                { data: 'apellidos', sClass: "oculto"},
                { data: 'sexo', sClass: "oculto"},
                { data: 'provincia', sClass: "oculto"}, 
                { data: 'canton', sClass: "oculto"}, 
                { data: 'parroquia', sClass: "oculto"}, 
                { data: 'direccion', sClass: "oculto"}, 
                { data: 'convencional', sClass: "oculto"}, 
                { data: 'celular', sClass: "oculto"}, 
                { data: 'usuario', sClass: "oculto"}, 
              
              ],
  });
}

$('#tabla_usuarios tbody').on('click', 'td.details-control', function () {
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
        '<tr><td><b>Apellidos:</b></td><td>'+(d.apellidos == '' || d.apellidos == null ?'No registrado': d.apellidos)+'</td></tr>'+
        '<tr><td><b>Nombres:</b></td><td>'+(d.nombres == '' || d.nombres == null ?'No registrado': d.nombres)+'</td></tr>'+
        '<tr><td><b>Género:</b></td><td>'+(d.sexo == '' || d.sexo == null ?'No registrado': d.sexo)+'</td></tr>'+
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
