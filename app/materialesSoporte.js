import { erroresFormulario,camposVaciosFormularios,
        query, exitoFormulario,TablasDatos,removerClassErrorForm,avisoFormulario} from './funcionesGlobales.js'; 

window.addEventListener('load',iniciarEventos,true)

var tabla; 
var accionMSoporte = null;
var idMSoporte = null
 
function iniciarEventos(){ 

   var b_modificar_m_soportes = query(".tabla_materiales_soporte")
   var b_registrar_m_soportes = query(".boton_registrar_material_soporte")  
   var actualizar_m_soportes = query("#actualizar_material_soporte")  
   var errorInput = query("#FrmActualizarMaterialSoporte") 

   actualizar_m_soportes.addEventListener('click',actualizarMSoportes)
   b_modificar_m_soportes.addEventListener('click',btnModificarMSoportes)
   b_registrar_m_soportes.addEventListener('click',btnRegistrarMSoportes) 
   errorInput.addEventListener('click',quitarError)
  
   tabla = imprimirTablaMSoporte("") 
} 

function btnRegistrarMSoportes(e){
    
    accionMSoporte = "registrar"
    query(".titulo_material_soporte").textContent = "Registrar material de soporte"
    query(".actualizar_material_soporte").textContent = "REGISTRAR"
    query("#FrmActualizarMaterialSoporte").reset();
    query(".estado_material_soporte").checked  = true
    query("#nombre_material_soporte").focus()  
    removerClassErrorForm({formulario:query("#FrmActualizarMaterialSoporte")})
} 

function btnModificarMSoportes(e){
    
    var clase = e.target.classList;
    var editar = e.target;
    if (clase.contains("fa-edit")) editar = e.target.parentElement
    
    if(clase.contains("boton_modificar_material_soporte") || clase.contains("fa-edit")){

       idMSoporte = editar.id  
       accionMSoporte = "modificar" 
        
       var celdas =  editar.parentElement.parentElement.cells;    
       var check = celdas[2].textContent == "ACTIVO" ? true : false; 
        
       query(".titulo_material_soporte").textContent = "Modificar material de soporte"; 
       query(".actualizar_material_soporte").textContent = "MODIFICAR" 
       query("#nombre_material_soporte").value = celdas[1].textContent; 
       query(".estado_material_soporte").checked = check;
       removerClassErrorForm({formulario:query("#FrmActualizarMaterialSoporte")})
         
    } 
}

function actualizarMSoportes(e){  
    
    e.preventDefault();
     
    var datos = new FormData(query("#FrmActualizarMaterialSoporte"));
    var mSoporte = {}; 
  
   const data = { 
     formulario: query("#FrmActualizarMaterialSoporte") 
   }      
   
   mSoporte = validarDatosMSoportes(data) 

   if(mSoporte["acceso"] === true){

      datos.get("estado_material_soporte")===null? datos.append("estado_material_soporte",null):''
      datos.append("codigo",idMSoporte) 
      datos.append("accion",accionMSoporte)     

       
      var materialD = query(".actualizar_material_soporte")
      var cancelarActMaterialD = query(".boton_cancelar_actualizar_material_soporte")
      
      var titulo = materialD.textContent; 
      materialD.textContent = materialD.textContent.replace("AR","ANDO...") 
      materialD.disabled = true;
      cancelarActMaterialD.disabled = true 
      
      fetch('actualizar_materialessoporte',{   
        method: 'POST',
        body: datos}) 
      .then(response => response.json())
      .then(data => {  
        if(data.estado){ 
          avisoFormulario({aviso: "actualizarMaterialS",mensaje: data.observacion,alerta:""})  
          $("#frm_actualizar_materiales_soporte").modal("hide") 
          tabla.ajax.reload();
        }else{
          avisoFormulario({aviso: "actualizarMaterialS", mensaje: data.observacion,alerta:"text-warning"})
        }
         materialD.disabled = false
         cancelarActMaterialD.disabled = false 
         materialD.textContent = titulo  
       }) 
      .catch(error => { 
        avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
        materialD.textContent = titulo
        materialD.disabled = false 
        query("#nombre_material_soporte").focus()
        cancelarActMaterialD.disabled = false
      });
   } 
    
}

function validarDatosMSoportes(inf){

  var datos = inf || {}
  var acceso = true;
  var excepto = [""] 
   
   if(camposVaciosFormularios({formulario:datos["formulario"],excepto:excepto}))acceso = false
    
  return {
      acceso: acceso
  }    
    
}

function imprimirTablaMSoporte(vacio){    
    
 return TablasDatos({   
  tabla  : 'tabla_materiales_soporte',
  url    : 'mostrar_materialessoporte',
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
