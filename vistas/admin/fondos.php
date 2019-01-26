<div class="container-fluid">
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">Configuraciones</a>
    </li>
    <li class="breadcrumb-item active">Fondos</li>
  </ol>
  <div class="card mb-3">
    <div class="card-header">
      Lista de fondos documentales <button class="btn btn-default btns-registros pull-right boton_registrar_fondo" data-toggle="modal" data-target="#frm_actualizar_fondos">Nuevo</button></div>
    <div class="card-body">
      <div class="table-responsive">
        <!--dataTable id en la tabla fondos--> 
        <table class="table table-bordered tabla_fondos datatables" id="tabla_fondos" width="100%" cellspacing="0">
          <thead>  
            <tr>
              <th style="text-align: center;">#</th>
              <th>Nombre</th>
              <th>Descripción</th>
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
<div class="modal fade" id="frm_actualizar_fondos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" role="document" data-keyboard="false" data-backdrop="static">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title titulo_fondo" id="exampleModalLabel"></h5>
        <small class="texto-pequeno">Los campos con * son obligatorios.</small>
        <!--<button class="close FrmActualizarFondo" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>-->
      </div> 
      <div class="modal-body"> 
       <form autocomplete="Off" id="FrmActualizarFondo" onsubmit="return false">
          <div class="form-group">
            <div class="form-row"> 
              <div class="col-md-12">
                <div class="form-label-group"> 
                  <input type="text" id="nombre_fondo" name="nombre_fondo" class="form-control campo" placeholder="Nombre*"  autofocus="autofocus">
                  <label for="nombre_fondo">Nombre*</label> 
                </div>
              </div>
            </div> 
          </div>
          <div class="form-group">
            <div class="form-row">
              <div class="col-md-12"> 
                <textarea class="form-control" name="descripcion_fondo" id="descripcion_fondo" placeholder="Descripción" rows="3"></textarea>
              </div> 
            </div>
          </div> 
          <div class="form-group">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input estado_fondo" id="estado_fondo" name="estado_fondo">   
              <label class="custom-control-label" for="estado_fondo">Estado</label>
            </div>
          </div>
        </form> 
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-default boton_cancelar_actualizar_fondo" data-dismiss="modal">CANCELAR</button>
          <button type="button" class="btn btn-primary actualizar_fondo" id="actualizar_fondo"></button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $('.Configuraciones,.fondos').addClass('active');
  $('.Configuraciones,.Configuraciones > div').addClass('show');
</script>
<script type="module" src="app/fondos.js"></script>