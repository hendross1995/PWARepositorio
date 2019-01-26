
// MENSAJES DE ERRORES DE FORMULARIO

function erroresFormulario(inf){
    
    var datos = inf || {}
    var tiempo = 2000 
    var mensaje = query(`${datos["formulario"]} .notificacion-ingreso`)
    
    mensaje.classList.add("errorMensaje");  

    
    if(query(".boton_iniciar_sesion")){
        query(".boton_iniciar_sesion").style.display = "none";
        query(".boton_cancelar_registro").style.display = "block"   
    }
    
     
    switch(datos["error"]){
       
         case "vacioCampo":  
            mensaje.innerHTML = "<b>Rellena los campos del formulario</b>" 
            break  
         case "contrasenaNoActualizada":  
            mensaje.innerHTML = "<b>"+datos["mensaje"]+"</b>" 
            query("#contrasena_actual").focus()
            break 
         case "actualizarContrasenaIncorrecto":  
            mensaje.innerHTML = "<b>Las contraseñas deben ser iguales</b>"
            query("#nueva_contrasena").focus()
            add("#repetir_contrasena","errorForm")
            break 
        case "correoRestablecerFalso":  
            mensaje.innerHTML = "<b>Escriba un correo correcto.</b>"
            query("#correo_restablecer").focus()
            break 
        case "restablerIncorrecto":  
            mensaje.innerHTML = "<b>"+datos["mensaje"]+"</b>" 
            query("#correo_restablecer").focus()
            break 
        case "correoRepetido":  
            mensaje.innerHTML = "<b>El correo ya existe.</b>"
            query("#nuevo_correo").focus()
            break 
        case "registroIncorrecto":   
            mensaje.innerHTML = datos['mensaje']
            break 
        case "loginIncorrecto":
            mensaje.innerHTML = datos['mensaje']
            query("#correo").focus()
            break 
        case "vacio":  
            mensaje.innerHTML = "<b>Todos los campos con (*) son obligatorios</b>"
            break
        case "correo":
            mensaje.innerHTML = "<b>Correo incorrecto</b>"
            query("#correo").focus() 
            break
        case "nuevo_correo":
            mensaje.innerHTML = "<b>Correo incorrecto.</b>"
            query("#nuevo_correo").focus()
            break
        case "celular":
            mensaje.innerHTML = "<b>Número incorrecto</b>" 
            query("#celular").focus()
            break
        case "contrasena":
            mensaje.innerHTML = "<b>Las contraseñas deben ser iguales</b>" 
            query("#repetir_contrasena").focus()
            break
        case "errorServidor":
            mensaje.innerHTML = "<b>No se pudo conectar con el servidor. Verifique su conexión a internet y vuelva a realizar la petición.</b>" 
            break
        default:
            mensaje.innerHTML = "<b>Lo sentimos :C</b>";
    }
      
    setTimeout(function() {  
         mensaje.innerHTML = ""
         mensaje.classList.remove("errorMensaje")
    },tiempo); 
     
}

// MENSAJES DE EXITOS DE FORMULARIO

function exitoFormulario(inf){
    
    var datos = inf || {}
    var mensaje = query(`${datos["formulario"]} .notificacion-ingreso`)
    var tiempo = 2000
    
    mensaje.classList.add("correctoMensaje");
    
    switch(datos["exito"]){
    
         case "restablerCorrecto":  
            mensaje.innerHTML = "<b>"+datos["mensaje"]+"</b>"
            break   
        case "loginCorrecto":  
            mensaje.innerHTML = "<b>Ingreso correcto. Redireccionando...</b>"
            break  
        case "registroCorrecto":  
            mensaje.innerHTML = "<b>Registro exitoso. Ingresando...</b>"
            query(".boton_cancelar_registro").style.display = "none"
            break 
        case "contrasenaActualizada":  
            mensaje.innerHTML = "<b>"+datos["mensaje"]+"</b>"
            break 
        default:
            mensaje.innerHTML = "<b>Algo no anda bien</b>"
            break 
      }
     
    setTimeout(function() {  
         mensaje.innerHTML = ""
         mensaje.classList.remove("correctoMensaje")
    },tiempo); 
    
}

// AVISOS GENERALES PARA FORMULARIOS

function avisoFormulario(inf){
     var datos =  inf || {}
     
     switch(datos["aviso"]){
          case "actualizarFondo": 
            $.notify(`<span class=${datos["alerta"]}>${datos["mensaje"]}</span>`);
            query("#nombre_fondo").focus() 
            break 
           case "actualizarColeccion": 
            $.notify(`<span class=${datos["alerta"]}>${datos["mensaje"]}</span>`);
            query("#nombre_coleccion").focus() 
            break
           case "actualizarMaterialD": 
            $.notify(`<span class=${datos["alerta"]}>${datos["mensaje"]}</span>`);
            query("#nombre_material_documental").focus() 
            break
           case "actualizarMaterialS": 
            $.notify(`<span class=${datos["alerta"]}>${datos["mensaje"]}</span>`);
            query("#nombre_material_soporte").focus() 
            break
           case "actualizarArchivo":  
            $.notify(`<span class=${datos["alerta"]}>${datos["mensaje"]}</span>`);
            query("#nombre_archivo").focus() 
            break
          case "actualizarSeccion":  
            $.notify(`<span class=${datos["alerta"]}>${datos["mensaje"]}</span>`);
            query("#nombre_seccion").focus() 
            break
          case "actualizarNivel":  
            $.notify(`<span class=${datos["alerta"]}>${datos["mensaje"]}</span>`);
            query("#nombre_nivel").focus() 
            break
          case "actualizarContenedor":  
            $.notify(`<span class=${datos["alerta"]}>${datos["mensaje"]}</span>`);
            query("#nombre_contenedor").focus() 
            break
          case "actualizarIdioma":  
            $.notify(`<span class=${datos["alerta"]}>${datos["mensaje"]}</span>`);
            query("#nombre_idioma").focus() 
            break 
          case "actualizarPersonajeGenerador":  
            $.notify(`<span class=${datos["alerta"]}>${datos["mensaje"]}</span>`);
            query("#cedula_personajegenerador").focus() 
            break 
          case "actualizarUsuario":  
            $.notify(`<span class=${datos["alerta"]}>${datos["mensaje"]}</span>`);
            query("#cedula_usuario").focus() 
            break
          case "fotoIncorrecta":   
            $.notify(`<span class=${datos["alerta"]}>${datos["mensaje"]}</span>`);
             query(`${datos["clase"]} .mostrar_foto`).classList.add("errorImagen") 
             setTimeout(function(){
                query(`${datos["clase"]} .mostrar_foto`).classList.remove("errorImagen") 
             },3000)
            break
          case "actualizarLineaInteres":   
            $.notify(`<span class=${datos["alerta"]}>${datos["mensaje"]}</span>`); 
            break
          case "actualizarDocumento":   
            $.notify(`<span class=${datos["alerta"]}>${datos["mensaje"]}</span>`); 
            break 
          case "vacioDocumento":   
            $.notify(`<span class=${datos["alerta"]}>${datos["mensaje"]}</span>`); 
            break 
          case "actualizarToponimia":   
            $.notify(`<span class=${datos["alerta"]}>${datos["mensaje"]}</span>`); 
            break 
          case "borrarFotoFicha":    
            $.notify(`<span class=${datos["alerta"]}>${datos["mensaje"]}</span>`); 
            break 
          case "documentoPendiente":    
            $.notify(`<span class=${datos["alerta"]}>${datos["mensaje"]}</span>`); 
            break
          case "cargarLibro":    
            $.notify(`<span class=${datos["alerta"]}>${datos["mensaje"]}</span>`); 
            break
          case "actualizarFavoritos":    
            $.notify(`<span class=${datos["alerta"]}>${datos["mensaje"]}</span>`); 
            break
          case "cargarInicio":    
            $.notify(`<span class=${datos["alerta"]}>${datos["mensaje"]}</span>`); 
            break
          case "obligatorio":    
            $.notify(`<span class=${datos["alerta"]}>Los campos con * son abligatorios.</span>`); 
            break
          case "errorServidor":
            $.notify(`<span class=${datos["alerta"]}>No se pudo conectar con el servidor. Verifique su conexión a internet y vuelva a realizar la petición.</span>`);
            break
     }                      
} 

// VALIDACION DE FORMULARIO PARA CAMPOS VACIOS

function camposVaciosFormularios(inf){
     let datos =  inf || {}
     let formulario = datos["formulario"].getElementsByClassName("campo");
     let vacio = false;
     let selectPicker = false;
     
     Array.from(formulario).forEach(function(campo,index){
         
         if(campo.nodeName === "SELECT" && campo.classList.contains("selectpicker")){
             selectPicker = $(`#${campo.id}`).val() == 0 
             if(selectPicker){
                 campo.classList.add("errorForm") 
                 query(`[data-id=${campo.name}]`).classList.add("errorForm","campo")
             }
         }else{
            if(campo.nodeName !== "DIV")
               if(campo.value.trim() === "" || campo.value === "0" || campo.value === null){ 
                  campo.classList.add("errorForm") 
                 
                 vacio = true;    
              }
         }
          
     })
    
    return vacio;
}

// QUITAR ERROR EN LOS FORMULARIOS VACIOS

function removerClassErrorForm(inf){
     var datos =  inf || {}
     var formulario = datos["formulario"].getElementsByClassName("campo");
  
     Array.from(formulario).forEach(function(campo){
                campo.classList.remove("errorForm")  
     })
}


// TABLA GLOBAL PARA MOSTRAR INFORMACION

function TablasDatos(b){
   
  var template = null;   
    
  return template = $(`#${b.tabla}`).DataTable({
   
      responsive: true,
      bProcessing : true,
      bServerSide : false,
      autoWidth   : true, 
      bAutoWidth  : false,
      pageLength  : 5, 
      destroy : true,
      cache: true,
      columns: b.columnas, 
      ajax: {
        url: b.url,
        type: "POST",
        data: b.data,
        beforeSend: function(e) {
        
        },
        error: function (xhr, error, thrown) {
            //  console.log(xhr.responseText);
            $.notify('<span class="text-danger"><b>¡Aviso!.</b> No se cargaron los datos correctamente en la tabla. Intente nuevamente.</span>');
        }
      }

    });
  }


 

const d = document,
      w = window,
      n = navigator,
      c = console.log 

 
function PWA(){
    
 console.log("INICIANDO SERVICEWORKER")

//Registro de Características de PWA's
  //Registro de SW
  if ( 'serviceWorker' in n ) {

      n.serviceWorker.register('./sw.js')
        .then( registration => { 
          c(registration)  
          c(
            'Service Worker registrado con éxito',
            registration.scope
          )
        })
        .catch( err => c(`Registro de Service Worker fallido`, err) )
  }

  //Activar Notificaciones
  if( w.Notification && Notification.permission !== 'denied' ) {
    Notification.requestPermission(status => {
      console.log(status)
      let n = new Notification('Título', {
        body: 'Soy una notificación :)',
        icon: './img/icon_192x192.png'
      })
    })
  }

  //Activar Sincronización de Fondo
  if ( 'serviceWorker' in n && 'SyncManager' in w ) {
    function registerBGSync () {
      n.serviceWorker.ready
        .then(registration => {
          return registration.sync.register('github')
            .then( () => c('Sincronización de Fondo Registrada') )
            .catch( err => c('Fallo la Sincronización de Fondo', err) )
        })
    }

    registerBGSync()
  }


}

// FUNCION AGREGAR CLASE REDUCIDA

function add(id,clase){
        query(id).classList.add(clase); 
}

// FUNCION OBTENER NODO REDUCIDA

function query(consulta){
    return document.querySelector(consulta);
} 


// EXPORTAR FUNCIONES A ARCHIVOS QUE LA REQUIERAN
export {erroresFormulario,camposVaciosFormularios,
        query,exitoFormulario,TablasDatos,removerClassErrorForm,avisoFormulario,PWA}; 
