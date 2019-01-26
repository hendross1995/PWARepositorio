<div class="container-fluid">
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">Inicio</a>
    </li>
    <li class="breadcrumb-item active">Visión general</li>
  </ol>
  <div class="row">
    <div class="col-xl-3 col-sm-6 mb-3">
      <div class="card text-white btn-primary o-hidden h-100">
        <div class="card-body">
          <div class="card-body-icon">
            <i class="fas fa-fw fa-users"></i>
          </div>
          <div class="mr-5">Lectores</div><br><div class="__a cantidad_usuarios"></div>
        </div>
        <a class="card-footer text-white clearfix small z-1" href="lectores">
          <span class="float-left">Ver detalles</span>
          <span class="float-right">
            <i class="fas fa-angle-right"></i>
          </span>
        </a>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-3">
      <div class="card text-white bg-warning o-hidden h-100">
        <div class="card-body">
          <div class="card-body-icon">
            <i class="fas fa-fw fa-star"></i>
          </div>
          <div class="mr-5">Documentos Favoritos</div><br><div class="__a cantidad_favoritos"></div>
        </div>
        <a class="card-footer text-white clearfix small z-1" href="favoritos">
          <span class="float-left">Ver detalles</span>
          <span class="float-right">
            <i class="fas fa-angle-right"></i>
          </span>
        </a>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-3">
      <div class="card text-white bg-success o-hidden h-100">
        <div class="card-body">
          <div class="card-body-icon">
            <i class="fas fa-fw fa-book"></i>
          </div>
          <div class="mr-5">Documentos publicados</div><br><div class="__a cantidad_documentos"></div>
        </div>
        <a class="card-footer text-white clearfix small z-1" href="documentos">
          <span class="float-left">Ver detalles</span>
          <span class="float-right">
            <i class="fas fa-angle-right"></i>
          </span>
        </a>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-3">
      <div class="card text-white bg-danger o-hidden h-100">
        <div class="card-body">
          <div class="card-body-icon">
            <i class="fas fa-fw fa-folder-open"></i>
          </div>
          <div class="mr-5">Documentos pendientes</div><br><div class="__a cantidad_pendientes"></div>
        </div>
        <a class="card-footer text-white clearfix small z-1" href="pendientes">
          <span class="float-left">Ver detalles</span>
          <span class="float-right">
            <i class="fas fa-angle-right"></i>
          </span>
        </a>
      </div>
    </div>
  </div>
  <div class="card mb-3">
    <div class="card-header">
      <i class="fas fa-chart-area"></i>
      Últimos 10 documentos históricos más vistos</div>
    <div class="card-body">
      <canvas id="myAreaChart" width="100%" height="30"></canvas>
    </div>
    <div class="card-footer small text-muted">Los gráficos se muestran a la información existente del mes actual.</div>
  </div>
</div>
<script type="text/javascript">
  $('.Inicio').addClass('active');
</script>
<script type="module" src="app/inicioAdministrador.js"></script>
<script src="src/admin/vendor/chart.js/Chart.min.js"></script>
  