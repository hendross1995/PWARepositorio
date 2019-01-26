<div class="container">
  <div class="card mb-3">
    <div class="card-header">Mi perfil</div>
    <div class="card-body">
      <form autocomplete="Off" id="FrmActualizarUsuario">
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
          <label for="sexo_usuario" class="col-md-3 col-form-label">* Género:</label>
          <div class="col-md-6">
            <select class="custom-select campo" id="sexo_usuario" name="sexo_usuario">
              <option value="0">Seleccionar:</option>
              <option value="M">Masculino</option>
              <option value="F">Femenino</option>
              <option value="O">Otros</option>
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
          <label for="correo_usuario" class="col-md-3 col-form-label">* Correo:</label>
          <div class="col-md-8">
            <input type="text" class="form-control campo" id="correo_usuario" name="correo_usuario">
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-12">
            <input type="submit" class="btn btn-block actualizar_perfil btn-info" style="margin-bottom: -18px;" value="ACTUALIZAR">
          </div>
        </div>
      </form>      
    </div>
  </div>
</div>
<script type="module" src="app/miPerfil.js"></script>
     
    