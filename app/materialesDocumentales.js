import { erroresFormulario,camposVaciosFormularios,
        query, exitoFormulario,TablasDatos,removerClassErrorForm,avisoFormulario} from './funcionesGlobales.js'; 

window.addEventListener('load',iniciarEventos,true)

var tabla; 
var accionMDocumentales = null;
var idMDocumentales = null
 
function iniciarEventos(){ 

   var boton_modificar_materiales_documentales = query(".tabla_materiales_documentales")
   var boton_registrar_materiales_documentales = query(".boton_registrar_material_documental")  
   var actualizar_materiales_documentales = query("#actualizar_material_documental")  
   var errorInput = query("#FrmActualizarMaterialDocumental") 

   actualizar_materiales_documentales.addEventListener('click',actualizarMDocumentales)
   boton_modificar_materiales_documentales.addEventListener('click',btnModificarMDocumentales)
   boton_registrar_materiales_documentales.addEventListener('click',btnRegistrarMDocumentales)
   errorInput.addEventListener('click',quitarError)
  
   tabla = imprimirTablaMDocumentales("") 
} 

function btnRegistrarMDocumentales(e){
    
    accionMDocumentales = "registrar"
    query(".titulo_material_documental").textContent = "Registrar material documental"
    query(".actualizar_material_documental").textContent = "REGISTRAR"
    query("#FrmActualizarMaterialDocumental").reset();
    query(".estado_material_documental").checked  = true
    query("#nombre_material_documental").focus() 
    removerClassErrorForm({formulario:query("#FrmActualizarMaterialDocumental")})
} 

function btnModificarMDocumentales(e){
    
    var clase = e.target.classList;
    var editar = e.target;
    if (clase.contains("fa-edit")) editar = e.target.parentElement
    
    if(clase.contains("boton_modificar_material_documental") || clase.contains("fa-edit")){
        
       idMDocumentales = editar.id  
       accionMDocumentales = "modificar" 
        
       var celdas =  editar.parentElement.parentElement.cells;    
       var check = celdas[2].textContent == "ACTIVO" ? true : false; 
        
       query(".titulo_material_documental").textContent = "Modificar material documental";
       query(".actualizar_material_documental").textContent = "MODIFICAR" 
       query("#nombre_material_documental").value = celdas[1].textContent; 
       query(".estado_material_documental").checked = check;
       removerClassErrorForm({formulario:query("#FrmActualizarMaterialDocumental")})
         
    } 
}

function actualizarMDocumentales(e){  
    
    e.preventDefault();
     
    var datos = new FormData(query("#FrmActualizarMaterialDocumental"));
    var coleccionMDocumentales = {}; 
  
   const data = { 
     formulario: query("#FrmActualizarMaterialDocumental") 
   }      
   
   coleccionMDocumentales = validarDatosMDocumentales(data) 

   if(coleccionMDocumentales["acceso"] === true){

      datos.get("estado_material_documental")===null? datos.append("estado_material_documental",null):''
      datos.append("codigo",idMDocumentales) 
      datos.append("accion",accionMDocumentales)     
       
      var materialD = query(".actualizar_material_documental")
      var cancelarActMaterialD = query(".boton_cancelar_actualizar_material_documental")
      
      var titulo = materialD.textContent; 
      materialD.textContent = materialD.textContent.replace("AR","ANDO...") 
      materialD.disabled = true;
      cancelarActMaterialD.disabled = true 
      
      fetch('actualizar_materialesdocumentales',{  
        method: 'POST',
        body: datos}) 
      .then(response => response.json())
      .then(data => {  
        if(data.estado){ 
          avisoFormulario({aviso: "actualizarMaterialD",mensaje: data.observacion,alerta:""})  
          $("#frm_actualizar_materiales_documentales").modal("hide") 
          tabla.ajax.reload();
        }else{
          avisoFormulario({aviso: "actualizarMaterialD", mensaje: data.observacion,alerta:"text-warning"})
        }
         materialD.disabled = false
         cancelarActMaterialD.disabled = false 
         materialD.textContent = titulo  
       })
      .catch(error => { 
        avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
        materialD.textContent = titulo
        materialD.disabled = false 
        query("#nombre_material_documental").focus()
        cancelarActMaterialD.disabled = false
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
  tabla  : 'tabla_materiales_documentales',
  url    : 'mostrar_materialesdocumentales',
  data   : { 
            id : vacio  
          },   
  columnas :  [
                { data: 'num', sClass: "centro"},  
                { data: 'nombre'},
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
