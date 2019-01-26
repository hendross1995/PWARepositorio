<div class="container-fluid">
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">Mantenimiento</a>
    </li>
    <li class="breadcrumb-item active">Usuarios</li>
  </ol>
  <div class="card mb-3">
    <div class="card-header">
      Lista de usuarios <button class="btn btn-default btns-registros pull-right boton_registrar_usuario" data-toggle="modal" data-target="#frm_actualizar_usuarios">Nuevo</button></div>
    <div class="card-body">
      <div class="table-responsive">
        <!--dataTable id en la tabla usuarios--> 
        <table class="table table-bordered tabla_usuarios datatables" id="tabla_usuarios" width="100%" cellspacing="0">
          <thead>  
            <tr>
              <th></th>
              <th style="text-align: center;">#</th>
              <th>Usuario</th>
              <th>Rol</th>
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
<div class="modal fade" id="frm_actualizar_usuarios" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document" data-keyboard="false" data-backdrop="static">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title titulo_usuario" id="exampleModalLabel"></h5>
        <small class="texto-pequeno">Los campos con * son obligatorios.</small>
        <!--<button class="close FrmActualizarUsuario" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>-->
      </div> 
      <div class="modal-body"> 
        <form autocomplete="Off" id="FrmActualizarUsuario" onsubmit="return false">
          <div class="form-group row">
            <label for="cedula_usuario" class="col-md-3 col-form-label">* Cédula:</label>
            <div class="col-md-6">
              <input type="text" class="form-control campo" id="cedula_usuario" name="cedula_usuario">
            </div>
          </div>
          <div class="form-group row">
            <label for="apellidos_usuario" class="col-md-3 col-form-label">* Apellidos:</label>
            <div class="col-md-8">
              <input type="text" class="form-control campo" id="apellidos_usuario" name="apellidos_usuario">
            </div>
          </div>
          <div class="form-group row">
            <label for="nombres_usuario" class="col-md-3 col-form-label">* Nombres:</label>
            <div class="col-md-8">
              <input type="text" class="form-control campo" id="nombres_usuario" name="nombres_usuario">
            </div>
          </div>
          <div class="form-group row">
            <label for="sexo_usuario" class="col-md-3 col-form-label">* Género</label>
            <div class="col-md-6">
              <select class="custom-select campo" id="sexo_usuario" name="sexo_usuario">
                <option value="0">Seleccionar:</option>
                <option value="M">Masculino</option>
                <option value="F">Femenino</option>
                <option value="O">Otro</option>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="convencional_usuario" class="col-md-3 col-form-label">Convencional:</label>
            <div class="col-md-6">
              <input type="number" class="form-control" id="convencional_usuario" name="convencional_usuario">
            </div>
          </div>
          <div class="form-group row">
            <label for="celular_usuario" class="col-md-3 col-form-label">Celular:</label>
            <div class="col-md-6">
              <input type="number" class="form-control" id="celular_usuario" name="celular_usuario">
            </div>
          </div>
          <div class="form-group row">
            <label for="provincia_usuario" class="col-md-3 col-form-label">* Provincia:</label>
            <div class="col-md-7">
              <select class="custom-select campo" id="provincia_usuario" name="provincia_usuario"></select>
            </div>
          </div>
          <div class="form-group row">
            <label for="canton_usuario" class="col-md-3 col-form-label">* Cantón:</label>
            <div class="col-md-7">
              <select class="custom-select campo" id="canton_usuario" name="canton_usuario"></select>
            </div>
          </div>
          <div class="form-group row">
            <label for="parroquia_usuario" class="col-md-3 col-form-label">* Parroquia:</label>
            <div class="col-md-7">
              <select class="custom-select campo" id="parroquia_usuario" name="parroquia_usuario"></select>
            </div>
          </div>
          <div class="form-group row">
            <label for="direccion_usuario" class="col-md-3 col-form-label">Dirección:</label>
            <div class="col-md-8">
              <input type="text" class="form-control" id="direccion_usuario" name="direccion_usuario">
            </div>
          </div>
          <hr>
          <div class="form-group row">
            <label for="rol_usuario" class="col-md-3 col-form-label">* Rol:</label>
            <div class="col-md-6">
              <select class="custom-select campo" id="rol_usuario" name="rol_usuario"></select>
            </div>
          </div>
          <div class="form-group row">
            <label for="correo_usuario" class="col-md-3 col-form-label">* Correo:</label>
            <div class="col-md-8">
              <input type="text" class="form-control campo" id="correo_usuario" name="correo_usuario">
            </div>
          </div>
          <div class="form-group row">
            <label for="estado_usuario" class="col-md-3 col-form-label"></label>
            <div class="col-md-8">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input estado_usuario" id="estado_usuario" name="estado_usuario">
                <label class="custom-control-label" for="estado_usuario">Estado</label>
              </div>
            </div>
          </div>
        </form> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default boton_cancelar_actualizar_usuario" data-dismiss="modal">CANCELAR</button>
        <button type="button" class="btn btn-primary actualizar_usuario" id="actualizar_usuario">
        </button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$('.Mantenimiento,.usuarios').addClass('active');
$('.Mantenimiento,.Mantenimiento > div').addClass('show');
</script>
<script type="module" src="app/usuarios.js"></script>