<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Repositorio digital, Archivo Histórico CCA</title>
    <link rel="stylesheet" href="src/lector/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="src/lector/css/bootstrap.min.css">
    <link href="src/lector/css/mdb.min.css" rel="stylesheet">
    <link rel="stylesheet" href="src/lector/css/my.css">
    <link rel="stylesheet" type="text/css" href="src/lector/css/simplePagination.css">
    <link rel="stylesheet" href="src/inicial/css/estilosGlobales.css">
    <link rel="stylesheet" href="src/admin/vendor/bootstrap-tags/dist/bootstrap-tagsinput.css">
    <link rel="stylesheet" href="src/lector/plugins/flipbook/css/lightbox.css">
    <link rel="stylesheet" href="src/lector/plugins/flipbook/css/font-awesome.min.css">
    
    <link rel="manifest" href="./manifest.json"> 
  <meta name="theme-color" content="#F7DF1E">
  <!-- Detección de Icono de PWA -->
  <link rel="icon" type="image/png" sizes="16x16" href="./img/icon_16x16.png">
  <link rel="icon" type="image/png" sizes="32x32" href="./img/icon_32x32.png">
  <link rel="icon" type="image/png" sizes="64x64" href="./img/icon_64x64.png">
  <link rel="icon" type="image/png" sizes="96x96" href="./img/icon_96x96.png">
  <link rel="icon" type="image/png" sizes="128x128" href="./img/icon_128x128.png">
  <link rel="icon" type="image/png" sizes="192x192" href="./img/icon_192x192.png">
  <link rel="icon" type="image/png" sizes="256x256" href="./img/icon_256x256.png">
  <link rel="icon" type="image/png" sizes="384x384" href="./img/icon_384x384.png">
  <link rel="icon" type="image/png" sizes="512x512" href="./img/icon_512x512.png">
  <link rel="icon" type="image/png" sizes="1024x1024" href="./img/icon_1024x1024.png">
  <!-- Metatags iOS -->
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <meta name="format-detection" content="telephone=no">
  <meta name="apple-mobile-web-app-title" content="PWA Demo">
  <link rel="apple-touch-icon" sizes="192x192" href="./img/icon_192x192.png">
  <!-- Metatags Windows -->
  <meta name="msapplication-TileColor" content="#F7DF1E">
  <meta name="msapplication-TileImage" content="./img/icon_192x192.png">
  <!-- Otros Metatags -->
  <meta property="og:title" content="PWA Demo">
  <meta property="og:locale" content="es_MX">
  <meta property="og:type" content="website">
  <meta property="og:image" content="./img/icon_128x128.png">
  <meta property="og:site_name" content="EDteam">
  <meta property="og:url" content="https://pwa.ed.team">
  
    
  </head>

  <body class="fondo-body">

  <header>
      <nav class="navbar navbar2 fixed-top navbar-expand-lg navbar-light white scrolling-navbar" id="navPrincipal" style="padding: .5rem 1rem;">
        <div class="container">
          <a class="titulo-nav navbar-brand waves-effect">
              <span class="white-text">Archivo</span><strong class="white-text"> Histórico</strong>
          </a>

          <!-- Collapse -->
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsePrincipalPrimario" aria-controls="collapsePrincipalPrimario"
              aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
          </button>

          <!-- Links -->
          <div class="collapse navbar-collapse" id="collapsePrincipalPrimario">
            <ul class="navbar-nav mr-auto"></ul>
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-danger dropdown-toggle waves-effect" type="button" id="dropdownMenu1" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false"><span class="text-white" style="text-transform: none;font-size: 11px;"><?php echo $_SESSION['usuario']; ?><i class="fa fa-user-circle fa-fw"></i></span></button>
              <div class="dropdown-menu dropdown-danger">
                <a class="dropdown-item" href="perfil"><i class="fa fa-user"></i> Mi perfil</a>
                <a class="dropdown-item quitar_favorito_documento" href="salir"><i class="fa fa-sign-out"></i> Cerrar sesión</a>
              </div>
            </div>
          </div>
        </div>
      </nav>
    <nav id="navSecundario" class="navbar navbar-expand-lg navbar-dark" style="background: #f7f7f7;">
      <div class="container">
      <a class="inicio_lector navbar-brand black-text btn btn-sm waves-effect" href="/" title="inicio"><i class="fa fa-home"></i> Inicio</a>
      <a class="favoritos_lector navbar-brand black-text btn btn-sm waves-effect" href="favoritos" title="favoritos"><i class="fa fa-star"></i> Favoritos</a>
      <div class="collapse navbar-collapse" id="collapseNavbarSecundario"></div>
      </div>
    </nav>
  </header>
  <script src="src/lector/js/jquery-3.3.1.min.js"></script>
  <script src="src/lector/js/popper.min.js"></script>
  <script src="src/lector/js/bootstrap.min.js"></script>
  <script src="src/lector/js/bootstrap-select.min.js"></script>
  <script src="src/lector/js/mdb.min.js"></script>


 
 
  