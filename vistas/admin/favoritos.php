<div class="container-fluid">
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">Gestión documental</a>
    </li>
    <li class="breadcrumb-item active">Favoritos</li>
  </ol>
  <div class="card mb-3">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered tabla_favoritos datatables" id="tabla_favoritos" width="100%" cellspacing="0">
          <thead>  
            <tr>
              <th style="text-align: center;">#</th>
              <th>Ficha</th>
              <th>Nombre del documento</th>
              <th>Total favoritos</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$('.GestionDocumental,.favoritos').addClass('active');
$('.GestionDocumental,.GestionDocumental > div').addClass('show');
</script>
<script type="module" src="app/favoritosLector.js"></script>