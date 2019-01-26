import { erroresFormulario,camposVaciosFormularios,
        query, exitoFormulario,TablasDatos,removerClassErrorForm,avisoFormulario} from './funcionesGlobales.js'; 

window.addEventListener('load',iniciarEventos,true)

var tabla;
var accionFondo = null;
var idFondo = null

function iniciarEventos(){ 
    
   var boton_modificar_fondo = query(".tabla_fondos")
   var boton_registrar_fondo = query(".boton_registrar_fondo")
   var actualizar_fondo = query("#actualizar_fondo")  
   var errorInput = query("#FrmActualizarFondo") 
   
   actualizar_fondo.addEventListener('click',actualizarFondo)
   boton_modificar_fondo.addEventListener('click',btnModificarFondo)
   boton_registrar_fondo.addEventListener('click',btnRegistrarFondo)
   errorInput.addEventListener('click',quitarError)
    
   tabla = imprimirTablaFondos("")
} 

function btnRegistrarFondo(e){

    accionFondo = "registrar"
    query(".titulo_fondo").textContent = "Registrar fondo documental"
    query(".actualizar_fondo").textContent = "REGISTRAR"
    query("#FrmActualizarFondo").reset();
    query(".estado_fondo").checked  = true
    query("#nombre_fondo").focus() 
    removerClassErrorForm({formulario:query("#FrmActualizarFondo")})
} 
 
function btnModificarFondo(e){
    
    var clase = e.target.classList;
    var editar = e.target;
    if (clase.contains("fa-edit")) editar = e.target.parentElement 
    
    if(clase.contains("boton_modificar_fondo") || clase.contains("fa-edit")){
        
       idFondo = editar.id  
       accionFondo = "modificar" 
        
       var celdas =  editar.parentElement.parentElement.cells;    
       var check = celdas[3].textContent == "ACTIVO" ? true : false; 
        
       query(".titulo_fondo").textContent = "Modificar fondo documental";
       query(".actualizar_fondo").textContent = "MODIFICAR" 
       query("#nombre_fondo").value = celdas[1].textContent; 
       query("#descripcion_fondo").value = celdas[2].textContent; 
       query(".estado_fondo").checked = check;
       removerClassErrorForm({formulario:query("#FrmActualizarFondo")})
         
    } 
}

function actualizarFondo(e){  
    
    e.preventDefault();
    
    var datos = new FormData(query("#FrmActualizarFondo"));
    var fondoActualizar = {}; 
  
   const data = { 
     nombre: datos.get("nombre_fondo"),
     formulario: query("#FrmActualizarFondo")
   }      
   
      
   fondoActualizar = validarDatosFondo(data);

   if(fondoActualizar["acceso"] === true){
       
      datos.get("estado_fondo")===null? datos.append("estado_fondo",null):''
      datos.append("codigo",idFondo) 
      datos.append("accion",accionFondo)    
       
      var fondo = query(".actualizar_fondo")
      var cancelarActFondo = query(".boton_cancelar_actualizar_fondo")
      var cerrarFondo = query(".FrmActualizarFondo")
      
      var titulo = fondo.textContent; 
      fondo.textContent = fondo.textContent.replace("AR","ANDO...") 
      fondo.disabled = true;
      cancelarActFondo.disabled = true 
      
      fetch('actualizar_fondos',{  
        method: 'POST',
        body: datos}) 
      .then(response => response.json())
      .then(data => {  
        if(data.estado){ 
          avisoFormulario({aviso: "actualizarFondo",mensaje: data.observacion,alerta:""})  
          $("#frm_actualizar_fondos").modal("hide") 
          tabla.ajax.reload();
        }else{
          avisoFormulario({aviso: "actualizarFondo", mensaje: data.observacion,alerta:"text-warning"})
        }
         fondo.disabled = false
         cancelarActFondo.disabled = false 
         fondo.textContent = titulo  
       })
      .catch(error => { 
        avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
        fondo.textContent = titulo
        fondo.disabled = false 
        query("#nombre_fondo").focus()
        cancelarActFondo.disabled = false
      });
   }
    
}


function validarDatosFondo(inf){

  var datos = inf || {}
  var acceso = true;
  var excepto = ["descripcion_fondo"] 
  
   if(camposVaciosFormularios({formulario:datos["formulario"],excepto:excepto}))acceso = false
    
  return {
      acceso: acceso
  }    
    
}

function imprimirTablaFondos(vacio){ 
   
 return TablasDatos({   
  tabla  : 'tabla_fondos',
  url    : 'mostrar_fondos',
  data   : {
            id : vacio 
          },   
  columnas :  [
                { data: 'num', sClass: "centro"},  
                { data: 'nombre'},
                { data: 'descripcion'}, 
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




