  <div class="container-fluid">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="#">Configuraciones</a>
      </li>
      <li class="breadcrumb-item active">Niveles</li>
    </ol>
    <div class="card mb-3">
      <div class="card-header">
        Lista de niveles <button class="btn btn-default btns-registros pull-right boton_registrar_nivel" data-toggle="modal" data-target="#frm_actualizar_niveles">Nuevo</button></div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered tabla_niveles datatables" id="tabla_niveles" width="100%" cellspacing="0">
            <thead>  
              <tr>
                <th style="text-align: center;">#</th>
                <th>Nivel</th>
                <th class="color-celda">Archivo</th>
                <th class="color-celda">Sección</th>
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
<div class="modal fade" id="frm_actualizar_niveles" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" role="document" data-keyboard="false" data-backdrop="static">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title titulo_nivel" id="exampleModalLabel"></h5>
        <small class="texto-pequeno">Los campos con * son obligatorios.</small>
        <!--<button class="close FrmActualizarNivel" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>-->
      </div> 
      <div class="modal-body"> 
        <form autocomplete="Off" id="FrmActualizarNivel" onsubmit="return false">
          <div class="form-group">
            <div class="form-row"> 
              <div class="col-md-12">
                <div class="form-label-group"> 
                  <input type="text" id="nombre_nivel" name="nombre_nivel" class="form-control campo" placeholder="*Nombre nivel"  autofocus="autofocus">
                  <label for="nombre_nivel">*Nombre nivel:</label> 
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="form-row"> 
              <div class="col-md-12">
                <select class="custom-select campo" id="archivo_seccion_niveles" name="archivo_seccion_niveles"></select>
              </div>
            </div> 
          </div>
          <div class="form-group">
            <div class="form-row"> 
              <div class="col-md-12">
                <select class="custom-select campo" id="seccion_nivel" name="seccion_nivel"></select>
              </div>
            </div> 
          </div>
          <div class="form-group"> 
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input estado_nivel" id="estado_nivel" name="estado_nivel">   
              <label class="custom-control-label" for="estado_nivel">Estado</label>
            </div>
          </div>
        </form> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default boton_cancelar_actualizar_nivel" data-dismiss="modal">CANCELAR</button>
        <button type="button" class="btn btn-primary actualizar_nivel" id="actualizar_nivel"></button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$('.Configuraciones,.niveles').addClass('active');
$('.Configuraciones,.Configuraciones > div').addClass('show');
</script>
<script type="module" src="app/niveles.js"></script>