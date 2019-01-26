import { erroresFormulario,camposVaciosFormularios,
        query, exitoFormulario,TablasDatos,removerClassErrorForm,avisoFormulario} from './funcionesGlobales.js';

window.addEventListener('load',iniciarEventos,true)

var tabla;
var accionDocumento = null
var idDocumento = null 
var datosDocumentos = null

var vaFoto = false;
var eliminarFoto = false;
var nombreFoto = null;
var rutaPorDefectoImagen = "src/inicial/images/anonimo.png"

var estado_verificar_documento = null;

let Documento = query(".actualizar_documento")
let cancelarActDocumento = query(".boton_cancelar_actualizar_documento")
let borrador = query(".boton_guardar_borrador_documento")

let idFotoBorrar = null
let nombreFotoBorrar = null 
let nodoFoto = null

let titulo = null
    

  
 
function iniciarEventos(){  
    
  // SELECT QUE CARGAN MAS SELECT 
  
  query("#fondo_documento").addEventListener('change',function(e){FiltrarSeleccion(
  {event:e,post:"fondo_coleccion",fetch:"cargar_colecciones",id:"#coleccion_documento"})})
  
  query("#archivos_documento").addEventListener('change',function(e){FiltrarSeleccion(
  {event:e,post:"archivo_seccion",fetch:"cargar_secciones",id:"#secciones_documento"})})

  query("#secciones_documento").addEventListener('change',function(e){FiltrarSeleccion(
  {event:e,post:"seccion_nivel",fetch:"cargar_niveles",id:"#nivel_documento"})})
    
  query("#nivel_documento").addEventListener('change',function(e){FiltrarSeleccion(
  {event:e,post:"nivel_contenedor",fetch:"cargar_contenedores",id:"#contenedor_documento"})})
    
  query("#portada_documento").addEventListener('input',CargarFotoDocumento)
  query(".elimminar_portada_documento").addEventListener('click',eliminarFotoDocumentos)
    
  query("#FrmActualizarDocumento").addEventListener('click',eliminarFotoEditarPreview)
  query("#eliminar").addEventListener('click',eliminarFotoEditar)
        
  query("#actualizar_documento").addEventListener('click',actualizarDocumento)
  query(".boton_guardar_borrador_documento").addEventListener('click',actualizarDocumento)
  query(".tabla_documentos").addEventListener('click',btnVerModificarDocumento)
  query(".boton_registrar_documento").addEventListener('click',btnRegistrarDocumento)
  query("#FrmActualizarDocumento") .addEventListener('change',quitarError) 
  query("#FrmActualizarDocumento") .addEventListener('click',quitarError) 
 
  tabla = imprimirTablaDocumentos("")
}
//*********LIMPIAR FORMULARIO****************//

function limpiarFormulario(){
  query("#FrmActualizarDocumento").reset();
  query("#secciones_documento").innerHTML = "" 
  query("#portada_documento").value = ""
  query("#coleccion_documento").innerHTML = ""
  query("#nivel_documento").innerHTML = "" 
  query("#contenedor_documento").innerHTML = ""
  query("#mostrar_portada_documento").src = "src/inicial/images/anonimo.png"
  query(".estado_documento").checked  = true 
  $("#coleccion_documento").innerHTML = ""
  $("#generadores_documento").selectpicker('refresh') 
  $("#toponimia_documento").selectpicker('refresh') 
  $("#personajes_documento").selectpicker('refresh') 
  $("#idiomas_documento").selectpicker('refresh') 
  $("#formato_documento").selectpicker('refresh') 
  $("#tipo_material_documento").selectpicker('refresh') 
  $("#material_soporte_documento").selectpicker('refresh') 
  $("#material_documento_documento").selectpicker('refresh')  
  $('#anos_criticos_documento').tagsinput('removeAll')
  $('#palabras_claves_documento').tagsinput('removeAll')
  removerClassErrorForm({formulario:query("#FrmActualizarDocumento")})

}

//********REGISTRAR LOS DATOS DEL FORMULARIO************//

function btnRegistrarDocumento(e){
  accionDocumento = "registrar"
  query(".titulo_documento").textContent = "Registrar Documento"
  query(".actualizar_documento").textContent = "REGISTRAR"
  limpiarFormulario()
  query("#d-subidos-tab").style.display="none"  
  query("#d-generales-tab").click() 
  query("#d-porsubir-tab").click() 
  
}

  
//************MODIFICAR LOS DATOS DEL FORMULARIO**********//

function btnVerModificarDocumento(e){
  let datos = new FormData();
  var clase = e.target.classList;
  var ver_editar = e.target;
    
  if (clase.contains("fa-edit")) ver_editar = e.target.parentElement
  if (clase.contains("fa-eye")) ver_editar = e.target.parentElement  
  
  if(clase.contains("boton_ver_documento") || clase.contains("fa-eye")){
    idDocumento = ver_editar.id
    accionDocumento = "ver" 
     
    $(".boton_modificar_documento").prop("disabled",true)
    $(".boton_ver_documento").prop("disabled",true)
    
    ver_editar.innerHTML = "..." 

    datos.append('idfichatecnica',idDocumento);
    fetch('ver_detalle_documento',{  
      method: 'POST',  
      body: datos}) 
    .then(response => response.json())
    .then(data => {
      if(data.estado){ 
        $('#FrmVerDocumento').html(data.datos)
        $("#frm_ver_documentos").modal("show")
      }else{
        avisoFormulario({aviso: "actualizarDocumento", mensaje: data.observacion,alerta:"text-warning"})
        $("#frm_ver_documentos").modal("hide")
      }
        $(".boton_modificar_documento").prop("disabled",false)
        $(".boton_ver_documento").prop("disabled",false)
        ver_editar.innerHTML = '<i class="fa fa-eye"></i>'
    }).catch(error => {
      $("#frm_ver_documentos").modal("hide")
      avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
        $(".boton_modificar_documento").prop("disabled",false)
        $(".boton_ver_documento").prop("disabled",false)
        ver_editar.innerHTML = '<i class="fa fa-eye"></i>'
    });
  }else if(clase.contains("boton_modificar_documento") || clase.contains("fa-edit")){
    idDocumento = ver_editar.id   
    accionDocumento = "modificar" 
    $(".boton_modificar_documento").prop("disabled",true)
    $(".boton_ver_documento").prop("disabled",true)
    ver_editar.innerHTML = "..."   
      
    query(".titulo_documento").textContent = "Modificar Documento";
    query(".actualizar_documento").textContent = "MODIFICAR"
    datos.append('idfichatecnica',idDocumento);
   
    fetch('ver_documento_modificar',{  
      method: 'POST', 
      body: datos}) 
    .then(response => response.json())
    .then(data => {
      if(data.estado){
      
        let inf = data.datos[0]
        
        limpiarFormulario()
        vaFoto = false
        nombreFoto= inf["path"]
        query("#d-subidos-tab").style.display="block"
        query("#d-subidos-tab").click()
        query("#d-generales-tab").click() 
          
          
        JSON.parse(inf["anios_criticos"]).forEach(function(name){
            $('#anos_criticos_documento').tagsinput('add',name);
        })
           
         JSON.parse(inf["palabras_claves"]).forEach(function(name){
            $('#palabras_claves_documento').tagsinput('add',name);
        }) 
        
          
        query(`#estado_conservacion_documento option[value='${inf["estado_conservacion"]}']`).selected = true
           
          
        if(inf["archivo"] != false){
          query(`#archivos_documento option[value='${inf["archivo"]}']`).selected = true
         cargarSeccionesPorArchivo(inf["archivo"],inf["seccion"],inf["nivel"],inf["contenedor"])   
        }
          
        if(inf["fondo"] != false){
             query(`#fondo_documento option[value='${inf["fondo"]}']`).selected = true
             cargarColeccionPorFondo(inf["fondo"],inf["coleccion"])
        }  

          
        $("#toponimia_documento").selectpicker('val',inf["toponimia"]); 
        $("#generadores_documento").selectpicker('val',inf["generadores"]); 
        $("#personajes_documento").selectpicker('val',inf["personajes"]); 
        $("#idiomas_documento").selectpicker('val',inf["idiomas"]);  
        $("#material_documento_documento").selectpicker('val',inf["materiales_documentos"]); 
        $("#material_soporte_documento").selectpicker('val',inf["material_soporte"]);
        $("#tipo_material_documento").selectpicker('val',inf["tipo_material_soporte"]); 
        $("#formato_documento").selectpicker('val',inf["formatos"]); 
         
            
        query("#largo_documento").value = inf["largo"]; 
        query("#ancho_documento").value = inf["ancho"];
        query("#transcripcion_documento").value = inf["transcripcion"] 
        query("#descripcion_documento").value = inf["descripcion"] 
        query("#lugar_emision_documento").value = inf["lugar_emision"] 
        query(".recibe_archivos_documentos").innerHTML = inf["archivos_documentos"]
        query("#codigo_institucional_documento").value = inf["codigo_institucional"]
        query("#codigo_patrimonial_documento").value = inf["codigo_patrimonial"]
        query("#asunto_tema_documento").value = inf["asunto_tema"]
        query("#codigo_digital_documento").value = inf["codigo_digital"]
        query("#estado_documento").checked = inf["estado"]
        query("#observaciones_documento").value = inf["observaciones"]
        query("#numero_documento").value = inf["numero_ficha"]
        query("#nombre_sugerido_documento").value = inf["nombre_sugerido"]
        query("#nombre_documento").value = inf["nombre"]
        query("#extension_documento").value = inf["numero_extension"]  
        query("#portada_documento").value = ""
        
        if(inf["portada"] != null || inf["portada"] != ""){
            query("#mostrar_portada_documento").src = inf["portada"]  
            query("#portada_documento").classList.remove("campo")  
        }
    
        $("#frm_actualizar_documentos").modal("show")
      }else{
        $("#frm_actualizar_documentos").modal("hide")
        avisoFormulario({aviso: "actualizarDocumento", mensaje: data.observacion,alerta:"text-warning"})
      }
        $(".boton_modificar_documento").prop("disabled",false)
        $(".boton_ver_documento").prop("disabled",false)
        ver_editar.innerHTML = '<i class="fa fa-edit"></i>'
    }).catch(error => {
      avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
      $("#frm_actualizar_documentos").modal("hide")
      $(".boton_modificar_documento").prop("disabled",false)
      $(".boton_ver_documento").prop("disabled",false)
      ver_editar.innerHTML = '<i class="fa fa-edit"></i>'
    });
  }
}

// ****** CARGAR SELECT CUANDO SE EDITA**************//

function cargarSeccionesPorArchivo(id,sec,niv,cont){
  let datos = new FormData(); 
  datos.append('archivo_seccion',id);
  bloquearBotones() 
 $("#secciones_documento").html("<option value='0' selected> Cargando datos.</option>").prop('disabled',true);
  fetch('cargar_secciones',{  
    method: 'POST', 
    body: datos}) 
  .then(response => response.json())
  .then(data => { 
      $("#secciones_documento").html(data).prop('disabled',false);
      
      if(sec != false){
           query(`#secciones_documento option[value='${sec}']`).selected = true
           cargarNivelesPorSeccion(sec,niv,cont)
      }  
      
      DesbloquearBotones()
  }).catch(error => {
     avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
     $("#secciones_documento").html('<option value="0">Error</option>').prop('disabled',false);
     DesbloquearBotones()
  }); 
}
 

function cargarNivelesPorSeccion(sec,niv,cont){
  let datos = new FormData(); 
    
   datos.append('seccion_nivel',sec);
   bloquearBotones() 
  $("#nivel_documento").html("<option value='0' selected> Cargando datos...</option>").prop('disabled',true); 
  fetch('cargar_niveles',{  
    method: 'POST',
    body: datos}) 
  .then(response => response.json())
  .then(data => {  
     $("#nivel_documento").html(data).prop('disabled',false)
      
     if(niv != false){
         query(`#nivel_documento option[value='${niv}']`).selected = true
         cargarContenedoresPorNiveles(niv,cont)
      } 
    
     DesbloquearBotones()
  }).catch(error => {
     avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
     $("#nivel_documento").html('<option value="0">Error</option>').prop('disabled',false);
     DesbloquearBotones()
  });
}


function cargarContenedoresPorNiveles(niv,cont){
  let datos = new FormData(); 
    
   datos.append('nivel_contenedor',niv); 
   bloquearBotones() 
  $("#contenedor_documento").html("<option value='0' selected> Cargando datos...</option>").prop('disabled',true);
  fetch('cargar_contenedores',{  
    method: 'POST',
    body: datos}) 
  .then(response => response.json())
  .then(data => {  
     $("#contenedor_documento").html(data).prop('disabled',false)
      
      if(cont != false){
         query(`#contenedor_documento option[value='${cont}']`).selected = true
      } 
     
     DesbloquearBotones()
  }).catch(error => {
     avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
     $("#contenedor_documento").html('<option value="0">Error</option>').prop('disabled',false);
     DesbloquearBotones()
  });
}

function cargarColeccionPorFondo(fondo,coleccion){
  let datos = new FormData(); 
    
  datos.append('fondo_coleccion',fondo); 
  bloquearBotones() 
  $("#coleccion_documento").html("<option value='0' selected> Cargando datos...</option>").prop('disabled',true);
  fetch('cargar_colecciones',{  
    method: 'POST',
    body: datos}) 
  .then(response => response.json())
  .then(data => {  
     $("#coleccion_documento").html(data).prop('disabled',false)
     
      if(coleccion != false){
         query(`#coleccion_documento option[value='${coleccion}']`).selected = true
      } 
      
     DesbloquearBotones()
  
  }).catch(error => {
     avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
     $("#coleccion_documento").html('<option value="0">Error</option>').prop('disabled',false);
     DesbloquearBotones()
  });
}

// ****** TERMINA DE SUBIR EL RESTO DEL DOCUMENTO********//

function actualizarRestoDocumento(archivosImagen){  

    datosDocumentos = new FormData(query("#FrmActualizarDocumento"));
    datosDocumentos.get("estado_documento")===null? datosDocumentos.append("estado_documento",null):''
    datosDocumentos.append("codigo",idDocumento)
    datosDocumentos.append("accion",accionDocumento)
    datosDocumentos.append("archivos",archivosImagen)
    
    datosDocumentos.append("personajes_documento",$("#personajes_documento").val())
    datosDocumentos.append("idiomas_documento",$("#idiomas_documento").val())
    datosDocumentos.append("toponimia_documento",$("#toponimia_documento").val())
    datosDocumentos.append("generadores_documento",$("#generadores_documento").val())
    datosDocumentos.append("formato_documento",$("#formato_documento").val())
    datosDocumentos.append("tipo_material_documento",$("#tipo_material_documento").val())
    datosDocumentos.append("material_soporte_documento",$("#material_soporte_documento").val())
    datosDocumentos.append("material_documento_documento",$("#material_documento_documento").val())
    datosDocumentos.append("anos_criticos_documento",$("#anos_criticos_documento").val())
    datosDocumentos.append("estado_verificacion_documento",estado_verificar_documento)
    datosDocumentos.append("vaFoto",vaFoto)
    datosDocumentos.append("eliminarFoto",eliminarFoto)
    datosDocumentos.append("nombreFoto",nombreFoto)
 
    if(estado_verificar_documento == 2){
        titulo = Documento.textContent
        Documento.textContent = Documento.textContent.replace("AR","ANDO...")
    }else{
        titulo = borrador.textContent
        borrador.textContent = borrador.textContent.replace("AR","ANDO")
    }
    
    fetch('actualizar_documentos',{  
      method: 'POST',
      body: datosDocumentos}) 
    .then(response => response.json())
    .then(data => {
      if(data.estado){ 
        avisoFormulario({aviso: "actualizarDocumento",mensaje: data.observacion,alerta:""})  
        $("#frm_actualizar_documentos").modal("hide") 
        tabla.ajax.reload();
      }else{
        //errorImagenes()
        avisoFormulario({aviso: "actualizarDocumento", mensaje: data.observacion,alerta:"text-warning"})
      }
       DesbloquearBotones()
       if(estado_verificar_documento == 2) Documento.textContent = titulo 
       else  borrador.textContent = titulo 
    }).catch(error => {  
      avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
      if(estado_verificar_documento == 2) Documento.textContent = titulo 
      else  borrador.textContent = titulo 
      DesbloquearBotones()
      //errorImagenes()
    });
  } 

//*********ERROR IMAGENES*********//

function errorImagenes(){
    
    if(document.getElementsByClassName("file-upload-indicator").length != 0){
          Array.from(document.getElementsByClassName("file-upload-indicator")).forEach(function(index){
          index.firstChild.className = "fa fa-exclamation-triangle text-danger"
      })
      
      query(".progress-bar-success").textContent = "Fallido"
      query(".progress-bar-success").style.background = "#dc3545"
      query(".progress-bar-success").className = "progress-bar progress-bar-danger"
    }
   
}


//************ACTUALIZAR DATOS DOCUMENTOS*************//

function actualizarDocumento(e){
    
  e.preventDefault(); 
    
  estado_verificar_documento = e.target.getAttribute('verificar')
    
  let DocumentoActualizar = {};
 
  const data = {
    formulario: query("#FrmActualizarDocumento")
  } 
  
  DocumentoActualizar = validarDatosDocumento(data)
   
    
 if(DocumentoActualizar["acceso"] === true){
  bloquearBotones(); 
     
 if(query(".kv-fileinput-error.file-error-message").style.display == "" && query("#archivos").files.length == 1){
     DesbloquearBotones()
     query(".fileinput-remove-button").focus()
 }   
     
 (query("#archivos").files.length != 0)? query(".fileinput-upload-button").click():actualizarRestoDocumento([]) 


  }else {
       
      if(query("#d-generales .errorForm")){
          query("#d-generales-tab").click()
      }else if(query("#d-documental .errorForm")){
          query("#d-documental-tab").click()
      }else if(query("#ubicacion .errorForm")){
          query("#ubicacion-tab").click()
      }else{ 
         query("#_archivos-tab").click() 
      }
      
      avisoFormulario({aviso: "vacioDocumento",mensaje: "¡Ciertos campos requieren su atención!",alerta:"text-warning"})
  }
    
}


//***********SUBIDA DE ARCHIVOS DE LOS DOCUMENTOS**************//
    
  $("#archivos").fileinput({
    theme: 'explorer',
    uploadUrl: 'subir_archivos',
    uploadAsync: false,
    previewFileIcon: '<i class="fa fa-file"></i>',
    allowedPreviewTypes: ['image'], 
    allowedFileExtensions: ['jpg','png','gif','ico','bmp','jpeg','tif','pdf'],
    preferIconicPreview: true,
    maxFileCount: 200,
    maxFileSize: 50000, 
    showUpload:true,
    fileActionSettings : {
      showUpload : false,
      showZoom : true,
    },
    //showCaption:false,
    overwriteInitial: true,
    browseOnZoneClick: true,
    uploadExtraData: function() {
        return {
          accion      : accionDocumento,
          iddocumento : idDocumento,
        };
    },
      previewFileIconSettings: { // configure your icon file extensions
          'doc': '<i class="fa fa-file-word text-primary"></i>',
          'xls': '<i class="fa fa-file-excel text-success"></i>',
          'ppt': '<i class="fa fa-file-powerpoint text-danger"></i>',
          'pdf': '<i class="fa fa-file-pdf text-danger"></i>',
          'zip': '<i class="fa fa-file-archive text-muted"></i>',
          'htm': '<i class="fa fa-file-code text-info"></i>',
          'txt': '<i class="fa fa-file-text text-info"></i>',
          'mov': '<i class="fa fa-file-movie text-warning"></i>',
          'mp3': '<i class="fa fa-file-audio text-warning"></i>',
         
      },
      previewFileExtSettings: { // configure the logic for determining icon file extensions
          'doc': function(ext) {
              return ext.match(/(doc|docx)$/i);
          },
          'xls': function(ext) {
              return ext.match(/(xls|xlsx)$/i);
          },
          'ppt': function(ext) {
              return ext.match(/(ppt|pptx)$/i);
          },
          'zip': function(ext) {
              return ext.match(/(zip|rar|tar|gzip|gz|7z)$/i);
          },
          'htm': function(ext) {
              return ext.match(/(htm|html)$/i);
          },
          'txt': function(ext) {
              return ext.match(/(txt|ini|csv|java|php|js|css)$/i);
          },
          'mov': function(ext) {
              return ext.match(/(avi|mpg|mkv|mov|mp4|3gp|webm|wmv)$/i);
          },
          'mp3': function(ext) {
              return ext.match(/(mp3|wav)$/i);
          },
      }, 
}).on('filebatchpreupload', function(event, data) {
  /*AQUÍ SE DEBEN BLOQUEAR LOS BOTONES Y AGREGAR EL "MODIFICANDO..." O "ENVIANDO A BORRADOR..."*/
    if(estado_verificar_documento == 2){
        titulo = Documento.textContent
        Documento.textContent = Documento.textContent.replace("AR","ANDO...")
    }else{
        titulo = borrador.textContent
        borrador.textContent = borrador.textContent.replace("AR","ANDO")
    }
    
  query("#_archivos-tab").click()
  bloquearBotones()    
}).on('filebatchuploadsuccess', function(event, data, previewId, index) {
  if(data.response.estado == false){
    DesbloquearBotones()
    avisoFormulario({aviso: "actualizarDocumento", mensaje: data.response.notificacion,alerta:"text-warning"})  
  }else actualizarRestoDocumento(data.response.archivos)
      
   if(estado_verificar_documento == 2) Documento.textContent = titulo 
      else  borrador.textContent = titulo       
      
}).on('filebatchuploaderror', function(event, data, previewId, index) {
  query("#_archivos-tab").click()
  avisoFormulario({aviso: "actualizarDocumento", mensaje: data.response.notificacion,alerta:"text-warning"})  
  DesbloquearBotones()
      
   if(estado_verificar_documento == 2) Documento.textContent = titulo 
      else  borrador.textContent = titulo       
})


//************VALIDAR DATOS DOCUMENTOS*************//

function validarDatosDocumento(inf){
  var datos = inf || {}
  var acceso = true;
  var excepto = [""]

  if(camposVaciosFormularios({formulario:datos["formulario"],excepto:excepto}))acceso = false

    
  return {
    acceso: acceso
  }
}

/****CARGAR COLECCION POR FONDO SELECCIONADO*******/


function FiltrarSeleccion(inf){

  let data = inf || {} 
  let selet = data["event"].target.id
  let id = query(`#${selet}`)
    
  data["event"].preventDefault();

  let datos = new FormData();
    
  if(selet == "archivos_documento"){
        query("#secciones_documento").innerHTML = ""  
        query("#nivel_documento").innerHTML = "" 
        query("#contenedor_documento").innerHTML = ""
  }else if(selet == "secciones_documento"){
        query("#nivel_documento").innerHTML = "" 
        query("#contenedor_documento").innerHTML = ""
  }else if(selet == "nivel_documento") 
        query("#contenedor_documento").innerHTML = ""    
   
  datos.append(data["post"],data["event"].target.value);
  bloquearBotones()    
  
 $(data["id"]).html("<option value='0' selected> Cargando datos.</option>").prop('disabled',true);
  fetch(data["fetch"],{  
    method: 'POST', 
    body: datos}) 
  .then(response => response.json())
  .then(resul => {  
      $(data["id"]).html(resul).prop('disabled',false);
      DesbloquearBotones()
  }).catch(error => {
     avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
     $(data["id"]).html('<option value="0">Error</option>').prop('disabled',false);
      DesbloquearBotones()
  });
    
}


//************CARGAR DATOS EN SELECT *************//

(function(){    
     

 let select = 
       [
        {id:"#material_soporte_documento",fetch:"cargar_materialessoporte",tipo:"picker"},
        {id:"#material_documento_documento",fetch:"cargar_materialesdocumentales",tipo:"picker"},
        {id:"#fondo_documento",fetch:"cargar_fondos"},
        {id:"#idiomas_documento",fetch:"cargar_idiomas",tipo:"picker"},
        {id:"#estado_conservacion_documento",fetch:"cargar_estadosconservacion"},
        {id:"#archivos_documento",fetch:"cargar_archivos"},
        {id:"#toponimia_documento",fetch:"cargar_toponimia",tipo:"picker"},
        {id:"#formato_documento",fetch:"cargar_formatos",tipo:"picker"},
        {id:"#tipo_material_documento",fetch:"cargar_tiposmaterialessoporte",tipo:"picker"},
        {id:"#generadores_documento",fetch:"cargar_personajesgeneradores",tipo:"picker"},
        {id:"#personajes_documento",fetch:"cargar_personajesgeneradores",tipo:"picker"}  
       ]

  cargarSelectDesdeInicio(select,function(index){
      if(select.length-1 == index) DesbloquearBotones()
  });
  
    
})() 


//********CARGAR SELECT CUANDO INICIA EL JS*******//

function cargarSelectDesdeInicio(select,aviso){
    
  let cont = 0;
    
  bloquearBotones()    
    
  select.forEach(function(nombre){ 

    $(nombre.id).html("<option value='0' selected> Cargando datos</option>").prop('disabled',true);
    fetch(nombre.fetch) 
    .then(response => response.json()) 
    .then(data => { 
      if((nombre.hasOwnProperty("tipo")) && nombre.tipo === "picker")
          $(nombre.id).html(data).prop('disabled',false).selectpicker('refresh') 
      else $(nombre.id).html(data).prop('disabled',false)
        
       aviso(cont++)
    
     }).catch(error => {
      avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
      $(nombre.id).html('<option value="0">Error</option>').prop('disabled',false);
       aviso(cont++)
     }); 
      
  })
  
}

//*******BLOQUEAR BOTONES**************//

 function bloquearBotones(){
  
    Documento.disabled = true
    cancelarActDocumento.disabled = true 
    borrador.disabled = true 
 }

//******DESBLOQUEAR BOTONES***********//

function DesbloquearBotones(){
       Documento.disabled = false
       cancelarActDocumento.disabled = false 
       borrador.disabled = false 
}

//*******CARGAR LA TABLA QUE MOSTRARA LOS DATOS****//

function imprimirTablaDocumentos(vacio){
 return TablasDatos({
  tabla  : 'tabla_documentos',
  url    : 'mostrar_documentos', 
  data   : {id : vacio},
  columnas :  [
             
                { data: 'num', sClass: "centro"},
                { data: 'num_ficha', sClass: "centro"},
                { data: 'nombre'},  
                { data: 'estado', sClass: "centro"},
                { data: 'estado_verificacion', sClass: "centro"},
                { data: 'accion', sClass: "centro"} 
              ],
  });
}

//*******CARGAR LA FOTO DE PORTADA**************//

function CargarFotoDocumento(e){

  var imagen = e.target.files[0];

    if(imagen["type"].indexOf("image") !== 0){

      query("#portada_documento").value = ""
        avisoFormulario({aviso: "fotoIncorrecta",mensaje: "Solo se permite subir imágenes.",alerta:"text-warning", clase:".foto_personaje_editable"})

    }else{
 
      var datosImagen = new FileReader;
      datosImagen.readAsDataURL(imagen);

      $(datosImagen).on("load", function(event){

        var rutaImagen = event.target.result;

        query(".previsualizar").src = rutaImagen;
            vaFoto = true
            eliminarFoto = false;

      })

    }
}


//********BORRAR FOTO PREVIEW*********//

function eliminarFotoEditarPreview(e){
    
  let clase = e.target.classList;
  let borrar = e.target;
    
  if (clase.contains("fa-trash")) borrar = e.target.parentElement

    
    if(clase.contains("eliminar_archivo") || clase.contains("fa-trash")){

      idFotoBorrar = borrar.getAttribute('data-id')
      nombreFotoBorrar = borrar.getAttribute('data-nombre') 
      nodoFoto = borrar.parentElement.parentElement.parentElement
}
    
}


//********BORRAR CUANDO SE EDITA*********//

function eliminarFotoEditar(e){
    e.preventDefault()
   
    let datos = new FormData(); 
    datos.append('iddocumento',idFotoBorrar);
    datos.append('nombre',nombreFotoBorrar);  
      
    bloquearBotones() 
      
     fetch('eliminar_archivos',{  
       method: 'POST', 
       body: datos}) 
     .then(response => response.json())
     .then(data => {
         
       if(data.estado){ 
        avisoFormulario({aviso: "borrarFotoFicha",mensaje: data.observacion,alerta:""}) 
        $("#frm_eliminar_archivo").modal("hide")
        nodoFoto.remove()
      }else{
        avisoFormulario({aviso: "borrarFotoFicha", mensaje: data.observacion,alerta:"text-warning"})
      }
      
       DesbloquearBotones()
      
    }).catch(error => {
     avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
      DesbloquearBotones()
    }); 
}

//*******ELIMINAR LA FOTO**************//

function eliminarFotoDocumentos(e){
    e.preventDefault() 
    
    query("#portada_documento").value = "" 
    query("#portada_documento").classList.add("campo")
    query("#mostrar_portada_documento").src = rutaPorDefectoImagen
    vaFoto = false 
    eliminarFoto = true
}

function quitarError(e){
  if(e.target.classList.contains("errorForm")) e.target.classList.remove("errorForm") 
}



