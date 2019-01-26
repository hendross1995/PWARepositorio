  <div class="container-fluid">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="#">Configuraciones</a>
      </li>
      <li class="breadcrumb-item active">Contenedores</li>
    </ol>
    <div class="card mb-3">
      <div class="card-header">
        Lista de contenedores <button class="btn btn-default btns-registros pull-right boton_registrar_contenedor" data-toggle="modal" data-target="#frm_actualizar_contenedores">Nuevo</button></div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered tabla_contenedores datatables" id="tabla_contenedores" width="100%" cellspacing="0">
            <thead>  
              <tr>
                <th style="text-align: center;">#</th>
                <th>Código</th>
                <th>Contenedor</th>
                <th class="color-celda">Archivo</th>
                <th class="color-celda">Sección</th>
                <th class="color-celda">Nivel</th>
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
<div class="modal fade" id="frm_actualizar_contenedores" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" role="document" data-keyboard="false" data-backdrop="static">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title titulo_contenedor" id="exampleModalLabel"></h5>
        <small class="texto-pequeno">Los campos con * son obligatorios.</small>
        <!--<button class="close FrmActualizarContenedor" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>-->
      </div>
      <div class="modal-body"> 
        <form autocomplete="Off" id="FrmActualizarContenedor" onsubmit="return false">
          <div class="form-group">
            <div class="form-row"> 
              <div class="col-md-12">
                <div class="form-label-group"> 
                  <input type="text" id="codigo_contenedor" name="codigo_contenedor" class="form-control campo" placeholder="* Código"  autofocus="autofocus">
                  <label for="codigo_contenedor">* Código:</label> 
                </div>
              </div>
            </div> 
          </div>
          <div class="form-group">
            <div class="form-row"> 
              <div class="col-md-12">
                <div class="form-label-group"> 
                  <input type="text" id="nombre_contenedor" name="nombre_contenedor" class="form-control campo" placeholder="* Nombre del contenedor"  autofocus="autofocus">
                  <label for="nombre_contenedor">* Nombre del contenedor:</label> 
                </div>
              </div>
            </div> 
          </div>
          <div class="form-group">
            <div class="form-row"> 
              <div class="col-md-12">
                <select class="custom-select campo" id="archivos" name="archivos"></select>
              </div>
            </div> 
          </div>
          <div class="form-group">
            <div class="form-row"> 
              <div class="col-md-12">
                <select class="custom-select campo" id="secciones" name="secciones"></select>
              </div>
            </div> 
          </div>
          <div class="form-group">
            <div class="form-row"> 
              <div class="col-md-12">
                <select class="custom-select campo" id="nivel_contenedor" name="nivel_contenedor"></select>
              </div>
            </div> 
          </div>
          <div class="form-group"> 
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input estado_contenedor" id="estado_contenedor" name="estado_contenedor">   
              <label class="custom-control-label" for="estado_contenedor">Estado</label>
            </div>
          </div>
        </form> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default boton_cancelar_actualizar_contenedor" data-dismiss="modal">CANCELAR</button>
        <button type="button" class="btn btn-primary actualizar_contenedor" id="actualizar_contenedor"></button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$('.Configuraciones,.contenedores').addClass('active');
$('.Configuraciones,.Configuraciones > div').addClass('show');
</script>
<script type="module" src="app/contenedores.js"></script>