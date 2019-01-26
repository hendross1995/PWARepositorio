<div class="container-fluid">
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">Gestión documental</a>
    </li>
    <li class="breadcrumb-item active">Lectores</li>
  </ol>
  <div class="card mb-3">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered tabla_usuarios_lectores datatables" id="tabla_usuarios_lectores" width="100%" cellspacing="0">
          <thead>  
            <tr>
              <th></th>
              <th style="text-align: center;">#</th>
              <th>Nombres</th>
              <th>Usuario</th>
              <th>Género</th>
              <th>Fecha creación</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$('.GestionDocumental,.lectores').addClass('active');
$('.GestionDocumental,.GestionDocumental > div').addClass('show');
</script>
<script type="module" src="app/lectores.js"></script>