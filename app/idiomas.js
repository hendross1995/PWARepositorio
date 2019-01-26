import { erroresFormulario,camposVaciosFormularios,
        query, exitoFormulario,TablasDatos,removerClassErrorForm,avisoFormulario} from './funcionesGlobales.js'; 

window.addEventListener('load',iniciarEventos,true)

var tabla; 
var accionMDocumentales = null;
var idMDocumentales = null
 
function iniciarEventos(){ 

   var boton_modificar_idiomas = query(".tabla_idiomas")
   var boton_registrar_idiomas = query(".boton_registrar_idioma")  
   var actualizar_idiomas = query("#actualizar_idioma")  
   var errorInput = query("#FrmActualizarIdioma") 

   actualizar_idiomas.addEventListener('click',actualizarMDocumentales)
   boton_modificar_idiomas.addEventListener('click',btnModificarMDocumentales)
   boton_registrar_idiomas.addEventListener('click',btnRegistrarMDocumentales)
   errorInput.addEventListener('click',quitarError)
  
   tabla = imprimirTablaMDocumentales("") 
} 

function btnRegistrarMDocumentales(e){
    
    accionMDocumentales = "registrar"
    query(".titulo_idioma").textContent = "Registrar idioma"
    query(".actualizar_idioma").textContent = "REGISTRAR"
    query("#FrmActualizarIdioma").reset();
    query(".estado_idioma").checked  = true
    query("#nombre_idioma").focus() 
    removerClassErrorForm({formulario:query("#FrmActualizarIdioma")})
} 

function btnModificarMDocumentales(e){
    
    var clase = e.target.classList;
    var editar = e.target;
    if (clase.contains("fa-edit")) editar = e.target.parentElement
    
    if(clase.contains("boton_modificar_idioma") || clase.contains("fa-edit")){
        
       idMDocumentales = editar.id  
       accionMDocumentales = "modificar" 
        
       var celdas =  editar.parentElement.parentElement.cells;    
       var check = celdas[2].textContent == "ACTIVO" ? true : false; 
        
       query(".titulo_idioma").textContent = "Modificar idioma";
       query(".actualizar_idioma").textContent = "MODIFICAR" 
       query("#nombre_idioma").value = celdas[1].textContent; 
       query(".estado_idioma").checked = check;
       removerClassErrorForm({formulario:query("#FrmActualizarIdioma")})
         
    } 
}

function actualizarMDocumentales(e){  
    
    e.preventDefault();
     
    var datos = new FormData(query("#FrmActualizarIdioma"));
    var coleccionMDocumentales = {}; 
  
   const data = { 
     formulario: query("#FrmActualizarIdioma") 
   }      
   
   coleccionMDocumentales = validarDatosMDocumentales(data) 

   if(coleccionMDocumentales["acceso"] === true){

      datos.get("estado_idioma")===null? datos.append("estado_idioma",null):''
      datos.append("codigo",idMDocumentales) 
      datos.append("accion",accionMDocumentales)     
       
      var idioma = query(".actualizar_idioma")
      var cancelarActIdioma = query(".boton_cancelar_actualizar_idioma")
      
      var titulo = idioma.textContent; 
      idioma.textContent = idioma.textContent.replace("AR","ANDO...") 
      idioma.disabled = true;
      cancelarActIdioma.disabled = true 
      
      fetch('actualizar_idiomas',{  
        method: 'POST',
        body: datos}) 
      .then(response => response.json())
      .then(data => {  
        if(data.estado){ 
          avisoFormulario({aviso: "actualizarIdioma",mensaje: data.observacion,alerta:""})  
          $("#frm_actualizar_idiomas").modal("hide") 
          tabla.ajax.reload();
        }else{
          avisoFormulario({aviso: "actualizarIdioma", mensaje: data.observacion,alerta:"text-warning"})
        }
         idioma.disabled = false
         cancelarActIdioma.disabled = false 
         idioma.textContent = titulo  
       })
      .catch(error => { 
        avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
        idioma.textContent = titulo
        idioma.disabled = false 
        query("#nombre_idioma").focus()
        cancelarActIdioma.disabled = false
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
  tabla  : 'tabla_idiomas',
  url    : 'mostrar_idiomas',
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
