import { erroresFormulario,camposVaciosFormularios, query, exitoFormulario,removerClassErrorForm} from './funcionesGlobales.js'; 

window.addEventListener('load',iniciarEventos,true)

function iniciarEventos(){
   var ingresar = query("#FrmIniciarSesion")  
   var restablecerContrasena = query("#FrmRestablecerContrasena") 
   var inciar = query(".boton_iniciar_sesion") 
   var olvido_contrasena = query(".olvido_contrasena")
   var cancelar_olvido_contrasena = query(".boton_cancelar_olvidar_contrasena");
   var errorInput = query("#FrmIniciarSesion") 
   var errorInput2 = query("#FrmRestablecerContrasena") 
   
   ingresar.addEventListener('submit',iniciarSesion)
   inciar.addEventListener('click',formularioIniciarSesion)
   olvido_contrasena.addEventListener('click',mostrarFormOlvidoContrasena)
   cancelar_olvido_contrasena.addEventListener('click',mostrarFormIniciarSesion)
   restablecerContrasena.addEventListener('submit',restablecerSuContrasena)
   errorInput.addEventListener('click',quitarError)
   errorInput2.addEventListener('click',quitarError) 
    
} 
 
   
function iniciarSesion(e){

   e.preventDefault();
    
   var datos = new FormData(e.target);
   var sesion = {};
    
   const data = {
       usuario: datos.get("correo").toLowerCase(),
       contrasena: datos.get("contrasena"),
       formulario: e.target
   }    
     
   sesion = validarDatosDeSesion(data);

   if(sesion["acceso"] === true){
      var login = query("#acceder_login");   
      login.value = "ACCEDIENDO..."
      login.disabled = true;
      fetch('iniciar_sesion',{
        method: 'POST',
        body: datos})
      .then(response => response.json())
      .then(data => {
        if(data.estado){ 
          exitoFormulario({exito: "loginCorrecto", formulario: "#FrmIniciarSesion"}) 
          location.reload() 
        }else{
          erroresFormulario({error: "loginIncorrecto", formulario: "#FrmIniciarSesion", mensaje: data.observacion})  
          login.disabled = false 
        }
         login.value = "ACCEDER"
       })
      .catch(error => {
        erroresFormulario({error: "errorServidor", formulario: "#FrmIniciarSesion"})
        login.value = "ACCEDER"
        login.disabled = false
      });
   }else erroresFormulario({error: sesion["error"],formulario: "#FrmIniciarSesion"})
    
}

function restablecerSuContrasena(e){
   
    e.preventDefault();
    
    var datos = new FormData(e.target)
    
    const data = {
        correo: datos.get("correo_restablecer"),
        formulario: e.target
    }
    
    var restablecer = validarDatosRestablecerContrasena(data);

    if(restablecer["acceso"] === true){
       
      var contrasena = query("#restablecer");
      var cancelaRestablecer = query(".boton_cancelar_olvidar_contrasena");
       
      contrasena.value = "RESTABLECIENDO..."
      cancelaRestablecer.disabled = true;
      contrasena.disabled = true;
       
      fetch('restablecer_contrasena',{ 
        method: 'Post',
        body: datos}) 
      .then(response => response.json())
      .then(data => {
        if(data.estado){ 
          exitoFormulario({exito: "restablerCorrecto", formulario: "#FrmRestablecerContrasena",mensaje:data.observacion})
          setTimeout(function(){location.reload()},5000)    
        } else{ 
          erroresFormulario({error: "restablerIncorrecto", formulario: "#FrmRestablecerContrasena",mensaje: data.observacion}) 
          cancelaRestablecer.disabled = false;
          contrasena.disabled = false;
        }
          contrasena.value = "RESTABLECER"
       })
      .catch(error => {
        erroresFormulario({error: "errorServidor", formulario: "#FrmRestablecerContrasena"})
        contrasena.value = "RESTABLECER"
        cancelaRestablecer.disabled = false;
        contrasena.disabled = false; 
      });
   
   }else erroresFormulario({error: restablecer["error"],formulario: "#FrmRestablecerContrasena"})
    
   
}

function validarDatosRestablecerContrasena(inf){
  var datos = inf || {}
  var acceso = true, error = "ninguno";
  var Expresion =/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/
  
   if(!Expresion.test(datos["correo"])){
       acceso = false 
       error = "correoRestablecerFalso" 
   } 
    
   if(camposVaciosFormularios({formulario:datos["formulario"],excepto:[]})){
       acceso = false
       error = "vacioCampo"
    }
      
  return {
      error: error,
      acceso: acceso
  }    
}

function validarDatosDeSesion(inf){

  var datos = inf || {}
  var acceso = true, error = "ninguno";
  var Expresion =/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/
  

   if(!Expresion.test(datos["usuario"])){
       acceso = false 
       error = "correo"
   } 
    
   if(camposVaciosFormularios({formulario:datos["formulario"],excepto:[]})){
       acceso = false
       error = "vacioCampo"
    }
    
  return {
      error: error,
      acceso: acceso
  }    
    
}

function mostrarFormOlvidoContrasena(e){
    query("#FrmIniciarSesion").reset();
    query(".iniciar_sesion_caja").style.display = "none"
    query(".contrasena_olvidar_caja").style.display = "block"
    query("#correo_restablecer").focus()
    removerClassErrorForm({formulario:query("#FrmIniciarSesion")})
}

function mostrarFormIniciarSesion(e){
    query("#FrmRestablecerContrasena").reset()
    query(".contrasena_olvidar_caja").style.display = "none"
    query(".iniciar_sesion_caja").style.display = "block"
    query("#correo").focus()  
    removerClassErrorForm({formulario:query("#FrmRestablecerContrasena")})
}

function formularioIniciarSesion(e){
    query("#FrmRegistrarUsuario").reset()
    query(".registrar_usuario_caja").style.display = "none"
    query(".iniciar_sesion_caja").style.display = "block"
    query("#correo").focus() 
} 

function quitarError(e){
    if(e.target.classList.contains("errorForm")){
        e.target.classList.remove("errorForm") 
    }
}

