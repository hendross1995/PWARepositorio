import { erroresFormulario,camposVaciosFormularios,
        query, exitoFormulario,TablasDatos,removerClassErrorForm,avisoFormulario} from './funcionesGlobales.js'; 

window.addEventListener('load',iniciarEventos,true)

var tabla; 
var accionMDocumentales = null;
var idMDocumentales = null
 
function iniciarEventos(){ 

   var boton_modificar_toponimia = query(".tabla_toponimia")
   var boton_registrar_toponimia = query(".boton_registrar_toponimia")  
   var actualizar_toponimia = query("#actualizar_toponimia")  
   var errorInput = query("#FrmActualizarToponimia") 

   actualizar_toponimia.addEventListener('click',actualizarMDocumentales)
   boton_modificar_toponimia.addEventListener('click',btnModificarMDocumentales)
   boton_registrar_toponimia.addEventListener('click',btnRegistrarMDocumentales)
   errorInput.addEventListener('click',quitarError)
  
   tabla = imprimirTablaMDocumentales("") 
} 

function btnRegistrarMDocumentales(e){
    
    accionMDocumentales = "registrar"
    query(".titulo_toponimia").textContent = "Registrar toponimia"
    query(".actualizar_toponimia").textContent = "REGISTRAR"
    query("#FrmActualizarToponimia").reset();
    query(".estado_toponimia").checked  = true
    query("#nombre_toponimia").focus() 
    removerClassErrorForm({formulario:query("#FrmActualizarToponimia")})
} 

function btnModificarMDocumentales(e){
    
    var clase = e.target.classList;
    var editar = e.target;
    if (clase.contains("fa-edit")) editar = e.target.parentElement
    
    if(clase.contains("boton_modificar_toponimia") || clase.contains("fa-edit")){
        
       idMDocumentales = editar.id  
       accionMDocumentales = "modificar" 
        
       var celdas =  editar.parentElement.parentElement.cells;    
       var check = celdas[2].textContent == "ACTIVO" ? true : false; 
        
       query(".titulo_toponimia").textContent = "Modificar toponimia";
       query(".actualizar_toponimia").textContent = "MODIFICAR" 
       query("#nombre_toponimia").value = celdas[1].textContent; 
       query(".estado_toponimia").checked = check;
       removerClassErrorForm({formulario:query("#FrmActualizarToponimia")})
         
    } 
}

function actualizarMDocumentales(e){  
    
    e.preventDefault();
     
    var datos = new FormData(query("#FrmActualizarToponimia"));
    var coleccionMDocumentales = {}; 
  
   const data = { 
     formulario: query("#FrmActualizarToponimia") 
   }      
   
   coleccionMDocumentales = validarDatosMDocumentales(data) 

   if(coleccionMDocumentales["acceso"] === true){

      datos.get("estado_toponimia")===null? datos.append("estado_toponimia",null):''
      datos.append("codigo",idMDocumentales) 
      datos.append("accion",accionMDocumentales)     
       
      var toponimia = query(".actualizar_toponimia")
      var cancelarActToponimia = query(".boton_cancelar_actualizar_toponimia")
      
      var titulo = toponimia.textContent; 
      toponimia.textContent = toponimia.textContent.replace("AR","ANDO...") 
      toponimia.disabled = true;
      cancelarActToponimia.disabled = true 
      
      fetch('actualizar_toponimia',{  
        method: 'POST',
        body: datos}) 
      .then(response => response.json())
      .then(data => {  
        if(data.estado){ 
          avisoFormulario({aviso: "actualizarToponimia",mensaje: data.observacion,alerta:""})  
          $("#frm_actualizar_toponimia").modal("hide") 
          tabla.ajax.reload();
        }else{
          avisoFormulario({aviso: "actualizarToponimia", mensaje: data.observacion,alerta:"text-warning"})
        }
         toponimia.disabled = false
         cancelarActToponimia.disabled = false 
         toponimia.textContent = titulo  
       })
      .catch(error => { 
        avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
        toponimia.textContent = titulo
        toponimia.disabled = false 
        query("#nombre_toponimia").focus()
        cancelarActToponimia.disabled = false
      });
   } 
    
}

function validarDatosMDocumentales(inf){

  var datos = inf || {}
  var acceso = true;
  var excepto = [""]  
  
   if(camposVaciosFormularios({formulario:datos["formulario"],excepto:excepto}))acceso = false
    
  return {
      acceso: acceso
  }    
    
}

function imprimirTablaMDocumentales(vacio){    
    
 return TablasDatos({   
  tabla  : 'tabla_toponimia',
  url    : 'mostrar_toponimia',
  data   : { 
            id : vacio  
          },   
  columnas :  [
                { data: 'num', sClass: "centro"},  
                { data: 'nombre'},
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
