import { erroresFormulario,camposVaciosFormularios,
        query, exitoFormulario,TablasDatos,removerClassErrorForm,avisoFormulario} from './funcionesGlobales.js'; 

window.addEventListener('load',iniciarEventos,true)

var tabla; 
var accionArchivo = null; 
var idArchivo = null
 
function iniciarEventos(){ 

   var b_modificar_archivos= query(".tabla_archivos")
   var b_registrar_archivos= query(".boton_registrar_archivo")  
   var actualizar_archivos= query("#actualizar_archivo")   
   var errorInput = query("#FrmActualizarArchivo")

   actualizar_archivos.addEventListener('click',actualizarArchivos)
   b_modificar_archivos.addEventListener('click',btnModificarArchivos)
   b_registrar_archivos.addEventListener('click',btnRegistrarArchivos) 
   errorInput.addEventListener('click',quitarError)
  
   tabla = imprimirTablaArchivos("") 
} 

function btnRegistrarArchivos(e){ 
    
    accionArchivo = "registrar"
    query(".titulo_archivo").textContent = "Registrar archivo"
    query(".actualizar_archivo").textContent = "REGISTRAR"
    query("#FrmActualizarArchivo").reset();
    query(".estado_archivo").checked  = true
    query("#nombre_archivo").focus()  
    removerClassErrorForm({formulario:query("#FrmActualizarArchivo")})
} 

function btnModificarArchivos(e){
    
    var clase = e.target.classList;
    var editar = e.target;
    if (clase.contains("fa-edit")) editar = e.target.parentElement
    
    if(clase.contains("boton_modificar_archivo") || clase.contains("fa-edit")){

       idArchivo = editar.id  
       accionArchivo = "modificar" 
        
       var celdas =  editar.parentElement.parentElement.cells;    
       var check = celdas[2].textContent == "ACTIVO" ? true : false; 
        
       query(".titulo_archivo").textContent = "Modificar archivo"; 
       query(".actualizar_archivo").textContent = "MODIFICAR" 
       query("#nombre_archivo").value = celdas[1].textContent; 
       query(".estado_archivo").checked = check;
       removerClassErrorForm({formulario:query("#FrmActualizarArchivo")})
         
    } 
}

function actualizarArchivos(e){  
    
    e.preventDefault();
     
    var datos = new FormData(query("#FrmActualizarArchivo"));
    var mArchivo = {}; 
  
   const data = { 
     formulario: query("#FrmActualizarArchivo") 
   }      
   
   mArchivo = validarDatosArchivos(data) 

   if(mArchivo["acceso"] === true){

      datos.get("estado_archivo")===null? datos.append("estado_archivo",null):''
      datos.append("codigo",idArchivo) 
      datos.append("accion",accionArchivo)     

       
      var archivoAct = query(".actualizar_archivo")
      var cancelarBotonArchivo = query(".boton_cancelar_actualizar_archivo") 
      
      var titulo = archivoAct.textContent; 
      archivoAct.textContent = archivoAct.textContent.replace("AR","ANDO...") 
      archivoAct.disabled = true; 
      cancelarBotonArchivo.disabled = true 
      
      fetch('actualizar_archivos',{   
        method: 'POST',
        body: datos}) 
      .then(response => response.json())
      .then(data => {  
        if(data.estado){ 
          avisoFormulario({aviso: "actualizarArchivo",mensaje: data.observacion,alerta:""})  
          $("#frm_actualizar_archivos").modal("hide") 
          tabla.ajax.reload();
        }else{
          avisoFormulario({aviso: "actualizarArchivo", mensaje: data.observacion,alerta:"text-warning"})
        }
         archivoAct.disabled = false
         cancelarBotonArchivo.disabled = false 
         archivoAct.textContent = titulo  
       }) 
      .catch(error => { 
        avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
        archivoAct.textContent = titulo
        archivoAct.disabled = false 
        query("#nombre_archivo").focus()
        cancelarBotonArchivo.disabled = false
      });
   } 
    
}

function validarDatosArchivos(inf){

  var datos = inf || {}
  var acceso = true;
  var excepto = [""] 
   
   if(camposVaciosFormularios({formulario:datos["formulario"],excepto:excepto}))acceso = false
    
  return {
      acceso: acceso
  }    
     
}

function imprimirTablaArchivos(vacio){    
    
 return TablasDatos({   
  tabla  : 'tabla_archivos',
  url    : 'mostrar_archivos',
  data   : { 
            id : vacio   
          },   
  columnas :  [
                { data: 'num', sClass: "centro"},  
                { data: 'nombre'},
                { data: 'estado', sClass: "centro"}, 
                { data: 'accion', sClass: "centro"}   
              ],
  orientacion: '', //landscape
  columnasexp: [0,2,3,4,5],
  anchuracol : ['5%', '30%', '20%', '35%', '10%'],
  }); 
} 

function quitarError(e){
    if(e.target.classList.contains("errorForm")){
        e.target.classList.remove("errorForm") 
    }
}
