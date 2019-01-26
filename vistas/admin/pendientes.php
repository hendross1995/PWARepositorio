<div class="container-fluid">
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">Gestión documental</a>
    </li>
    <li class="breadcrumb-item active">Pendientes</li>
  </ol>
  <div class="card mb-3">
    <div class="card-header">Lista de documentos pendientes</div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered tabla_pendientes datatables" id="tabla_pendientes" width="100%" cellspacing="0">
          <thead>  
            <tr>
              <th style="text-align: center;">#</th>
              <th>Ficha</th>
              <th>Nombre</th>
              <th>Usuario creador</th>
              <th style="text-align: center;">Estado</th>
              <th style="text-align: center;">Acción</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="frm_ver_pendientes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document" data-keyboard="false" data-backdrop="static">
    <div class="modal-content" style="width: 103% !important;">
      <div class="modal-header">
        <h5 class="modal-title titulo_documento" id="exampleModalLabel">Ver detalles del documento</h5>
        <small class="texto-pequeno">Los campos con * son obligatorios.</small>
        <!--<button class="close FrmVerDocumento" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>-->
      </div> 
      <div class="modal-body"> 
        <form autocomplete="Off" id="FrmVerDocumento" onsubmit="return false"></form> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default boton_cancelar_actualizar_documento" data-dismiss="modal">SALIR</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="frm_aprobar_ficha" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content" style="width: 103%;">
      <div class="modal-header">
        <h4 class="modal-title">Aprobar ficha técnica</h4>
          <button  type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <span>¿Seguro deseas aprobar esta ficha técnica?</span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="cancelar_aprobar">CANCELAR</button>
        <button type="button" class="btn btn-primary" id="aprobar">APROBAR</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="frm_reprobar_ficha" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="width: 103%;">
      <div class="modal-header">
        <h4 class="modal-title">Reprobar ficha técnica</h4>
          <button  type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <span>¿Seguro deseas reprobar esta ficha técnica?</span>
            <div class="form-group" style="margin-top: 10px;">
              <div class="form-row">
                <div class="col-md-12"> 
                  <textarea class="form-control" name="observaciones_pendiente" id="observaciones_pendiente" placeholder="Alguna observación (opcional)." rows="3"></textarea>
                </div> 
              </div>
            </div> 
          </div> 
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="cancelar_reprobar">CANCELAR</button>
        <button type="button" class="btn btn-primary" id="reprobar">REPROBAR</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$('.GestionDocumental,.pendientes').addClass('active');
$('.GestionDocumental,.GestionDocumental > div').addClass('show');
</script>
<script type="module" src="app/pendientes.js"></script>
