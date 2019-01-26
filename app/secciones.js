import { erroresFormulario,camposVaciosFormularios,
        query, exitoFormulario,TablasDatos,removerClassErrorForm,avisoFormulario} from './funcionesGlobales.js'; 

window.addEventListener('load',iniciarEventos,true)

var tabla; 
var accionSeccion = null;
var idSeccion = null

function iniciarEventos(){ 
  var boton_modificar_seccion = query(".tabla_secciones")
  var boton_registrar_seccion = query(".boton_registrar_seccion")  
  var actualizar_seccion = query("#actualizar_seccion")  
  var errorInput = query("#FrmActualizarSeccion") 

  actualizar_seccion.addEventListener('click',actualizarSeccion)
  boton_modificar_seccion.addEventListener('click',btnModificarSeccion)
  boton_registrar_seccion.addEventListener('click',btnRegistrarSeccion)
  errorInput.addEventListener('click',quitarError)
  
  tabla = imprimirTablaSecciones("")
} 

function btnRegistrarSeccion(e){
  accionSeccion = "registrar"
  query(".titulo_seccion").textContent = "Registrar sección"
  query(".actualizar_seccion").textContent = "REGISTRAR"
  query("#FrmActualizarSeccion").reset();
  query(".estado_seccion").checked  = true
  query("#nombre_seccion").focus() 
  removerClassErrorForm({formulario:query("#FrmActualizarSeccion")})
} 

function btnModificarSeccion(e){
  var clase = e.target.classList;
  var editar = e.target;
  if (clase.contains("fa-edit")) editar = e.target.parentElement
  
  if(clase.contains("boton_modificar_seccion") || clase.contains("fa-edit")){
    idSeccion = editar.id  
    accionSeccion = "modificar" 
     
    var celdas =  editar.parentElement.parentElement.cells;    
    var check = celdas[3].textContent == "ACTIVO" ? true : false; 
    var select = document.querySelector("#archivo_seccion").children
    
    Array.from(select).forEach(function(item){
       if(item.textContent == celdas[2].textContent){item.selected = true;return}})
    
    query(".titulo_seccion").textContent = "Modificar sección";
    query(".actualizar_seccion").textContent = "MODIFICAR" 
    query("#nombre_seccion").value = celdas[1].textContent; 
    query(".estado_seccion").checked = check;
    removerClassErrorForm({formulario:query("#FrmActualizarSeccion")})
  } 
}

function actualizarSeccion(e){  
  e.preventDefault();
  var datos = new FormData(query("#FrmActualizarSeccion"));
  var seccionActualizar = {}; 
  const data = { 
    formulario: query("#FrmActualizarSeccion") 
  }      
  seccionActualizar = validarDatosSeccion(data) 
  if(seccionActualizar["acceso"] === true){
    
    datos.get("estado_seccion")===null? datos.append("estado_seccion",null):''
    datos.append("codigo",idSeccion) 
    datos.append("accion",accionSeccion)     
     
    var seccion = query(".actualizar_seccion")
    var cancelarActSeccion = query(".boton_cancelar_actualizar_seccion")
    var cerrarSeccion = query(".FrmActualizarSeccion")
    
    var titulo = seccion.textContent; 
    seccion.textContent = seccion.textContent.replace("AR","ANDO...") 
    seccion.disabled = true;
    cancelarActSeccion.disabled = true 
    
    fetch('actualizar_secciones',{  
      method: 'POST',
      body: datos}) 
    .then(response => response.json())
    .then(data => {
      if(data.estado){ 
        avisoFormulario({aviso: "actualizarSeccion",mensaje: data.observacion,alerta:""})  
        $("#frm_actualizar_secciones").modal("hide") 
        tabla.ajax.reload();
      }else{
        avisoFormulario({aviso: "actualizarSeccion", mensaje: data.observacion,alerta:"text-warning"})
      }
       seccion.disabled = false
       cancelarActSeccion.disabled = false 
       seccion.textContent = titulo  
    }).catch(error => { 
      avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
      seccion.textContent = titulo
      seccion.disabled = false 
      query("#nombre_seccion").focus()
      cancelarActSeccion.disabled = false
    });
  } 
}

(function(){
  var datos = new FormData();
  datos.append('id_archivo','');
  $("#archivo_seccion").html("<option value='0'> Cargando datos.</option>").prop('disabled',true);
  fetch('cargar_archivos',{  
    method: 'POST',
    body: datos}) 
  .then(response => response.json())
  .then(data => {  
    $("#archivo_seccion").html(data).prop('disabled',false);
  }).catch(error => {
    avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
    $("#archivo_seccion").html('<option value="0">Error</option>').prop('disabled',false);
  });
})()


function validarDatosSeccion(inf){
  var datos = inf || {}
  var acceso = true;
  var excepto = [""] 
  
  if(camposVaciosFormularios({formulario:datos["formulario"],excepto:excepto}))acceso = false
    
  return {
    acceso: acceso
  }    
}

function imprimirTablaSecciones(vacio){    
 return TablasDatos({   
  tabla  : 'tabla_secciones',
  url    : 'mostrar_secciones', 
  data   : { 
            id : vacio  
          },   
  columnas :  [
                { data: 'num', sClass: "centro"},  
                { data: 'nombre'},
                { data: 'archivo',sClass: "color-celda"}, 
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
 



