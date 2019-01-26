import { erroresFormulario,camposVaciosFormularios,
        query, exitoFormulario,TablasDatos,removerClassErrorForm,avisoFormulario} from './funcionesGlobales.js';

window.addEventListener('load',iniciarEventos,true)

var tabla;
var accionpersonajegenerador = null
var idpersonajegenerador = null
var vaFoto = false;
var eliminarFoto = false;
var nombreFoto = null;
var rutaPorDefectoImagen = "src/inicial/images/anonimo.png"
 
function iniciarEventos(){ 

  var boton_modificar_personajegenerador = query(".tabla_personajesgeneradores")
  var boton_registrar_personajegenerador = query(".boton_registrar_personajegenerador")
  var actualizar_personajegenerador = query("#actualizar_personajegenerador")
  var cargar_foto = query("#foto_personajegenerador")
  var eliminarFoto = query(".elimminar_foto_personajegenerador")
  var errorInput = query("#FrmActualizarPersonajeGenerador")

  actualizar_personajegenerador.addEventListener('click',actualizarPersonaje)
  boton_modificar_personajegenerador.addEventListener('click',btnModificarPersonajeGenerador)
  boton_registrar_personajegenerador.addEventListener('click',btnRegistrarPersonaje)
  cargar_foto.addEventListener('input',CargarFotoPersonajeGenerador)
  eliminarFoto.addEventListener('click',eliminarFotoPersonaje)
  errorInput.addEventListener('click',quitarError)

  tabla = imprimirTablapersonajes("")
}

function btnRegistrarPersonaje(e){
  accionpersonajegenerador = "registrar"
  query(".titulo_personajegenerador").textContent = "Registrar personaje o generador"
  query(".actualizar_personajegenerador").textContent = "REGISTRAR"
  query("#foto_personajegenerador").value = "" 
  query("#mostrar_foto_personajegenerador").src = rutaPorDefectoImagen
  query("#FrmActualizarPersonajeGenerador").reset();
  query(".estado_personajegenerador").checked  = true 
  query("#cedula_personajegenerador").focus()  
  removerClassErrorForm({formulario:query("#FrmActualizarPersonajeGenerador")})
}

function btnModificarPersonajeGenerador(e){
  var clase = e.target.classList;
  var editar = e.target;
  if (clase.contains("fa-edit")) editar = e.target.parentElement

  if(clase.contains("boton_modificar_personajegenerador") || clase.contains("fa-edit")){
    idpersonajegenerador = editar.id   
    accionpersonajegenerador = "modificar" 
     
    var celdas =  editar.parentElement.parentElement.cells;    
    var check = celdas[5].textContent == "ACTIVO" ? true : false; 
    
    vaFoto = false

    query(".titulo_personajegenerador").textContent = "Modificar personaje o generador";
    query(".actualizar_personajegenerador").textContent = "MODIFICAR" 
    query("#foto_personajegenerador").value = "" 
    query("#nombres_personajegenerador").value = celdas[2].textContent
    query("#apellidos_personajegenerador").value = celdas[3].textContent
    query("#sexo_personajegenerador").value = celdas[4].textContent
    query("#cedula_personajegenerador").value =  celdas[7].textContent
    query("#alias_personajegenerador").value = celdas[11].textContent 
    query("#descripcion_personajegenerador").value = celdas[12].textContent
    query("#lugar_nacimiento_personajegenerador").value = celdas[8].textContent
    query("#fecha_nacimiento_personajegenerador").value = celdas[10].textContent
    query("#fecha_disfuncion_personajegenerador").value = celdas[9].textContent
    query("#mostrar_foto_personajegenerador").src = celdas[13].textContent
    query("#nacionalidad_personajegenerador").value = celdas[14].textContent
    query("#organizacion_personajegenerador").value = celdas[15].textContent 
    query("#estado_personajegenerador").checked = check;
    nombreFoto = celdas[16].textContent
      
    removerClassErrorForm({formulario:query("#FrmActualizarPersonajeGenerador")})
  }
}

 

function CargarFotoPersonajeGenerador(e){

	var imagen = e.target.files[0];

  	if(imagen["type"].indexOf("image") !== 0){

  		query("#foto_personajegenerador").value = ""
        avisoFormulario({aviso: "fotoIncorrecta",mensaje: "Solo se permite subir imágenes.",alerta:"text-warning", clase:".foto_personajegenerador_editable"})
        

  	}else if(imagen["size"] > 2000000){    

  		 query("#foto_personajegenerador").value = ""
         avisoFormulario({aviso: "fotoIncorrecta",mensaje: "El tamaño de la imagen debe ser menor a 2Mb.",alerta:"text-warning",clase:".foto_personajegenerador_editable"})

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

function eliminarFotoPersonaje(e){
    e.preventDefault() 
    
    query("#foto_personajegenerador").value = "" 
    query("#mostrar_foto_personajegenerador").src = rutaPorDefectoImagen
    vaFoto = false 
    eliminarFoto = true
}


function actualizarPersonaje(e){
  e.preventDefault();
  var datos = new FormData(query("#FrmActualizarPersonajeGenerador"));
  var personajeActualizar = {};
  const data = {
    formulario: query("#FrmActualizarPersonajeGenerador")
  } 
  personajeActualizar = validarDatospersonaje(data)
  if(personajeActualizar["acceso"] === true){
      
      
    datos.get("estado_personajegenerador")===null? datos.append("estado_personajegenerador",null):''
    datos.append("codigo",idpersonajegenerador)
    datos.append("accion",accionpersonajegenerador)
    datos.append("vaFoto",vaFoto)
    datos.append("eliminarFoto",eliminarFoto)
    datos.append("nombreFoto",nombreFoto)

    var personajegenerador = query(".actualizar_personajegenerador")
    var cancelarActpersonajeGenerador = query(".boton_cancelar_actualizar_personajegenerador")
    var cerrarpersonajegenerador = query(".FrmActualizarPersonajeGenerador")

    var titulo = personajegenerador.textContent;
    personajegenerador.textContent = personajegenerador.textContent.replace("AR","ANDO...")
    personajegenerador.disabled = true;
    cancelarActpersonajeGenerador.disabled = true

    fetch('actualizar_personajesgeneradores',{
      method: 'POST',
      body: datos})
    .then(response => response.json())
    .then(data => {
      if(data.estado){
        avisoFormulario({aviso: "actualizarPersonajeGenerador",mensaje: data.observacion,alerta:""})
        $("#frm_actualizar_personajesgeneradores").modal("hide")
        tabla.ajax.reload();
      }else{
        avisoFormulario({aviso: "actualizarPersonajeGenerador", mensaje: data.observacion,alerta:"text-warning"})
      }
       personajegenerador.disabled = false
       cancelarActpersonajeGenerador.disabled = false
       personajegenerador.textContent = titulo
    }).catch(error => {
      avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
      personajegenerador.textContent = titulo
      personajegenerador.disabled = false
      cancelarActpersonajeGenerador.disabled = false
    });
  }
}

function validarDatospersonaje(inf){
  var datos = inf || {}
  var acceso = true;
  var excepto = [""]

  if(camposVaciosFormularios({formulario:datos["formulario"],excepto:excepto}))acceso = false

  return {
    acceso: acceso
  }
}

function imprimirTablapersonajes(vacio){
 return TablasDatos({
  tabla  : 'tabla_personajesgeneradores',
  url    : 'mostrar_personajesgeneradores',
  data   : {id : vacio},
  columnas :  [
                {
                  className      : 'details-control centro sombra',
                  defaultContent : '<i class="fa fa-angle-right desglosar_menu_tabla"></i>'
                },
                { data: 'num', sClass: "centro"},
                { data: 'nombres'},
                { data: 'apellidos'},
                { data: 'sexo', sClass: "centro"},
                { data: 'estado', sClass: "centro"},
                { data: 'accion', sClass: "centro"},
                { data: 'cedula', sClass: "oculto"},
                { data: 'lugar_nacimiento', sClass: "oculto"},
                { data: 'fecha_nacimiento', sClass: "oculto"},
                { data: 'fecha_disfuncion', sClass: "oculto"},
                { data: 'alias', sClass: "oculto"},
                { data: 'descripcion', sClass: "oculto"},
                { data: 'foto_carnet', sClass: "oculto"},
                { data: 'nacionalidad', sClass: "oculto"},
                { data: 'organizacion', sClass: "oculto"},
                { data: 'path', sClass: "oculto"},
              ],
  });
}

$('#tabla_personajesgeneradores tbody').on('click', 'td.details-control', function () {
   var tr  = $(this).closest('tr'),
       row = tabla.row(tr);
   if (row.child.isShown()) {
     tr.next('tr').removeClass('details-row');
     row.child.hide();
     tr.removeClass('shown');
     tr.find('td:first i').remove();
     tr.find('td:first').append('<i class="fa fa-angle-right desglosar_menu_tabla"></i>');
   }
   else {
     row.child(format(row.data())).show();
     tr.next('tr').addClass('details-row');
     tr.addClass('shown');
     tr.find('td:first i').remove();
     tr.find('td:first').append('<i class="fa fa-angle-down desglosar_menu_tabla"></i>');
   }
});

function format ( d ) {
    return '<table class="table table-hover table-bordered table-striped">'+
        '<tr><td><b>Cédula:</b></td><td>'+(d.cedula == '' || d.cedula == null ?'No registrado': d.cedula)+'</td></tr>'+
        '<tr><td><b>Álias:</b></td><td>'+(d.alias == '' || d.alias == null ?'No registrado': d.alias)+'</td></tr>'+
        '<tr><td><b>Descripción:</b></td><td>'+(d.descripcion == '' || d.descripcion == null ?'No registrado': d.descripcion)+'</td></tr>'+
        '<tr><td><b>Lugar de nacimiento:</b></td><td>'+(d.lugar_nacimiento == '' || d.lugar_nacimiento == null ?'No registrado': d.lugar_nacimiento)+'</td></tr>'+
        '<tr><td><b>Fecha de nacimiento:</b></td><td>'+(d.fecha_nacimiento == '' || d.fecha_nacimiento == null ?'No registrado': d.fecha_nacimiento)+'</td></tr>'+
        '<tr><td><b>Fecha de disfunción:</b></td><td>'+(d.fecha_disfuncion == '' || d.fecha_disfuncion == null ?'No registrado': d.fecha_disfuncion)+'</td></tr>'+
        '<tr><td><b>Nacionalidad:</b></td><td>'+(d.nacionalidad == '' || d.nacionalidad == null ?'No registrado': d.nacionalidad)+'</td></tr>'+
        '<tr><td><b>Organización:</b></td><td>'+(d.organizacion == '' || d.organizacion == null ?'No registrado': d.organizacion)+'</td></tr>'+
        '<tr><td><b>Foto:</b></td><td>'+(d.foto_carnet == '' || d.foto_carnet == null?'No registrado':'<img class="img-circle" src='+d.foto_carnet+' alt="Foto">')+'</td></tr>'+'<tr style="display:none"><td></td><td>'+d.path+'</td></tr>'
    '</table>';
}
  
function quitarError(e){
  if(e.target.classList.contains("errorForm")){
      e.target.classList.remove("errorForm")
  }
}
