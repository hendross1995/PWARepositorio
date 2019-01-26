
import {query,avisoFormulario} from './funcionesGlobales.js';

window.addEventListener('load',iniciarEventos,true)

let pagina,fondo,coleccion,totalItems, errorTest = true
let totalAnios = 0;
let totalPalabrasClaves = 0;

function iniciarEventos(){ 
  query("#filtrar_fondo").addEventListener('change',filtrarColeccionesPorFondo) 
  query("#filtrar_fondo").addEventListener('change',buscarFondo) 
  query("#filtrar_coleccion").addEventListener('change',buscarColeccion) 
  query("#filtrar_generadores").addEventListener('change',buscarGeneradores) 
  query("#filtrar_personajes").addEventListener('change',buscarPersonajes) 
  query("#filtrar_toponimia").addEventListener('change',buscarTopononimia) 
  query("#filtrar_idioma").addEventListener('change',buscarIdioma) 
  query("#filtrar_material_soporte").addEventListener('change',buscarMaterialSoporte) 
  query("#filtrar_material_documento").addEventListener('change',buscarMaterialDocumento) 
  query("#seccion_fecha_especifica").addEventListener('keyup',buscarAnosCriticos)
  query("#seccion_palabras_claves").addEventListener('keyup',buscarPalabrasClaves)
  query("#inputBuscarPrincipal").value = ""
  $('#inputFechaEspecifica').tagsinput('removeAll');

} 

// **** FUNCION QUE ME ACTUALIZA LOS DATOS A BUSCAR ***** //

function actualizarBusqueda(es_pagina){
  if(!es_pagina){ pagina = 1;}
    return {
            pagina: pagina,
            fondo:fondo,
            coleccion:coleccion,
            palabra:$("#inputBuscarPrincipal").val().trim(),
            generadores:$('#filtrar_generadores').val(),
            personajes:$('#filtrar_personajes').val(),
            toponimia:$('#filtrar_toponimia').val(),
            idioma:$('#filtrar_idioma').val(),
            material_soporte:$('#filtrar_material_soporte').val(),
            material_documento:$('#filtrar_material_documento').val(),
            anos:$("#inputFechaEspecifica").val(),
            palabras_claves:$("#inputPalabrasClaves").val(),
         }
  }


//**** LIMPIAR FILTROS ****//

function estableceSeccionesFiltros(){
    
  if($('#check_intervalo_fecha').prop('checked')){
      
    $('#seccion_intervalo_fechas').removeAttr('hidden');
    $('#seccion_fecha_especifica').attr('hidden',true);
      
  }
  if($('#check_anio_critico').prop('checked')){
    
    $('#seccion_fecha_especifica').removeAttr('hidden');
    $('#seccion_intervalo_fechas').attr('hidden',true);
    $('#inputHasta,#inputDesde').val('');
      
  }
}



//***** INICIAR TODOS LOS SELECT ******//

(function(){     
  //estableceSeccionesFiltros()
  let select = 
      [
        {id:"#filtrar_fondo",fetch:"cargar_fondos"},
        {id:"#filtrar_generadores",fetch:"cargar_generadores"},
        {id:"#filtrar_personajes",fetch:"cargar_personajes"},
        {id:"#filtrar_toponimia",fetch:"cargar_toponimia"},
        {id:"#filtrar_idioma",fetch:"cargar_idiomas"},
        {id:"#filtrar_material_soporte",fetch:"cargar_materialessoporte"},
        {id:"#filtrar_material_documento",fetch:"cargar_materialesdocumentales"},
      ]
  cargarSelectDesdeInicio(select)
  DocumentoMasVistos()
})() 
 

//********CARGAR SELECT CUANDO INICIA EL JS*******//

function cargarSelectDesdeInicio(select,aviso){
      
 select.forEach(function(nombre){ 
    
     $.ajax({
            url: nombre.fetch,  
            type: 'GET',
            cache: false, 
            contentType: false,
            processData: false,
            beforeSend: function(){
      
              $(nombre.id).html("<option value='0' selected> Cargando datos...</option>").prop('disabled',true);         
            },
            success: function(data){
               $(nombre.id).html(JSON.parse(data)).prop('disabled',false).selectpicker('refresh')
            },
            error: function(data){
          
             avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
             $(nombre.id).html('<option value="0">Error</option>').prop('disabled',false);
            }
        });     
    
  })
}


//**** FILTRAR COLECCIONES POR FONDO ****//

function filtrarColeccionesPorFondo(e){
    
    let datos = new FormData();
  
    datos.append('fondo_coleccion',e.target.options[e.target.selectedIndex].value)
    
      $.ajax({
            url: 'cargar_colecciones',  
            type: 'POST',
            data: datos, 
            cache: false,
            contentType: false,
            processData: false,

            beforeSend: function(){
              $("#filtrar_coleccion").html("<option value='0' selected> Cargando datos...</option>").prop('disabled',true).selectpicker('refresh')
            },
            success: function(data){
             $("#filtrar_coleccion").html(JSON.parse(data)).prop('disabled',false).selectpicker('refresh')

            },
            error: function(error){
              avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
              $("#filtrar_coleccion").html('<option value="0">Error</option>').prop('disabled',false).selectpicker('refresh');
            }
        });     
}

//******** VALIDAR PALABRAS ***********//

query("#inputBuscarPrincipal").onchange = function(e){
    
	var busqueda = e.target.value
  var expresion = /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]*$/

	if(!expresion.test(busqueda)) {
        
        errorTest = true
        e.target.value = ""
        
    }else{
        errorTest = false
    }

}

//******** BUSCADOR CON PALABRAS ***********//

query("#inputBuscarPrincipal").onkeyup = function(event){

    event.preventDefault();

    if(event.keyCode == 13 && event.target.value.trim() != ""){

			 busquedaDocumentos(actualizarBusqueda())

		}
      
       if(event.target.value.trim() == "" && !errorTest){

			 busquedaDocumentos(actualizarBusqueda())
             errorTest = true
		}

}


//******** BUSCAR CON EL BOTON BUSCAR ***********//

query("#btn_buscar_principal").onclick = function(event){
    	
        event.preventDefault();

		if(query("#inputBuscarPrincipal").value.trim() != ""){ 

			 busquedaDocumentos(actualizarBusqueda())

		}else{
            query("#inputBuscarPrincipal").focus()
        }
}


//**** BUSCAR ANOS CRITICOS ****//
function buscarAnosCriticos(e){

     e.preventDefault();
    
    if((e.keyCode == 13 )){
        totalAnios = $("#inputFechaEspecifica").val().length
        busquedaDocumentos(actualizarBusqueda())
    }
    
    if((e.keyCode == 8) && $("#inputFechaEspecifica").val().length != totalAnios){
       busquedaDocumentos(actualizarBusqueda())
    } 
}


//**** BUSCAR PALABRAS CLAVES ****//
function buscarPalabrasClaves(e){
  e.preventDefault();
  if((e.keyCode == 13 )){
    totalPalabrasClaves = $("#inputPalabrasClaves").val().length
    busquedaDocumentos(actualizarBusqueda())
  }
  if((e.keyCode == 8) && $("#inputPalabrasClaves").val().length != totalPalabrasClaves){
    busquedaDocumentos(actualizarBusqueda())
  }
}


//**** BUSCAR FONDOS ****//

function buscarFondo(e){
    e.preventDefault();
    fondo = e.target.options[e.target.selectedIndex].value
    coleccion = undefined  
    
        busquedaDocumentos(actualizarBusqueda())
}

//**** BUSCAR ANOS COLECCION ****//

function buscarColeccion(e){
     e.preventDefault();
     coleccion = e.target.options[e.target.selectedIndex].value    
    
       busquedaDocumentos(actualizarBusqueda())
}

//**** BUSCAR GENERADORES ****//
function buscarGeneradores(e){
  e.preventDefault();
  busquedaDocumentos(actualizarBusqueda())
}

//**** BUSCAR PERSONAJES ****//
function buscarPersonajes(e){
  e.preventDefault();
  busquedaDocumentos(actualizarBusqueda())
}

//**** BUSCAR TOPONIMIA ****//
function buscarTopononimia(e){
  e.preventDefault();
  busquedaDocumentos(actualizarBusqueda())
} 

//**** BUSCAR IDIOMA ****//
function buscarIdioma(e){
  e.preventDefault();
  busquedaDocumentos(actualizarBusqueda())
}

//**** BUSCAR MATERIAL DE SOPORTE ****//
function buscarMaterialSoporte(e){
  e.preventDefault();
  busquedaDocumentos(actualizarBusqueda())
}

//**** BUSCAR MATERIAL DE DOCUMENTO ****//
function buscarMaterialDocumento(e){
  e.preventDefault();
  busquedaDocumentos(actualizarBusqueda())
}

    
//**** BUSQUEDA CON MUCHOS PARAMETROS ****//
  function busquedaDocumentos(inf){
    let datos = inf || {}; 
    let data = new FormData();
    let contenido = query('#documentosAcordeon ol')
    let paginacion = query('#paginacion')
     
    datos["fondo"]= datos["fondo"] == 0  ? "ninguno" : datos["fondo"]
    datos["coleccion"]= datos["coleccion"] == 0  ? "ninguno" : datos["coleccion"]
 
    data.append('pagina',datos["pagina"] || "ninguno")
    data.append('fondo', datos["fondo"] || "ninguno" )
    data.append('coleccion',datos["coleccion"] || "ninguno")
    data.append('palabra', (datos["palabra"].length == 0) ? "ninguno": datos["palabra"]) 
    data.append('generadores',(datos["generadores"].length == 0) ? "ninguno": datos["generadores"])
    data.append('personajes',(datos["personajes"].length == 0) ? "ninguno": datos["personajes"])
    data.append('toponimia',(datos["toponimia"].length == 0) ? "ninguno": datos["toponimia"])
    data.append('idioma',(datos["idioma"].length == 0) ? "ninguno": datos["idioma"])
    data.append('material_soporte',(datos["material_soporte"].length == 0) ? "ninguno": datos["material_soporte"])
    data.append('material_documento',(datos["material_documento"].length == 0) ? "ninguno": datos["material_documento"])
    data.append('anosCriticos', (datos["anos"].length == 0) ? "ninguno": datos["anos"]) 
    data.append('palabrasClaves', (datos["palabras_claves"].length == 0) ? "ninguno": datos["palabras_claves"]) 
    data.append('accion',""); 

    totalAnios = $("#inputFechaEspecifica").val().length
    totalPalabrasClaves = $("#inputPalabrasClaves").val().length
      
    $.ajax({
      url: 'filtrar_documentos',  
      type: 'POST',
      data: data,
      cache: false,
      contentType: false,
      processData: false,
      beforeSend: function(){
        $('.resultados_finales').remove()
        query(".mensajeNoEncontrado").innerHTML = ""
        contenido.nextElementSibling != null ?  contenido.nextElementSibling.remove() : ''
        query(".busquedaResultado").innerHTML = 'Buscando <img style="width:1.3em;margin:0" src="src/lector/imagenes/cargando.gif">'  
        query(".contenedorMensaje").innerHTML = "Cargando documentos históricos"
        query(".contenedorSpiner").src = "src/lector/imagenes/cargando2.gif"
        query(".fichaBusqueda").src = "src/lector/imagenes/carpeta.png"
        query('#paginacion').style.display = "none"
      },
      success: function(dato){
        try{
          let data = JSON.parse(dato)
          query(".contenedorMensaje").innerHTML = ""
          query(".fichaBusqueda").src = ""
          query(".contenedorSpiner").src = ""
          //console.log(data.consulta)
          if(data.estado){
            if (data.contenido !== null) {
              let totalItems = data.total
              let mensaje = ''

              if(totalItems != 1){
                  mensaje = `<b>Página ${datos['pagina']== undefined?'1':datos['pagina']}</b> con ${data.total_paginas} de ${totalItems} documentos históricos encontrados.`
              }else mensaje = `${totalItems} documento histórico encontrado.`

              contenido.insertAdjacentHTML('afterend', data.contenido)
              query(".busquedaResultado").innerHTML = mensaje  
              query('#paginacion').style.display = "block"
              $('#paginacion').pagination('updateItems', totalItems)
            }else{   
              $('#paginacion').pagination('destroy');
              query(".busquedaResultado").innerHTML = `No se encontraron documentos históricos.` 
              query(".mensajeNoEncontrado").innerHTML = ` 
                                             <img style="/*! width:1.3em; *//*! margin:0 */width: 5em;margin-bottom: 2em;" src="src/lector/imagenes/404.png">
                                           <h5>No se encontraron resultados coincidentes.</h5>`
            }
          }else{
            query(".busquedaResultado").innerHTML = `${data.observacion}` 
            query(".mensajeNoEncontrado").innerHTML = ` 
                                             <img style="/*! width:1.3em; *//*! margin:0 */width: 5em;margin-bottom: 2em;" src="src/lector/imagenes/404.png">
                                           <h5>${data.observacion}</h5>`
          }
        } catch(e){
          query(".contenedorMensaje").innerHTML = ""
          query(".fichaBusqueda").src = ""
          query(".contenedorSpiner").src = ""
          $('#paginacion').pagination('destroy');
          query(".busquedaResultado").innerHTML = `Ocurrió un error al realizar la búsqueda.` 
          query(".mensajeNoEncontrado").innerHTML = ` 
                                         <img style="/*! width:1.3em; *//*! margin:0 */width: 5em;margin-bottom: 2em;" src="src/lector/imagenes/404.png">
                                       <h5>Error de búsqueda.</h5>`
        }       
      },
      error: function(error){
          query(".fichaBusqueda").src = ""
          query(".contenedorSpiner").src = ""
          query(".contenedorMensaje").innerHTML = ""
          query(".busquedaResultado").innerHTML = "No se cargaron los documentos históricos."
          query(".mensajeNoEncontrado").innerHTML = `
                                             <img style="width: 7em;margin-bottom: 2em;" src="src/lector/imagenes/problema.png">
                                            <h5>No se pudo realizar la búsqueda</h5>`
      }
  });      
}

function DocumentoMasVistos(){
    let datos = new FormData();
    let contenido = query('#documentosAcordeon ol')
    datos.append('accion',"")
    $.ajax({
      url: 'mostrar_documentos_relevantes',  
      type: 'POST',
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      beforeSend: function(){
        $('.resultados_finales').remove()
        query(".mensajeNoEncontrado").innerHTML = ""
        contenido.nextElementSibling != null ?  contenido.nextElementSibling.remove() : ''
        query(".busquedaResultado").innerHTML = 'Cargando <img style="width:1.3em;margin:0" src="src/lector/imagenes/cargando.gif">'  
        query(".contenedorMensaje").innerHTML = "Cargando documentos históricos más relevantes"
        query(".contenedorSpiner").src = "src/lector/imagenes/cargando2.gif"
        query(".fichaBusqueda").src = "src/lector/imagenes/carpeta.png"
        query('#paginacion').style.display = "none"
      },
      success: function(dato){
        let data = JSON.parse(dato)
        query(".contenedorMensaje").innerHTML = ""
        query(".fichaBusqueda").src = ""
        query(".contenedorSpiner").src = ""
        if(data.estado){
          if(data.contenido !== null) {
            contenido.insertAdjacentHTML('afterend', data.contenido)
            query(".busquedaResultado").innerHTML = `Documentos históricos relevantes.`  
          }else{   
            query(".busquedaResultado").innerHTML = `No se encontraron documentos históricos relevantes.` 
            query(".mensajeNoEncontrado").innerHTML = ` 
                                           <img style="/*! width:1.3em; *//*! margin:0 */width: 5em;margin-bottom: 2em;" src="src/lector/imagenes/404.png">
                                         <h5>No se encontraron resultados coincidentes.</h5>`
          }
        }else{
          query(".busquedaResultado").innerHTML = `${data.observacion}` 
          query(".mensajeNoEncontrado").innerHTML = ` 
                                           <img style="/*! width:1.3em; *//*! margin:0 */width: 5em;margin-bottom: 2em;" src="src/lector/imagenes/404.png">
                                         <h5>${data.observacion}</h5>`
        }
      },
      error: function(error){
          query(".fichaBusqueda").src = ""
          query(".contenedorSpiner").src = ""
          query(".contenedorMensaje").innerHTML = ""
          query(".busquedaResultado").innerHTML = "No se cargaron los documentos históricos más relevantes."
          query(".mensajeNoEncontrado").innerHTML = `
                                             <img style="width: 7em;margin-bottom: 2em;" src="src/lector/imagenes/problema.png">
                                            <h5>No se pudo realizar la visualización</h5>`
      }
  });      
}


//**** PAGINACION DE LOS RESULTADOS DE BUSQUEDA*****//
$('#paginacion').pagination({
  items: totalItems,
  itemsOnPage: 5,
  cssStyle: 'light-theme', 
  prevText: 'Anterior',
  nextText: 'Siguiente',
  onPageClick(){
    pagina = $('#paginacion').pagination('getCurrentPage')
    busquedaDocumentos(actualizarBusqueda(true))
  }
});
