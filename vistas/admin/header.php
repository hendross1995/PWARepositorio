<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Repositorio digital, Archivo Histórico CCA</title>
    <link href="src/admin/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="src/admin/vendor/bootstrap-select/dist/css/bootstrap-select.css">
    <link rel="stylesheet" href="src/admin/vendor/bootstrap-tags/dist/bootstrap-tagsinput.css">
    <link href="src/admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="src/admin/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="src/admin/css/sb-admin.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="src/admin/css/adicional_admin.css">
    <link rel="stylesheet" href="src/inicial/css/estilosGlobales.css">
  </head>

  <body id="page-top">
    <nav class="navbar navbar-expand navbar-dark bg-dark static-top">
      <a class="navbar-brand mr-1" href="/">ARCHIVOS CCA</a>
      <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
        <i class="fas fa-bars"></i>
      </button>
      <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0"></form>
      <ul class="navbar-nav ml-auto ml-md-0">
        <li class="nav-item dropdown no-arrow">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php echo $_SESSION['usuario']; ?><i class="fas fa-user-circle fa-fw"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
            <a class="dropdown-item" href="perfil">Mi perfil</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">Cerrar sesión</a>
          </div>
        </li>
      </ul>
      
    </nav>

    <div id="wrapper">

      <!-- Sidebar -->
      <ul class="sidebar navbar-nav">
        <li class="nav-item Inicio">
          <a class="nav-link" href="/">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Inicio</span>
          </a>
        </li>
        <?php if($_SESSION['rol'] == 'ADMINISTRADOR'){ ?>
          <li class="nav-item dropdown GestionDocumental">
            <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-fw fa-file-alt"></i>
              <span>Gestión documental</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="pagesDropdown" id="menu2">
              <a class="dropdown-item documentos" href="documentos">Documentos históricos</a>
              <a class="dropdown-item pendientes" href="pendientes">Pendientes</a>
              <a class="dropdown-item favoritos" href="favoritos">Favoritos</a>
              <a class="dropdown-item lectores" href="lectores">Lectores</a>
            </div>
          </li>
          <li class="nav-item dropdown Mantenimiento">
            <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-fw fa-id-card-alt"></i>
              <span>Mantenimiento</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="pagesDropdown" id="menu2">
              <a class="dropdown-item usuarios" href="usuarios">Usuarios</a>
              <a class="dropdown-item personajesgeneradores" href="personajesgeneradores">Personajes y generadores</a>
            </div>
          </li>
          <li class="nav-item dropdown Configuraciones">
            <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-fw fa-cogs"></i>
              <span>Configuraciones</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="pagesDropdown" id="menu2">
              <a class="dropdown-item fondos" href="fondos">Fondos</a>
              <a class="dropdown-item colecciones" href="colecciones">Colecciones</a>
              <a class="dropdown-item materialesdocumentales" href="materialesdocumentales">Materiales documentales</a>
              <a class="dropdown-item materialessoporte" href="materialessoporte">Materiales soporte</a>
              <a class="dropdown-item archivos" href="archivos">Archivos</a>
              <a class="dropdown-item secciones" href="secciones">Secciones</a>
              <a class="dropdown-item niveles" href="niveles">Niveles</a>
              <a class="dropdown-item contenedores" href="contenedores">Contenedores</a>
              <a class="dropdown-item idiomas" href="idiomas">Idiomas</a>
              <a class="dropdown-item toponimia" href="toponimia">Toponimia</a>
              <a class="dropdown-item lineasinteres" href="lineasinteres">Línes de interés</a>
            </div>
          </li>
          <?php }elseif($_SESSION['rol'] == 'DOCUMENTALISTA'){ ?>
            <li class="nav-item dropdown GestionDocumental">
              <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-fw fa-file-alt"></i>
                <span>Gestión documental</span>
              </a>
              <div class="dropdown-menu" aria-labelledby="pagesDropdown" id="menu2">
                <a class="dropdown-item documentos" href="documentos">Documentos históricos</a>
              </div>
            </li>
          <?php } ?>
      </ul>
      <div id="content-wrapper">
      <script src="src/admin/vendor/jquery/jquery.min.js"></script>