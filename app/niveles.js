import { erroresFormulario,camposVaciosFormularios,
        query, exitoFormulario,TablasDatos,removerClassErrorForm,avisoFormulario} from './funcionesGlobales.js'; 

window.addEventListener('load',iniciarEventos,true)

var tabla; 
var accionNivel = null;
var idNivel = null

function iniciarEventos(){ 
  var boton_modificar_nivel = query(".tabla_niveles")
  var boton_registrar_nivel = query(".boton_registrar_nivel")  
  var actualizar_nivel = query("#actualizar_nivel")
  var archivo_seccion_niveles = query("#archivo_seccion_niveles")  
  var errorInput = query("#FrmActualizarNivel") 

  actualizar_nivel.addEventListener('click',actualizarNivel)
  boton_modificar_nivel.addEventListener('click',btnModificarNivel)
  boton_registrar_nivel.addEventListener('click',btnRegistrarNivel)
  archivo_seccion_niveles.addEventListener('change',CargarSeccionesPorArchivos)
  errorInput.addEventListener('click',quitarError)
  
  tabla = imprimirTablaNiveles("")
} 

function btnRegistrarNivel(e){
  accionNivel = "registrar"
  query(".titulo_nivel").textContent = "Registrar nivel"
  query(".actualizar_nivel").textContent = "REGISTRAR"
  query("#FrmActualizarNivel").reset();
  query("#seccion_nivel").innerHTML = ""
  query(".estado_nivel").checked  = true
  query("#nombre_nivel").focus() 
  removerClassErrorForm({formulario:query("#FrmActualizarNivel")})
} 

function btnModificarNivel(e){
  var clase = e.target.classList;
  var editar = e.target;
  if (clase.contains("fa-edit")) editar = e.target.parentElement
  
  if(clase.contains("boton_modificar_nivel") || clase.contains("fa-edit")){
    idNivel = editar.id  
    accionNivel = "modificar" 
    query("#FrmActualizarNivel").reset();
    
    let celdas =  editar.parentElement.parentElement.cells;    
    let check = celdas[4].textContent == "ACTIVO" ? true : false; 
    let select = document.querySelector("#archivo_seccion_niveles").children
    
    Array.from(select).forEach(function(item){
       if(item.textContent == celdas[2].textContent){
           item.selected = true
           CargarSecciones(item.value, celdas[3].textContent)
           return
       }})
      
    query(".titulo_nivel").textContent = "Modificar nivel";
    query(".actualizar_nivel").textContent = "MODIFICAR" 
    query("#nombre_nivel").value = celdas[1].textContent; 
    query(".estado_nivel").checked = check;
    removerClassErrorForm({formulario:query("#FrmActualizarNivel")})
  } 
}


//function cargarSelectUniversal(select,value){
//    Array.from(select).forEach(function(item){
//       if(item.textContent == value){
//           item.selected = true
//           CargarSeccionesPorArchivosModificar(item.value, celdas[2].textContent)
//           return
//       }})
//}

function CargarSecciones(id,texto){
  let datos = new FormData();
  let nivel = query(".actualizar_nivel")
  let cancelarActNivel = query(".boton_cancelar_actualizar_nivel")
    
  datos.append('archivo_seccion',id);
  nivel.disabled = true;
  cancelarActNivel.disabled = true     
  
  $("#seccion_nivel").html("<option value='0' selected> Cargando datos...</option>").prop('disabled',true);
  fetch('cargar_secciones',{  
    method: 'POST',
    body: datos}) 
  .then(response => response.json())
  .then(data => {  
     let select = document.querySelector("#seccion_nivel").children
     $("#seccion_nivel").html(data).prop('disabled',false)
     Array.from(select).forEach(function(item){
       if(item.textContent == texto){item.selected = true;return}})
     nivel.disabled = false
     cancelarActNivel.disabled = false 
  
  }).catch(error => {
     avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
     $("#seccion_nivel").html('<option value="0">Error</option>').prop('disabled',false);
     nivel.disabled = false
     cancelarActNivel.disabled = false  
     query("#nombre_nivel").focus()
  });
}


function CargarSeccionesPorArchivos(e){
  
  e.preventDefault();
    
  let nivel = query(".actualizar_nivel")
  let cancelarActNivel = query(".boton_cancelar_actualizar_nivel")
  let datos = new FormData();
    
  nivel.disabled = true;
  cancelarActNivel.disabled = true     
  datos.append('archivo_seccion',e.target.value);
    
  $("#seccion_nivel").html("<option value='0' selected> Cargando datos.</option>").prop('disabled',true);
    
  fetch('cargar_secciones',{  
    method: 'POST',
    body: datos}) 
  .then(response => response.json())
  .then(data => {  
    $("#seccion_nivel").html(data).prop('disabled',false);
     nivel.disabled = false
     cancelarActNivel.disabled = false 
  }).catch(error => {
    avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
    $("#seccion_nivel").html('<option value="0">Error</option>').prop('disabled',false);
    nivel.disabled = false
    cancelarActNivel.disabled = false  
    query("#nombre_nivel").focus()
  });
}

function actualizarNivel(e){  
  e.preventDefault();
  var datos = new FormData(query("#FrmActualizarNivel"));
  var nivelActualizar = {}; 
  const data = { 
    formulario: query("#FrmActualizarNivel") 
  }      
  nivelActualizar = validarDatosNivel(data) 
  if(nivelActualizar["acceso"] === true){
    
    datos.get("estado_nivel")===null? datos.append("estado_nivel",null):''
    datos.append("codigo",idNivel) 
    datos.append("accion",accionNivel)     
     
    var nivel = query(".actualizar_nivel")
    var cancelarActNivel = query(".boton_cancelar_actualizar_nivel")
    var cerrarNivel = query(".FrmActualizarNivel")
    
    var titulo = nivel.textContent; 
    nivel.textContent = nivel.textContent.replace("AR","ANDO...") 
    nivel.disabled = true;
    cancelarActNivel.disabled = true 
    
    fetch('actualizar_niveles',{  
      method: 'POST',
      body: datos}) 
    .then(response => response.json())
    .then(data => {
      if(data.estado){ 
        avisoFormulario({aviso: "actualizarNivel",mensaje: data.observacion,alerta:""})  
        $("#frm_actualizar_niveles").modal("hide") 
        tabla.ajax.reload();
      }else{
        avisoFormulario({aviso: "actualizarNivel", mensaje: data.observacion,alerta:"text-warning"})
      }
       nivel.disabled = false
       cancelarActNivel.disabled = false 
       nivel.textContent = titulo  
    }).catch(error => { 
      avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
      nivel.textContent = titulo
      nivel.disabled = false 
      query("#nombre_nivel").focus()
      cancelarActNivel.disabled = false
    });
  } 
}

(function(){
  var datos = new FormData();
  datos.append('id','');
  $("#archivo_seccion_niveles").html("<option value='0' selected> Cargando datos.</option>").prop('disabled',true);
  fetch('cargar_archivos',{  
    method: 'POST',
    body: datos}) 
  .then(response => response.json())
  .then(data => {  
    $("#archivo_seccion_niveles").html(data).prop('disabled',false);
  }).catch(error => {
    avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
    $("#archivo_seccion_niveles").html('<option value="0">Error</option>').prop('disabled',false);
  });
})()


function validarDatosNivel(inf){
  var datos = inf || {}
  var acceso = true;
  var excepto = [""]
  
  if(camposVaciosFormularios({formulario:datos["formulario"],excepto:excepto}))acceso = false
    
  return {
    acceso: acceso
  }    
}

function imprimirTablaNiveles(vacio){    
 return TablasDatos({   
  tabla  : 'tabla_niveles',
  url    : 'mostrar_niveles', 
  data   : { 
            id : vacio  
          },   
  columnas :  [
                { data: 'num', sClass: "centro"},  
                { data: 'nombre'},
                { data: 'archivo',sClass: "color-celda"}, 
                { data: 'seccion',sClass: "color-celda"}, 
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
 



