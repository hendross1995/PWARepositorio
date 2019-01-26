
import { erroresFormulario,camposVaciosFormularios,
        query, exitoFormulario,TablasDatos,removerClassErrorForm,avisoFormulario} from './funcionesGlobales.js';
 
window.addEventListener('load',iniciarEventos,true)

function iniciarEventos(){
  var cargarCanton = query("#provincia_usuario")
  var cargarParroquia = query("#canton_usuario")
  var errorInput = query("#FrmActualizarUsuario")

  cargarCanton.addEventListener('change',filtrarPorCanton)
  cargarParroquia.addEventListener('change',filtrarPorParroquia)
  errorInput.addEventListener('click',quitarError)
        
} 



  query("#FrmActualizarUsuario").addEventListener('submit',function(e){
      
      e.preventDefault();
      
      var UsuarioActualizar = {}
      const data = { 
        formulario: e.target
      } 
      
     UsuarioActualizar = validarDatosUsuario(data)
  
  if(UsuarioActualizar["acceso"] === true){
     
    let datos = new FormData(e.target);  
      
    datos.append("codigo","")
    datos.append("accion","modificar")

     var Usuario = query(".actualizar_perfil") 
     var titulo = Usuario.value;
     Usuario.value = Usuario.value.replace("AR","ANDO...")
     $(".actualizar_perfil").prop('disabled',true); 

    fetch('actualizar_perfil',{
      method: 'POST',
      body: datos})
    .then(response => response.json()) 
    .then(data => {
      if(data.estado){
        avisoFormulario({aviso: "actualizarUsuario",mensaje: data.observacion,alerta:""})
        setTimeout(function(){
            location.reload(); 
        },1000)
      }else{ 
        avisoFormulario({aviso: "actualizarUsuario", mensaje: data.observacion,alerta:"text-warning"})
        $(".actualizar_perfil").prop('disabled',false); 
      }
        
      Usuario.value = titulo

    }).catch(error => {
      avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
      Usuario.value = titulo
      $(".actualizar_perfil").prop('disabled',false); 
    });
  }else{
      avisoFormulario({aviso: "obligatorio"}) 
  }
     
  })

 

function cargarCantones(id,canton,parroquia){
  let datos = new FormData();
  
  datos.append('provincia',id);
  
 $("#canton_usuario").html("<option value='0' selected> Cargando datos.</option>").prop('disabled',true);
  fetch('cargar_cantones',{  
    method: 'POST',  
    body: datos}) 
  .then(response => response.json())
  .then(data => {  
       
     $("#canton_usuario").html(data).prop('disabled',false);
      
       if(canton != false){
           query(`#canton_usuario option[value='${canton}']`).selected = true
           cargarParroquias(canton,parroquia)
       }else{
           $(".actualizar_perfil").prop('disabled',false);
       }
  
  }).catch(error => {
     $(".actualizar_perfil").prop('disabled',false);
     avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
     $("#canton_usuario").html('<option value="0">Error</option>').prop('disabled',false);
     query("#nombre_usuario").focus()
  });
}

function cargarParroquias(id,parroquia){
  let datos = new FormData();

  datos.append('canton',id);
  
  $("#parroquia_usuario").html("<option value='0' selected> Cargando datos...</option>").prop('disabled',true);
  fetch('cargar_parroquias',{  
    method: 'POST',
    body: datos}) 
  .then(response => response.json()) 
  .then(data => {  
      
     $("#parroquia_usuario").html(data).prop('disabled',false);
      
      if( parroquia != false){
           query(`#parroquia_usuario option[value='${parroquia}']`).selected = true
       }
      
        $(".actualizar_perfil").prop('disabled',false);
  
  }).catch(error => {
     $(".actualizar_perfil").prop('disabled',false);
     avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
     $("#parroquia_usuario").html('<option value="0">Error</option>').prop('disabled',false);
     query("#nombre_usuario").focus()
  });
}



function filtrarPorCanton(e){
  e.preventDefault();
  let datos = new FormData();
   
  datos.append('provincia',e.target.value);
    
 $(".actualizar_perfil").prop('disabled',true);
    
 $("#canton_usuario").html("<option value='0' selected> Cargando datos...</option>").prop('disabled',true);
  fetch('cargar_cantones',{  
    method: 'POST', 
    body: datos}) 
  .then(response => response.json())
  .then(data => {  
      $(".actualizar_perfil").prop('disabled',false);
      $("#canton_usuario").html(data).prop('disabled',false);
      $("#parroquia_usuario").html('')
     
  }).catch(error => {
     avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
     $("#canton_usuario").html('<option value="0">Error</option>').prop('disabled',false);
     $("#parroquia_usuario").html('')
      $(".actualizar_perfil").prop('disabled',false);
     query("#nombre_usuario").focus()
  });
}

function filtrarPorParroquia(e){
  e.preventDefault();
    
  let datos = new FormData();
  
  datos.append('canton',e.target.value);
   $(".actualizar_perfil").prop('disabled',true);
     
  $("#parroquia_usuario").html("<option value='0' selected> Cargando datos...</option>").prop('disabled',true);
  fetch('cargar_parroquias',{  
    method: 'POST',
    body: datos}) 
  .then(response => response.json())
  .then(data => {  
     $("#parroquia_usuario").html(data).prop('disabled',false)
     $(".actualizar_perfil").prop('disabled',false); 
  }).catch(error => {
     avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
     $("#parroquia_usuario").html('<option value="0">Error</option>').prop('disabled',false);
      $(".actualizar_perfil").prop('disabled',false);
     query("#nombre_usuario").focus()
  });
}

function validarDatosUsuario(inf){
  var datos = inf || {}
  var acceso = true;
  var excepto = [""]

  if(camposVaciosFormularios({formulario:datos["formulario"],excepto:excepto}))acceso = false

  return {
    acceso: acceso
  }
}

//************CARGAR PROVINCIAS Y DATOS*************//

(function(){
    
    
  query("#FrmActualizarUsuario").reset();
  removerClassErrorForm({formulario:query("#FrmActualizarUsuario")})
    

  $("#provincia_usuario").html("<option value='0' selected> Cargando datos.</option>").prop('disabled',true);
  fetch('cargar_provincias') 
  .then(response => response.json())
  .then(data => {  
    $("#provincia_usuario").html(data).prop('disabled',false);
  }).catch(error => {
    avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
    $("#provincia_usuario").html('<option value="0">Error</option>').prop('disabled',false);
  });
    
   
    let datos = new FormData();
      
     datos.append('idUsuario',"")
             
     $.ajax({
            url: 'mostrar_perfil',  
            type: 'POST',
            data: datos,
            cache: false,  
            contentType: false,
            processData: false,
            beforeSend: function(){
      
              $(".actualizar_perfil").prop('disabled',true);  
                
            },
            success: function(data){
              let lector = JSON.parse(data).data[0]
                
               if(lector.idprovincia != false){
                 query(`#provincia_usuario option[value='${lector.idprovincia}']`).selected = true
                 cargarCantones(lector.idprovincia,lector.idcanton,lector.idparroquia)    
              }else{
                  $(".actualizar_perfil").prop('disabled',false);
              }
                
                
              query(`#sexo_usuario option[value='${lector.sexo}']`).selected = true    
              query("#correo_usuario").value = lector.usuario
              query("#cedula_usuario").value =  lector.cedula
              query("#apellidos_usuario").value = lector.apellidos
              query("#nombres_usuario").value = lector.nombres
              query("#direccion_usuario").value = lector.direccion
              query("#convencional_usuario").value = lector.convencional
              query("#celular_usuario").value = lector.celular
                 
            },
            error: function(data){
               query(".actualizar_perfil").disabled = false 
               avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
              $(".actualizar_perfil").prop('disabled',false); 
            }
        });   
    
    
})()

//******************************************//

  
function quitarError(e){
  if(e.target.classList.contains("errorForm")){
      e.target.classList.remove("errorForm")
  }
}
