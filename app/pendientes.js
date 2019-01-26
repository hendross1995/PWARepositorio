import { erroresFormulario,camposVaciosFormularios,
        query, exitoFormulario,TablasDatos,removerClassErrorForm,avisoFormulario} from './funcionesGlobales.js'; 

window.addEventListener('load',iniciarEventos,true)

let tabla = null;
let idAprobar = null; 
let idReprobar = null; 


function iniciarEventos(){ 

   var ver_detalle_pendientes = query("#tabla_pendientes")
   ver_detalle_pendientes.addEventListener("click",verPendientes)

   var ficha_aprobar = query("#tabla_pendientes")
   ficha_aprobar.addEventListener("click",aprovarFichaPreview)

   var ficha_noaprobar = query("#tabla_pendientes")
   ficha_aprobar.addEventListener("click",reprovarFichaPreview)
    
   query("#aprobar").addEventListener("click",aprovarFicha)
   query("#reprobar").addEventListener("click",reprovarFicha)
   
   tabla = imprimirTablaMDocumentales("") 
} 


function verPendientes(e){ 
     
  let datos = new FormData();
  
  var clase = e.target.classList;
  var ver = e.target;
    
  if (clase.contains("fa-eye")) ver = e.target.parentElement

  if(clase.contains("boton_ver_ficha") || clase.contains("fa-eye")){
      
    activarBotones()
     
    ver.innerHTML = "..." 

    datos.append('idfichatecnica',ver.id);
    fetch('ver_detalle_pendientes',{  
      method: 'POST',  
      body: datos}) 
    .then(response => response.json())
    .then(data => {
      if(data.estado){ 
        $('#FrmVerDocumento').html(data.datos) 
        $("#frm_ver_pendientes").modal("show")
      }else{
        avisoFormulario({aviso: "documentoPendiente", mensaje: 'No pudo realizar la petición. Inténtelo nuevamente.',alerta:"text-warning"})
        $("#frm_ver_pendientes").modal("hide")
      }
        desactivarBotones()
        ver.innerHTML = '<i class="fa fa-eye"></i>'
    }).catch(error => {
      $("#frm_ver_pendientes").modal("hide")
      avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
        desactivarBotones()
        ver.innerHTML = '<i class="fa fa-eye"></i>'
    });
  }
}

//********APROVAR FICHA PREVIEW*********//

function aprovarFichaPreview(e){
    
  
  var clase = e.target.classList;
  var aprovar = e.target;
    
  if (clase.contains("fa-check")) aprovar = e.target.parentElement

  if(clase.contains("boton_aprobar_ficha") || clase.contains("fa-check")) idAprobar = aprovar.id
    
}

//********REPROVAR FICHA PREVIEW*********//

function reprovarFichaPreview(e){ 
    
  
  var clase = e.target.classList;
  var reprovar = e.target;
    
  if (clase.contains("fa-ban")) reprovar = e.target.parentElement

  if(clase.contains("boton_reprobar_ficha") || clase.contains("fa-ban")){
      query("#observaciones_pendiente").value = ""
      idReprobar = reprovar.id
  } 
    
}

//****** APROVAR FICHA*****************

function aprovarFicha(e){
    e.preventDefault()
   
    let datos = new FormData(); 
    datos.append('idfichatecnica',idAprobar);
     
     $("#cancelar_aprobar").prop("disabled",true)
     $("#aprobar").prop("disabled",true)                
      
     fetch('aprobar_documentos',{  
       method: 'POST', 
       body: datos}) 
     .then(response => response.json())
     .then(data => {
         
       if(data.estado){ 
        avisoFormulario({aviso: "documentoPendiente",mensaje: data.observacion,alerta:""}) 
        $("#frm_aprobar_ficha").modal("hide")
        tabla.ajax.reload()
       
      }else{
        avisoFormulario({aviso: "documentoPendiente", mensaje: data.observacion,alerta:"text-warning"})
      }
      
      $("#cancelar_aprobar").prop("disabled",false)
      $("#aprobar").prop("disabled",false)    
      
    }).catch(error => {
     avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
       $("#cancelar_aprobar").prop("disabled",false)
       $("#aprobar").prop("disabled",false)    
    }); 
}


//****** REPROVAR FICHA*****************
 

function reprovarFicha(e){
    e.preventDefault()
   
    let datos = new FormData(); 
    datos.append('idfichatecnica',idReprobar);
    datos.append('observaciones_pendiente',query("#observaciones_pendiente").value);
      
     $("#cancelar_reprobar").prop("disabled",true)
     $("#reprobar").prop("disabled",true)
       
     fetch('reprobar_documentos',{  
       method: 'POST',  
       body: datos}) 
     .then(response => response.json())
     .then(data => {
         
       if(data.estado){ 
        avisoFormulario({aviso: "documentoPendiente",mensaje: data.observacion,alerta:""}) 
        $("#frm_reprobar_ficha").modal("hide")
        tabla.ajax.reload()
        
      }else{
        avisoFormulario({aviso: "documentoPendiente", mensaje: data.observacion,alerta:"text-warning"})
      }
      
     $("#cancelar_reprobar").prop("disabled",false)
     $("#reprobar").prop("disabled",false)
      
    }).catch(error => {
     avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
      $("#cancelar_reprobar").prop("disabled",false)
      $("#reprobar").prop("disabled",false)
    }); 
}



function activarBotones(){   
    $(".boton_ver_ficha").prop("disabled",true)
    $(".boton_aprobar_ficha").prop("disabled",true)
    $(".boton_reprobar_ficha").prop("disabled",true)
}

function desactivarBotones(){
   $(".boton_ver_ficha").prop("disabled",false)
   $(".boton_aprobar_ficha").prop("disabled",false)
   $(".boton_reprobar_ficha").prop("disabled",false)
}


function imprimirTablaMDocumentales(vacio){    
    
 return TablasDatos({   
  tabla  : 'tabla_pendientes',
  url    : 'mostrar_pendientes',
  data   : { 
            id : vacio  
          },    
  columnas :  [
                { data: 'num', sClass: "centro"},  
                { data: 'num_ficha', sClass: "centro"},  
                { data: 'nombre'},
                { data: 'creador'},
                { data: 'estado', sClass: "centro"}, 
                { data: 'accion', sClass: "centro"}   
              ],
  }); 
}


