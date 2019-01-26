import { erroresFormulario,camposVaciosFormularios, query, exitoFormulario} from './funcionesGlobales.js'; 

window.addEventListener('load',iniciarEventos,true)

function iniciarEventos(){
    
   var actualizarContrasena = query("#FrmActualizarContrasena")  
   var errorInput = query("#FrmActualizarContrasena")  
  
   actualizarContrasena.addEventListener('submit',contrasenaNueva)
   errorInput.addEventListener('click',quitarError)
} 
 
   
function contrasenaNueva(e){
   
    
   e.preventDefault();
    
   var datos = new FormData(e.target);
   var nuevaSesion = {};
    
   const data = {
       contrasena: datos.get("nueva_contrasena"),
       contrasena2: datos.get("repetir_contrasena"),
       formulario: e.target
   }    
     
   nuevaSesion = validarDatosDeNuevaContrasena(data);

   if(nuevaSesion["acceso"] === true){ 
      var passAct = query(".actualizar_contrasena");   
      passAct.value = "ACTUALIZANDO..."
      passAct.disabled = true;
      fetch('actualizar_contrasena',{
        method: 'POST',
        body: datos}) 
      .then(response => response.json())
      .then(data => {
        if(data.estado){ 
          exitoFormulario({exito: "contrasenaActualizada", formulario: "#FrmActualizarContrasena",mensaje: data.observacion}) 
          setTimeout(function(){location.reload()},5000)  
        }else{
          erroresFormulario({error: "contrasenaNoActualizada", formulario: "#FrmActualizarContrasena", mensaje: data.observacion})  
          passAct.disabled = false 
        }
           passAct.value = "ACTUALIZAR"
       })
      .catch(error => {
        erroresFormulario({error: "errorServidor", formulario: "#FrmActualizarContrasena"})
        passAct.value = "ACCEDER"
        passAct.disabled = false
      });
   }else erroresFormulario({error: nuevaSesion["error"],formulario: "#FrmActualizarContrasena"})
    
}

 
function validarDatosDeNuevaContrasena(inf){
  var datos = inf || {}
  var acceso = true, error = "ninguno";
  
   if(datos["contrasena"] !== datos["contrasena2"]){
        acceso = false
        error = "actualizarContrasenaIncorrecto"
    } 
    
   if(camposVaciosFormularios({formulario:datos["formulario"],excepto:[]})){
       acceso = false
       error = "vacio" 
    }
      
  return {
      error: error,
      acceso: acceso
  }    
}

function quitarError(e){
    if(e.target.classList.contains("errorForm")){
        e.target.classList.remove("errorForm") 
    }
}


