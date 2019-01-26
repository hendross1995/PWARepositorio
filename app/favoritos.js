import { erroresFormulario,camposVaciosFormularios,
        query, exitoFormulario,TablasDatos,removerClassErrorForm,avisoFormulario} from './funcionesGlobales.js';

window.addEventListener('load',iniciarEventos,true)

var tabla;
var accionFavorito = null
var idUsuario = null
var archivos = [];
var total_paginas = 0;

function iniciarEventos(){ 
  tabla = imprimirTablaFavoritos("")
  let favoritos_opciones = query(".tabla_favoritos")
  favoritos_opciones.addEventListener('click',verFavoritosOpciones)
}

function verFavoritosOpciones(e){
  var clase = e.target.classList;
  var boton = e.target
  //console.log(boton.parentElement.find('button'))
  if (clase.contains("fa")) boton = e.target.parentElement
  if (clase.contains("boton_ver_documento") || clase.contains("fa-bookmark")){
    let datos = new FormData();
    datos.append("iddocumento",boton.id)
    $(boton).prop('disabled',true).html('<i class="fa fa-bookmark"></i> Visualizando...')
    archivos = [];
    total_paginas = 0;
    fetch('cargar_libro',{
      method: 'POST',
      body: datos}) 
    .then(response => response.json())
    .then(data => {
      if(data.estado){
        archivos = data.datos.paginas;
        total_paginas = data.datos.total_paginas
        $(boton).prop('disabled',false).html('<i class="fa fa-bookmark"></i> Visualizar documento')
        if(data.datos.tipo == 'pdf'){
          instance.options = booksOptions['book1'];
          instance.options.controlsProps.downloadURL = CallbackPdf(1)
          instance.options.pdf = CallbackPdf(1);
        }else{
          instance.options = booksOptions['book2'];
          instance.options.pages = total_paginas
        }
        $('.fb3d-modal').fb3dModal('show');
      }else{
        $('#modalDocumentoDetalle').modal('hide')
        avisoFormulario({aviso: "cargarLibro", mensaje: data.observacion,alerta:"text-warning"})
      }
     }).catch(error => { 
      avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
      $(boton).prop('disabled',false).html('<i class="fa fa-bookmark"></i> Visualizar documento')
    });
  }else if (clase.contains("quitar_favorito_documento" ) || clase.contains("fa-star")){
    let datos = new FormData();
    let favorito = true;
    datos.append("iddocumento",boton.id)
    datos.append("accion_favorito",true)
    $(boton).prop('disabled',true).html('<i class="fa fa-star"></i> Quitando...')
    fetch('actualizar_favoritos',{
      method: 'POST',
      body: datos}) 
    .then(response => response.json())
    .then(data => {
      $(boton).prop('disabled',false).html('<i class="fa fa-star"></i> Quitar de favoritos')
      if(data.estado){
        avisoFormulario({aviso: "actualizarFavoritos",mensaje: data.observacion,alerta:""})
        tabla.ajax.reload();
      }else{
        avisoFormulario({aviso: "actualizarFavoritos", mensaje: data.observacion,alerta:"text-warning"})
      }
     })
    .catch(error => { 
      avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
      $(boton).prop('disabled',false).html('<i class="fa fa-star"></i> Quitar de favoritos')
    });
  }else if (clase.contains("imprimir_documento") || clase.contains("fa-print") ){
      console.log("imprimir:"+boton.id)
  }  
}

var $ = window.jQuery;
var styleClb = function() {
  $('.fb3d-modal').removeClass('light').addClass('dark');
}, booksOptions = {
  book1: {
    pdf: undefined,
    controlsProps: { 
      //cmdFastForward: 10,
      downloadURL: undefined
    },
    propertiesCallback: function(props) {
      props.page.depth /= 2;
      return props;
    },
    template: {
      html: 'src/lector/plugins/flipbook/templates/default-book-view.html',
      styles: [
        'src/lector/plugins/flipbook/css/short-white-book-view.css'
      ],
      links: [{
        rel: 'stylesheet',
        href: 'src/lector/plugins/flipbook/css/font-awesome.min.css'
      }],
      script: 'src/lector/plugins/flipbook/js/default-book-view.js',
      sounds: {
        startFlip: 'src/lector/plugins/flipbook/sounds/start-flip.mp3',
        endFlip: 'src/lector/plugins/flipbook/sounds/end-flip.mp3'
      }
    },
    styleClb: styleClb
  },
  book2: {
    pageCallback: CallbackImage,
    pages: total_paginas,
    propertiesCallback: function(props) {
      props.cover.color = 0x000000;
      return props;
    },
    template: {
      html: 'src/lector/plugins/flipbook/templates/default-book-view.html',
      styles: [
        'src/lector/plugins/flipbook/css/font-awesome.min.css',
        'src/lector/plugins/flipbook/css/short-white-book-view.css'
      ],
      script: 'src/lector/plugins/flipbook/js/default-book-view.js',
      sounds: {
        startFlip: 'src/lector/plugins/flipbook/sounds/start-flip.mp3',
        endFlip: 'src/lector/plugins/flipbook/sounds/end-flip.mp3'
      }
    },
    styleClb: styleClb
  },
};

var instance = {
  scene: 1,
  options: undefined,
  node: $('.fb3d-modal .mount-container')
};

var modal = $('.fb3d-modal');
modal.on('fb3d.modal.hide', function() {
  instance.scene.dispose();
});
modal.on('fb3d.modal.show', function() {
  instance.scene = instance.node.FlipBook(instance.options);
  instance.options.styleClb();
});

function CallbackImage(n) {
  if(archivos[n]){
    return {
      type: 'image',
      src: archivos[n],
      interactive: true
    };
  }else{
    return {
      type: 'blank',
    };
  }
}

function CallbackPdf(n) {
  if(archivos[n]){
    return archivos[n]
  }else{
    return undefined
  }
}
//******************************************//

function imprimirTablaFavoritos(vacio){
 return TablasDatos({
  tabla  : 'tabla_favoritos',
  url    : 'mostrar_favoritos',
  data   : {id : vacio},
  columnas :  [ 
                {
                  className      : 'details-control centro sombra',
                  defaultContent : '<i class="fa fa-angle-right desglosar_menu_tabla"></i>'
                },
      
                { data: 'num', sClass: 'centro'},
                { data: 'nombre'},
                { data: 'accion', sClass: 'centro'},
                
                { data: 'nombre_sugerido', sClass: 'oculto'},
                { data: 'fondo', sClass: 'oculto'}, 
                { data: 'coleccion', sClass: 'oculto'},
                { data: 'generadores', sClass: 'oculto'},
                { data: 'asunto_tema', sClass: 'oculto'}, 
                { data: 'lugar_emision', sClass: 'oculto'}, 
                { data: 'toponimia', sClass: 'oculto'}, 
                { data: 'personajes', sClass: 'oculto'}, 
                { data: 'idiomas', sClass: 'oculto'}, 
                { data: 'material_soporte', sClass: 'oculto'}, 
                { data: 'materiales_documentos', sClass: 'oculto'},
                { data: 'anios_criticos', sClass: 'oculto'},
                { data: 'palabras_claves', sClass: 'oculto'},
                { data: 'descripcion', sClass: 'oculto'},
                { data: 'transcripcion', sClass: 'oculto'},
              ],
  });
}

$('#tabla_favoritos tbody').on('click', 'td.details-control', function () {
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
        '<tr><td><b>Nombre sugerido:</b></td><td>'+(d.nombre_sugerido == '' || d.nombre_sugerido == null ?'No registrado': d.nombre_sugerido)+'</td></tr>'+
        '<tr><td><b>Fondo:</b></td><td>'+(d.fondo == '' || d.fondo == null ?'No registrado': d.fondo)+'</td></tr>'+
        '<tr><td><b>Colección:</b></td><td>'+(d.coleccion == '' || d.coleccion == null ?'No registrado': d.coleccion)+'</td></tr>'+
        '<tr><td><b>Asunto / Tema:</b></td><td>'+(d.asunto_tema == '' || d.asunto_tema == null ?'No registrado': d.asunto_tema)+'</td></tr>'+
        '<tr><td><b>Lugar emisión:</b></td><td>'+(d.lugar_emision == '' || d.lugar_emision == null ?'No registrado': d.lugar_emision)+'</td></tr>'+
        '<tr><td><b>Toponimia:</b></td><td>'+(d.toponimia == '' || d.toponimia == null ?'No registrado': d.toponimia)+'</td></tr>'+
        '<tr><td><b>Generadores:</b></td><td>'+(d.generadores == '' || d.generadores == null ?'No registrado': d.generadores)+'</td></tr>'+
        '<tr><td><b>Personajes:</b></td><td>'+(d.personajes == '' || d.personajes == null ?'No registrado': d.personajes)+'</td></tr>'+
        '<tr><td><b>Idiomas:</b></td><td>'+(d.idiomas == '' || d.idiomas == null ?'No registrado': d.idiomas)+'</td></tr>'+
        '<tr><td><b>Material de soporte:</b></td><td>'+(d.material_soporte == '' || d.material_soporte == null ?'No registrado': d.material_soporte)+'</td></tr>'+
        '<tr><td><b>Material del documento:</b></td><td>'+(d.materiales_documentos == '' || d.materiales_documentos == null ?'No registrado': d.materiales_documentos)+'</td></tr>'+
        '<tr><td><b>Años críticos:</b></td><td>'+(d.anios_criticos == '' || d.anios_criticos == null ?'No registrado': d.anios_criticos)+'</td></tr>'+
        '<tr><td><b>Palabras claves:</b></td><td>'+(d.palabras_claves == '' || d.palabras_claves == null ?'No registrado': d.palabras_claves)+'</td></tr>'+
        '<tr><td><b>Descripción:</b></td><td>'+(d.descripcion == '' || d.descripcion == null ?'No registrado': d.descripcion)+'</td></tr>'+
        '<tr><td><b>Transcripción:</b></td><td>'+(d.transcripcion == '' || d.transcripcion == null ?'No registrado': d.transcripcion)+'</td></tr>'
    '</table>';
}
  
function quitarError(e){
  if(e.target.classList.contains("errorForm")){
      e.target.classList.remove("errorForm")
  }
}
