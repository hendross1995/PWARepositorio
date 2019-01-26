<div class="container">
  <div class="card mb-3">
    <div class="card-header">Mis documentos históricos favoritos</div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered tabla_favoritos datatables" id="tabla_favoritos" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th></th>
              <th style="text-align: center;">#</th>
              <th>Documento</th>
              <th style="text-align: center;">Acción</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $('.favoritos_lector').css({'background':'#e9ecef'});
  $('.inicio_lector').css({'background':'#f7f7f7'});
</script>
<script type="module" src="app/favoritos.js"></script>
