
import {query,avisoFormulario,PWA} from './funcionesGlobales.js';

window.addEventListener('load',iniciarEventos,true)

function iniciarEventos() {
  query('#documentosAcordeon').addEventListener('click',botonesFavorVerDocum)
  PWA()
}

var archivos = [];
var total_paginas = 0;

function botonesFavorVerDocum(e){
  let datos = new FormData();
  var clase = e.target.classList;
  let btn = e.target
  if (clase.contains('fa-star') || clase.contains('fa-star-o')) btn = e.target.parentElement
  if (clase.contains('fa-bookmark') || clase.contains('fa-bookmark-o')) btn = e.target.parentElement
    
  if(btn.classList.contains('btn-favorito') || clase.contains("fa-star") || clase.contains("fa-star-o")){
    
    let favorito = btn.getAttribute('es-favorito')?true:false;
    datos.append("iddocumento",btn.getAttribute('id'))
    datos.append("accion_favorito",favorito)
    $(btn).prop('disabled',true).html(favorito?'<i class="fa fa-star"></i> QUITANDO...':'<i class="fa fa-star-o"></i> AGREGANDO...')
    fetch('actualizar_favoritos',{
      method: 'POST',
      body: datos}) 
    .then(response => response.json())
    .then(data => {
      if(data.estado){
        $(btn).prop('disabled',false).html(data.accion=='quitado'?'<i class="fa fa-star-o"></i> AGREGAR A FAVORITOS':'<i class="fa fa-star"></i> QUITAR DE FAVORITOS')
        btn.setAttribute('es-favorito',(data.accion=='quitado'?'':'1'))
        avisoFormulario({aviso: "actualizarFavoritos",mensaje: data.observacion,alerta:""})
      }else{
        $(btn).prop('disabled',false).html(!favorito?'<i class="fa fa-star-o"></i> AGREGAR A FAVORITOS':'<i class="fa fa-star"></i> QUITAR DE FAVORITOS')
        avisoFormulario({aviso: "actualizarFavoritos", mensaje: data.observacion,alerta:"text-warning"})
      }
     })
    .catch(error => { 
      avisoFormulario({aviso: "errorServidor",alerta:"text-danger"})
      $(btn).prop('disabled',false).html(!favorito?'<i class="fa fa-star-o"></i> AGREGAR A FAVORITOS':'<i class="fa fa-star"></i> QUITAR DE FAVORITOS')
    });

  }else if(btn.classList.contains('btnVerDocumento') || clase.contains("fa-bookmark") || clase.contains("fa-bookmark-o")){
    
    let datos = new FormData();
    datos.append("iddocumento",btn.getAttribute('id'))
    $(btn).prop('disabled',true).html('<i class="fa fa-bookmark"></i> VISUALIZANDO...')
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
        $(btn).prop('disabled',false).html('<i class="fa fa-bookmark"></i> VISUALIZAR DOCUMENTO')
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
      $(btn).prop('disabled',false).html('<i class="fa fa-bookmark"></i> VISUALIZAR DOCUMENTO')
    });

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