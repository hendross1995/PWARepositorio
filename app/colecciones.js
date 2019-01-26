import { erroresFormulario,camposVaciosFormularios,
        query, exitoFormulario,TablasDatos,removerClassErrorForm,avisoFormulario} from './funcionesGlobales.js'; 

window.addEventListener('load',iniciarEventos,true)

var tabla; 
var accionColeccion = null;
var idColeccion = null

function iniciarEventos(){ 

   var boton_modificar_coleccion = query(".tabla_colecciones")
   var boton_registrar_coleccion = query(".boton_registrar_coleccion")  
   var actualizar_coleccion = query("#actualizar_coleccion")  
   var errorInput = query("#FrmActualizarColeccion") 

   actualizar_coleccion.addEventListener('click',actualizarColeccion)
   boton_modificar_coleccion.addEventListener('click',btnModificarColeccion)
   boton_registrar_coleccion.addEventListener('click',btnRegistrarColeccion)
   errorInput.addEventListener('click',quitarError)
  
   tabla = imprimirTablaColecciones("")
} 

function btnRegistrarColeccion(e){
    
    accionColeccion = "registrar"
    query(".titulo_coleccion").textContent = "Registrar colección"
    query(".actualizar_coleccion").textContent = "REGISTRAR"
    query("#FrmActualizarColeccion").reset();
    query(".estado_coleccion").checked  = true
    query("#nombre_coleccion").focus() 
    removerClassErrorForm({formulario:query("#FrmActualizarColeccion")})
} 

function btnModificarColeccion(e){
    
    var clase = e.target.classList;
    var editar = e.target;
    if (clase.contains("fa-edit")) editar = e.target.parentElement
    
    if(clase.contains("boton_modificar_coleccion") || clase.contains("fa-edit")){
        
       idColeccion = editar.id  
       accionColeccion = "modificar" 
        
       var celdas =  editar.parentElement.parentElement.cells;    
       var check = celdas[5].textContent == "ACTIVO" ? true : false; 
       var select = document.querySelector("#fondo_coleccion").children
       
       Array.from(select).forEach(function(item){
           if(item.textContent == celdas[4].textContent){item.selected = true;return}})
        
       query(".titulo_coleccion").textContent = "Modificar colección";
       query(".actualizar_coleccion").textContent = "MODIFICAR" 
       query("#nombre_coleccion").value = celdas[1].textContent; 
       query("#descripcion_coleccion").value = celdas[2].textContent;
       query("#fecha_registro_coleccion").value = celdas[3].textContent
       query(".estado_coleccion").checked = check;
       removerClassErrorForm({formulario:query("#FrmActualizarColeccion")})
         
    } 
}

function actualizarColeccion(e){  
    
    e.preventDefault();
     
    var datos = new FormData(query("#FrmActualizarColeccion"));
    var coleccionActualizar = {}; 
  
   const data = { 
     formulario: query("#FrmActualizarColeccion") 
   }      
   
   coleccionActualizar = validarDatosColeccion(data) 

   if(coleccionActualizar["acceso"] === true){
        
      datos.get("estado_coleccion")===null? datos.append("estado_coleccion",null):''
      datos.append("codigo",idColeccion) 
      datos.append("accion",accionColeccion)     
       
      var coleccion = query(".actualizar_coleccion")
      var cancelarActColeccion = query(".boton_cancelar_actualizar_coleccion")
      var cerrarColeccion = query(".FrmActualizarColeccion")
      
      var titulo = coleccion.textContent; 
      coleccion.textContent = coleccion.textContent.replace("AR","ANDO...") 
      coleccion.disabled = true;
      cancelarActColeccion.disabled = true 
      
      fetch('actualizar_colecciones',{  
        method: 'POST',
        body: datos}) 
      .then(response => response.json())
      .then(data => {  
        if(data.estado){ 
          avisoFormulario({aviso: "actualizarColeccion",mensaje: data.observacion,alerta:""})  
          $("#frm_actualizar_colecciones").modal("hide") 
          tabla.ajax.reload();
        }else{
          avisoFormulario({aviso: "actualizarColeccion", mensaje: data.observacion,alerta:"text-warning"})
        }
         coleccion.disabled = false
         cancelarActColeccion.disabled = false 
         coleccion.textContent = titulo  
       })
      .catch(error => { 
        avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
        coleccion.textContent = titulo
        coleccion.disabled = false 
        query("#nombre_coleccion").focus()
        cancelarActColeccion.disabled = false
      });
   } 
    
}


(function(){
    
    var datos = new FormData();
    datos.append('fondo_coleccion','');  
    
    $("#fondo_coleccion").html("<option value='0'> Cargando datos.</option>").prop('disabled',true);
     fetch('cargar_fondos',{  
        method: 'POST',
        body: datos}) 
      .then(response => response.json())
      .then(data => {  
          $("#fondo_coleccion").html(data).prop('disabled',false);
       })
      .catch(error => {
         avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
         $("#fondo_coleccion").html('<option value="0">Error</option>').prop('disabled',false);
      });

})()


function validarDatosColeccion(inf){

  var datos = inf || {}
  var acceso = true;
  var excepto = ["descripcion_coleccion","fecha_registro_coleccion"] 
  
   if(camposVaciosFormularios({formulario:datos["formulario"],excepto:excepto}))acceso = false
    
  return {
      acceso: acceso
  }    
    
}

function imprimirTablaColecciones(vacio){    
    
 return TablasDatos({   
  tabla  : 'tabla_colecciones',
  url    : 'mostrar_colecciones', 
  data   : { 
            id : vacio  
          },   
  columnas :  [
                { data: 'num', sClass: "centro"},  
                { data: 'nombre'},
                { data: 'descripcion'}, 
                { data: 'fecha_registro'}, 
                { data: 'fondo',sClass: "color-celda"}, 
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
 



