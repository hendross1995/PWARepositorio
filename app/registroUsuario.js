import { camposVaciosFormularios,query,erroresFormulario,exitoFormulario,removerClassErrorForm } from './funcionesGlobales.js'; 

window.addEventListener('load',iniciarEventos,true)

var _correoRepetido= false;

function iniciarEventos(){ 
   var registrar = query("#FrmRegistrarUsuario")  
   var nuevo_registro = query(".registrar_usuario")
   var usuario = query("#nuevo_correo");
   var cancelar_registro = query(".boton_cancelar_registro");
   var errorInput = query("#FrmRegistrarUsuario") 
       
   registrar.addEventListener('submit',registrarUsuarios)
   nuevo_registro.addEventListener('click',mostrarFormRegistrar) 
   cancelar_registro.addEventListener('click',cancelarRegistro)
   errorInput.addEventListener('click',quitarError)
}


function registrarUsuarios(e){
 
   e.preventDefault();
    
   var datos = new FormData(e.target);
   var registro = {};
    
   const data = {
       usuario: datos.get("nuevo_correo"),
       celular: datos.get("celular"),
       contrasena: datos.get("nueva_contrasena"),
       contrasena2: datos.get("repetir_contrasena"),
       formulario: e.target
   }    
    
   registro = validarDatosDeRegistro(data);
    
   if(registro["acceso"] === true){
       var registro = query("#registrarme");
       var cancelar = query(".boton_cancelar_registro");
       
       registro.value = "REGISTRÁNDOME..."
       registro.disabled = true; 
       cancelar.disabled = true; 
       
       fetch('registrar_usuario',{  
           method: 'Post',   
           body: datos 
       }).then(response => response.json()) 
         .then(data => {
           if(data.estado){
              exitoFormulario({exito: "registroCorrecto", formulario: "#FrmRegistrarUsuario"})
              setTimeout(function(){location.reload()},5000)    
           } else{
              erroresFormulario({error: "registroIncorrecto", formulario: "#FrmRegistrarUsuario",mensaje:data.observacion})
              registro.disabled = false; 
              cancelar.disabled = false; 
           } 
           query("#registrarme").value = "REGISTRARME"
           
       }).catch(error => { 
            erroresFormulario({error: "errorServidor", formulario: "#FrmRegistrarUsuario"})
            registro.value = "REGISTRARME"
            registro.disabled = false; 
            cancelar.disabled = false;
       }) 
   }else erroresFormulario({error: registro["error"],formulario: "#FrmRegistrarUsuario"})
    
} 


function validarDatosDeRegistro(inf){
 
    var datos = inf || {}
    var acceso = true, error = "ninguno";
    var Expresion =/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/
    var Expresion2 = /^([0-9]{15})*$/
    var excepto = ["organizacion","celular"]

    if(_correoRepetido){
        acceso = false 
        error = "correoRepetido" 
    } 
    
    if(!Expresion.test(datos["usuario"])){
        acceso = false 
        error = "nuevo_correo"
    } 
    
    /*if(!Expresion2.test(datos["celular"])){
        acceso = false 
        error = "celular"
    } */ 
    
    if(datos["contrasena"] !== datos["contrasena2"]){
        acceso = false
        error = "contrasena"
    } 
                                
     if(camposVaciosFormularios({formulario:datos["formulario"],excepto:excepto})){
       acceso = false
       error = "vacio"
    }
    
    return {
      error: error,
      acceso: acceso
  }    
  
}


function mostrarFormRegistrar(e){
    query("#FrmIniciarSesion").reset();
    query(".boton_cancelar_registro").style.display = "block"
    query(".registrar_usuario_caja").style.display = "block";
    query(".iniciar_sesion_caja").style.display = "none"; 
    removerClassErrorForm({formulario:query("#FrmIniciarSesion")})
} 

function cancelarRegistro(e){
    query("#FrmRegistrarUsuario").reset()
    query(".registrar_usuario_caja").style.display = "none"
    query(".iniciar_sesion_caja").style.display = "block"
    query(".boton_iniciar_sesion").style.display = "none"
    query("#correo").focus() 
    removerClassErrorForm({formulario:query("#FrmRegistrarUsuario")})
} 

function quitarError(e){
    if(e.target.classList.contains("errorForm")){
        e.target.classList.remove("errorForm") 
    }
}
