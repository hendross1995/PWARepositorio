<div class="container-fluid">
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">Configuraciones</a>
    </li>
    <li class="breadcrumb-item active">Colecciones</li>
  </ol>
  <div class="card mb-3">
    <div class="card-header">
      Lista de colecciones <button class="btn btn-default btns-registros pull-right boton_registrar_coleccion" data-toggle="modal" data-target="#frm_actualizar_colecciones">Nuevo</button></div>
    <div class="card-body">
      <div class="table-responsive">
        <!--dataTable id en la tabla colecciones--> 
        <table class="table table-bordered tabla_colecciones datatables" id="tabla_colecciones" width="100%" cellspacing="0">
          <thead>  
            <tr>
              <th style="text-align: center;">#</th>
              <th>Nombre</th>
              <th>Descripción</th>
              <th>Fecha registro</th>
              <th class="color-celda">Fondo</th>
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
<div class="modal fade" id="frm_actualizar_colecciones" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" role="document" data-keyboard="false" data-backdrop="static">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title titulo_coleccion" id="exampleModalLabel"></h5>
        <small class="texto-pequeno">Los campos con * son obligatorios.</small>
        <!--<button class="close FrmActualizarColeccion" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>-->
      </div> 
      <div class="modal-body"> 
        <form autocomplete="Off" id="FrmActualizarColeccion" onsubmit="return false">
          <div class="form-group">
            <div class="form-row"> 
              <div class="col-md-12">
                <div class="form-label-group"> 
                  <input type="text" id="nombre_coleccion" name="nombre_coleccion" class="form-control campo" placeholder="Nombre*"  autofocus="autofocus">
                  <label for="nombre_coleccion">*Nombre:</label> 
                </div>
              </div>
            </div> 
          </div>
          <div class="form-group">
            <div class="form-row">
              <div class="col-md-12"> 
                <textarea class="form-control campo" name="descripcion_coleccion" id="descripcion_coleccion" placeholder="* Descripción:" rows="3"></textarea>
              </div> 
            </div>
          </div>
          <div class="form-group">
            <div class="form-row">  
              <div class="col-md-12">
                <div class="form-label-group"> 
                  <input type="date" id="fecha_registro_coleccion" name="fecha_registro_coleccion" class="form-control" placeholder="Fecha creación:"  autofocus="autofocus">
                  <label for="fecha_registro_coleccion">Fecha creación:</label> 
                </div>
              </div>
            </div> 
          </div>
          <div class="form-group">
            <div class="form-row"> 
              <div class="col-md-12">
                <select class="custom-select campo" id="fondo_coleccion" name="fondo_coleccion"></select>
              </div>
            </div> 
          </div>
          <div class="form-group"> 
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input estado_coleccion" id="estado_coleccion" name="estado_coleccion">   
              <label class="custom-control-label" for="estado_coleccion">Estado</label>
            </div>
          </div>
        </form> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default boton_cancelar_actualizar_coleccion" data-dismiss="modal">CANCELAR</button>  
        <button type="button" class="btn btn-primary actualizar_coleccion" id="actualizar_coleccion"></button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $('.Configuraciones,.colecciones').addClass('active');
  $('.Configuraciones,.Configuraciones > div').addClass('show');
</script>
<script type="module" src="app/colecciones.js"></script>
<?php require_once "footer.php"; ?>