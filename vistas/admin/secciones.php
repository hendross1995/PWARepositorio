<div class="container-fluid">
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">Configuraciones</a>
    </li>
    <li class="breadcrumb-item active">Secciones</li>
  </ol>
  <div class="card mb-3">
    <div class="card-header">
      Lista de secciones <button class="btn btn-default btns-registros pull-right boton_registrar_seccion" data-toggle="modal" data-target="#frm_actualizar_secciones">Nuevo</button></div>
    <div class="card-body">
      <div class="table-responsive">
        <!--dataTable id en la tabla Secciones--> 
        <table class="table table-bordered tabla_secciones datatables" id="tabla_secciones" width="100%" cellspacing="0">
          <thead>  
            <tr>
              <th style="text-align: center;">#</th>
              <th>Nombre</th>
              <th class="color-celda">Archivo</th>
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
<div class="modal fade" id="frm_actualizar_secciones" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" role="document" data-keyboard="false" data-backdrop="static">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title titulo_seccion" id="exampleModalLabel"></h5>
        <small class="texto-pequeno">Los campos con * son obligatorios.</small>
        <!--<button class="close FrmActualizarSeccion" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>-->
      </div> 
      <div class="modal-body"> 
        <form autocomplete="Off" id="FrmActualizarSeccion" onsubmit="return false">
          <div class="form-group">
            <div class="form-row"> 
              <div class="col-md-12">
                <div class="form-label-group"> 
                  <input type="text" id="nombre_seccion" name="nombre_seccion" class="form-control campo" placeholder="*Nombre"  autofocus="autofocus">
                  <label for="nombre_seccion">*Nombre:</label> 
                </div>
              </div>
            </div> 
          </div>
          <div class="form-group">
            <div class="form-row"> 
              <div class="col-md-12">
                <select class="custom-select campo" id="archivo_seccion" name="archivo_seccion"></select>
              </div>
            </div> 
          </div>
          <div class="form-group"> 
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input estado_seccion" id="estado_seccion" name="estado_seccion">   
              <label class="custom-control-label" for="estado_seccion">Estado</label>
            </div>
          </div>
        </form> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default boton_cancelar_actualizar_seccion" data-dismiss="modal">CANCELAR</button>
        <button type="button" class="btn btn-primary actualizar_seccion" id="actualizar_seccion"></button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$('.Configuraciones,.secciones').addClass('active');
$('.Configuraciones,.Configuraciones > div').addClass('show');
</script>
<script type="module" src="app/secciones.js"></script>