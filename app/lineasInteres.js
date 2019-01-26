import { erroresFormulario,camposVaciosFormularios,
        query, exitoFormulario,TablasDatos,removerClassErrorForm,avisoFormulario} from './funcionesGlobales.js'; 

window.addEventListener('load',iniciarEventos,true)

var tabla; 
var accionMDocumentales = null;
var idMDocumentales = null
 
function iniciarEventos(){ 

   var boton_modificar_lineainteres = query(".tabla_lineasinteres")
   var boton_registrar_lineasinteres = query(".boton_registrar_lineainteres")  
   var actualizar_lineasinteres = query("#actualizar_lineainteres")  
   var errorInput = query("#FrmActualizarLineaInteres") 

   actualizar_lineasinteres.addEventListener('click',actualizarMDocumentales)
   boton_modificar_lineainteres.addEventListener('click',btnModificarMDocumentales)
   boton_registrar_lineasinteres.addEventListener('click',btnRegistrarMDocumentales)
   errorInput.addEventListener('click',quitarError)
  
   tabla = imprimirTablaMDocumentales("") 
} 

function btnRegistrarMDocumentales(e){
    
    accionMDocumentales = "registrar"
    query(".titulo_lineainteres").textContent = "Registrar línea de interés"
    query(".actualizar_lineainteres").textContent = "REGISTRAR"
    query("#FrmActualizarLineaInteres").reset();
    query(".estado_lineainteres").checked  = true
    query("#nombre_lineainteres").focus() 
    removerClassErrorForm({formulario:query("#FrmActualizarLineaInteres")})
} 

function btnModificarMDocumentales(e){
    
    var clase = e.target.classList;
    var editar = e.target;
    if (clase.contains("fa-edit")) editar = e.target.parentElement
    
    if(clase.contains("boton_modificar_lineainteres") || clase.contains("fa-edit")){
        
       idMDocumentales = editar.id  
       accionMDocumentales = "modificar" 
        
       var celdas =  editar.parentElement.parentElement.cells;    
       var check = celdas[2].textContent == "ACTIVO" ? true : false; 
        
       query(".titulo_lineainteres").textContent = "Modificar línea de interés";
       query(".actualizar_lineainteres").textContent = "MODIFICAR" 
       query("#nombre_lineainteres").value = celdas[1].textContent; 
       query(".estado_lineainteres").checked = check;
       removerClassErrorForm({formulario:query("#FrmActualizarLineaInteres")})
         
    } 
}

function actualizarMDocumentales(e){  
    
    e.preventDefault();
     
    var datos = new FormData(query("#FrmActualizarLineaInteres"));
    var coleccionMDocumentales = {}; 
  
   const data = { 
     formulario: query("#FrmActualizarLineaInteres") 
   }      
   
   coleccionMDocumentales = validarDatosMDocumentales(data) 

   if(coleccionMDocumentales["acceso"] === true){

      datos.get("estado_lineainteres")===null? datos.append("estado_lineainteres",null):''
      datos.append("codigo",idMDocumentales) 
      datos.append("accion",accionMDocumentales)     
       
      var lineainteres = query(".actualizar_lineainteres")
      var cancelarActLineaInteres = query(".boton_cancelar_actualizar_lineainteres")
      
      var titulo = lineainteres.textContent; 
      lineainteres.textContent = lineainteres.textContent.replace("AR","ANDO...") 
      lineainteres.disabled = true;
      cancelarActLineaInteres.disabled = true 
      
      fetch('actualizar_lineasinteres',{  
        method: 'POST',
        body: datos}) 
      .then(response => response.json())
      .then(data => {  
        if(data.estado){ 
          avisoFormulario({aviso: "actualizarLineaInteres",mensaje: data.observacion,alerta:""})  
          $("#frm_actualizar_lineasinteres").modal("hide") 
          tabla.ajax.reload();
        }else{
          avisoFormulario({aviso: "actualizarLineaInteres", mensaje: data.observacion,alerta:"text-warning"})
        }
         lineainteres.disabled = false
         cancelarActLineaInteres.disabled = false 
         lineainteres.textContent = titulo  
       })
      .catch(error => { 
        avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
        lineainteres.textContent = titulo
        lineainteres.disabled = false 
        query("#nombre_lineainteres").focus()
        cancelarActLineaInteres.disabled = false
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
  tabla  : 'tabla_lineasinteres',
  url    : 'mostrar_lineasinteres',
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
