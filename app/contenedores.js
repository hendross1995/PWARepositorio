import { erroresFormulario,camposVaciosFormularios,
        query, exitoFormulario,TablasDatos,removerClassErrorForm,avisoFormulario} from './funcionesGlobales.js'; 

window.addEventListener('load',iniciarEventos,true)

var tabla; 
var accionContenedor = null;
var idContenedor = null

function iniciarEventos(){ 
  var boton_modificar_contenedor = query(".tabla_contenedores")
  var boton_registrar_contenedor = query(".boton_registrar_contenedor")  
  var actualizar_contenedor = query("#actualizar_contenedor")
  var archivos = query("#archivos")  
  var secciones = query("#secciones")  
  var errorInput = query("#FrmActualizarContenedor") 

  actualizar_contenedor.addEventListener('click',actualizarContenedor)
  boton_modificar_contenedor.addEventListener('click',btnModificarContenedor)
  boton_registrar_contenedor.addEventListener('click',btnRegistrarContenedor)
  archivos.addEventListener('change',FiltrarSeccionesPorArchivo)
  secciones.addEventListener('change',FiltrarNivelPorSeccion)
  errorInput.addEventListener('click',quitarError)
  
  tabla = imprimirTablaContenedores("")
} 

function btnRegistrarContenedor(e){
  accionContenedor = "registrar"
  query(".titulo_contenedor").textContent = "Registrar contenedor"
  query(".actualizar_contenedor").textContent = "REGISTRAR"
  query("#FrmActualizarContenedor").reset();
  query("#secciones").innerHTML = ""
  query("#nivel_contenedor").innerHTML = ""
  query(".estado_contenedor").checked  = true
  query("#nombre_contenedor").focus() 
  removerClassErrorForm({formulario:query("#FrmActualizarContenedor")})
} 

function btnModificarContenedor(e){
  var clase = e.target.classList;
  var editar = e.target;
  if (clase.contains("fa-edit")) editar = e.target.parentElement
  
  if(clase.contains("boton_modificar_contenedor") || clase.contains("fa-edit")){
    idContenedor = editar.id  
    accionContenedor = "modificar" 
     
    var celdas =  editar.parentElement.parentElement.cells;    
    var check = celdas[6].textContent == "ACTIVO" ? true : false; 
    var select_archivo = document.querySelector("#archivos").children
    
    Array.from(select_archivo).forEach(function(item){
       if(item.textContent == celdas[3].textContent){
           item.selected = true
           CargarSeccionesPorArchivo(item.value,celdas[4].textContent,celdas[5].textContent)
           return 
    }})

    query(".titulo_contenedor").textContent = "Modificar contenedor";
    query(".actualizar_contenedor").textContent = "MODIFICAR" 
    query("#codigo_contenedor").value = celdas[1].textContent; 
    query("#nombre_contenedor").value = celdas[2].textContent; 
    query(".estado_contenedor").checked = check;
    removerClassErrorForm({formulario:query("#FrmActualizarContenedor")})
  } 
}


function CargarSeccionesPorArchivo(id,texto,texto2){
  let datos = new FormData();
  let contenedor = query(".actualizar_contenedor")
  let cancelarActContenedor = query(".boton_cancelar_actualizar_contenedor")
    
  datos.append('archivo_seccion',id);
  contenedor.disabled = true;
  cancelarActContenedor.disabled = true     
  
 $("#secciones").html("<option value='0' selected> Cargando datos.</option>").prop('disabled',true);
  fetch('cargar_secciones',{  
    method: 'POST', 
    body: datos}) 
  .then(response => response.json())
  .then(data => {  
     let select = document.querySelector("#secciones").children
      $("#secciones").html(data).prop('disabled',false);
     Array.from(select).forEach(function(item){
       if(item.textContent == texto){
           item.selected = true;
           CargarSeccionesPorSeccion(item.value,texto2)
           return}})
  
  }).catch(error => {
     avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
     $("#secciones").html('<option value="0">Error</option>').prop('disabled',false);
     contenedor.disabled = false
     cancelarActContenedor.disabled = false  
     query("#nombre_contenedor").focus()
  });
}

function CargarSeccionesPorSeccion(id,texto){
  let datos = new FormData();
  let contenedor = query(".actualizar_contenedor")
  let cancelarActContenedor = query(".boton_cancelar_actualizar_contenedor")
    
  datos.append('seccion_nivel',id);
  contenedor.disabled = true;
  cancelarActContenedor.disabled = true     
  
  $("#nivel_contenedor").html("<option value='0' selected> Cargando datos...</option>").prop('disabled',true);
  fetch('cargar_niveles',{  
    method: 'POST',
    body: datos}) 
  .then(response => response.json())
  .then(data => {  
     let select = document.querySelector("#nivel_contenedor").children
     $("#nivel_contenedor").html(data).prop('disabled',false)
     Array.from(select).forEach(function(item){
       if(item.textContent == texto){item.selected = true;return}})
     contenedor.disabled = false
     cancelarActContenedor.disabled = false 
  
  }).catch(error => {
     avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
     $("#nivel_contenedor").html('<option value="0">Error</option>').prop('disabled',false);
     contenedor.disabled = false
     cancelarActContenedor.disabled = false  
     query("#nombre_contenedor").focus()
  });
}



function FiltrarSeccionesPorArchivo(e){
  e.preventDefault();
  let datos = new FormData();
  let contenedor = query(".actualizar_contenedor")
  let cancelarActContenedor = query(".boton_cancelar_actualizar_contenedor")
    
  datos.append('archivo_seccion',e.target.value);
  contenedor.disabled = true;
  cancelarActContenedor.disabled = true     
  
 $("#secciones").html("<option value='0' selected> Cargando datos.</option>").prop('disabled',true);
  fetch('cargar_secciones',{  
    method: 'POST', 
    body: datos}) 
  .then(response => response.json())
  .then(data => {  
     let select = document.querySelector("#secciones").children
      $("#secciones").html(data).prop('disabled',false);
      contenedor.disabled = false
      cancelarActContenedor.disabled = false 
  }).catch(error => {
     avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
     $("#secciones").html('<option value="0">Error</option>').prop('disabled',false);
     contenedor.disabled = false
     cancelarActContenedor.disabled = false  
     query("#nombre_contenedor").focus()
  });
}

function FiltrarNivelPorSeccion(e){
  e.preventDefault();
    
  let datos = new FormData();
  let contenedor = query(".actualizar_contenedor")
  let cancelarActContenedor = query(".boton_cancelar_actualizar_contenedor")
    
  datos.append('seccion_nivel',e.target.value);
  contenedor.disabled = true;
  cancelarActContenedor.disabled = true     
  
  $("#nivel_contenedor").html("<option value='0' selected> Cargando datos...</option>").prop('disabled',true);
  fetch('cargar_niveles',{  
    method: 'POST',
    body: datos}) 
  .then(response => response.json())
  .then(data => {  
     let select = document.querySelector("#nivel_contenedor").children
     $("#nivel_contenedor").html(data).prop('disabled',false)
     contenedor.disabled = false
     cancelarActContenedor.disabled = false 
  
  }).catch(error => {
     avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
     $("#nivel_contenedor").html('<option value="0">Error</option>').prop('disabled',false);
     contenedor.disabled = false
     cancelarActContenedor.disabled = false  
     query("#nombre_contenedor").focus()
  });
}

function actualizarContenedor(e){  
  e.preventDefault();
  var datos = new FormData(query("#FrmActualizarContenedor"));
  var contenedorActualizar = {}; 
  const data = { 
    formulario: query("#FrmActualizarContenedor") 
  }      
  contenedorActualizar = validarDatosContenedor(data) 
  if(contenedorActualizar["acceso"] === true){
    
    datos.get("estado_contenedor")===null? datos.append("estado_contenedor",null):''
    datos.append("codigo",idContenedor) 
    datos.append("accion",accionContenedor)     
     
    var contenedor = query(".actualizar_contenedor")
    var cancelarActContenedor = query(".boton_cancelar_actualizar_contenedor")
    var cerrarContenedor = query(".FrmActualizarContenedor")
    
    var titulo = contenedor.textContent; 
    contenedor.textContent = contenedor.textContent.replace("AR","ANDO...") 
    contenedor.disabled = true;
    cancelarActContenedor.disabled = true 
    
    fetch('actualizar_contenedores',{  
      method: 'POST',
      body: datos}) 
    .then(response => response.json())
    .then(data => {
      if(data.estado){ 
        avisoFormulario({aviso: "actualizarContenedor",mensaje: data.observacion,alerta:""})  
        $("#frm_actualizar_contenedores").modal("hide") 
        tabla.ajax.reload();
      }else{
        avisoFormulario({aviso: "actualizarContenedor", mensaje: data.observacion,alerta:"text-warning"})
      }
       contenedor.disabled = false
       cancelarActContenedor.disabled = false 
       contenedor.textContent = titulo  
    }).catch(error => { 
      avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
      contenedor.textContent = titulo
      contenedor.disabled = false 
      query("#nombre_contenedor").focus()
      cancelarActContenedor.disabled = false
    });
  } 
}

(function(){
  var datos = new FormData();
  datos.append('id','');
  $("#archivos").html("<option value='0' selected> Cargando datos.</option>").prop('disabled',true);
  fetch('cargar_archivos',{  
    method: 'POST',
    body: datos}) 
  .then(response => response.json())
  .then(data => {  
    $("#archivos").html(data).prop('disabled',false);
  }).catch(error => {
    avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
    $("#archivos").html('<option value="0">Error</option>').prop('disabled',false);
  });
})()


function validarDatosContenedor(inf){
  var datos = inf || {}
  var acceso = true;
  var excepto = [""]
  
  if(camposVaciosFormularios({formulario:datos["formulario"],excepto:excepto}))acceso = false
    
  return {
    acceso: acceso
  }    
}

function imprimirTablaContenedores(vacio){    
 return TablasDatos({   
  tabla  : 'tabla_contenedores',
  url    : 'mostrar_contenedores', 
  data   : {id : vacio},   
  columnas :  [
                { data: 'num', sClass: "centro"},  
                { data: 'codigo', sClass: "centro"},
                { data: 'nombre'},
                { data: 'archivo', sClass: "color-celda"}, 
                { data: 'seccion', sClass: "color-celda"}, 
                { data: 'nivel', sClass: "color-celda"}, 
                { data: 'estado', sClass: "centro"}, 
                { data: 'accion', sClass: "centro"}   
              ],
  }); 
}

function quitarError(e){
  if(e.target.classList.contains("errorForm")){
      e.target.classList.remove("errorForm") 
  }
}
 



