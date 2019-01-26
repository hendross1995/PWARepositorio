<div class="container-fluid">
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">Mantenimiento</a>
    </li>
    <li class="breadcrumb-item active">Personajes y Generadores</li>
  </ol>
  <div class="card mb-3">
    <div class="card-header">
      Lista de personajes y generadores <button class="btn btn-default btns-registros pull-right boton_registrar_personajegenerador" data-toggle="modal" data-target="#frm_actualizar_personajesgeneradores">Nuevo</button></div>
    <div class="card-body">
      <div class="table-responsive">
        <!--dataTable id en la tabla personajes--> 
        <table class="table table-bordered tabla_personajesgeneradores datatables" id="tabla_personajesgeneradores" width="100%" cellspacing="0">
          <thead>  
            <tr>
              <th></th>
              <th style="text-align: center;">#</th>
              <th>Nombres</th>
              <th>Apellidos</th>
              <th>Género</th>
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
<div class="modal fade" id="frm_actualizar_personajesgeneradores" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document" data-keyboard="false" data-backdrop="static">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title titulo_personajegenerador" id="exampleModalLabel"></h5>
        <small class="texto-pequeno">Los campos con * son obligatorios.</small>
        <!--<button class="close FrmActualizarPersonajeGenerador" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>-->
      </div> 
      <div class="modal-body"> 
        <form autocomplete="Off" id="FrmActualizarPersonajeGenerador" onsubmit="return false">
          <div class="form-group row">
            <label for="cedula_personajegenerador" class="col-md-3 col-form-label">Cédula:</label>
            <div class="col-md-6">
              <input type="text" class="form-control" id="cedula_personajegenerador" name="cedula_personajegenerador">
            </div>
          </div>
          <div class="form-group row">
            <label for="nombres_personajegenerador" class="col-md-3 col-form-label">* Nombres:</label>
            <div class="col-md-8">
              <input type="text" class="form-control campo" id="nombres_personajegenerador" name="nombres_personajegenerador">
            </div>
          </div>
          <div class="form-group row">
            <label for="apellidos_personajegenerador" class="col-md-3 col-form-label">* Apellidos:</label>
            <div class="col-md-8">
              <input type="text" class="form-control campo" id="apellidos_personajegenerador" name="apellidos_personajegenerador">
            </div>
          </div>
          <div class="form-group row">
            <label for="lugar_nacimiento_personajegenerador" class="col-md-3 col-form-label">Lugar nacimiento:</label>
            <div class="col-md-8">
              <input type="text" class="form-control" id="lugar_nacimiento_personajegenerador" name="lugar_nacimiento_personajegenerador">
            </div>
          </div>
          <div class="form-group row">
            <label for="fecha_nacimiento_personajegenerador" class="col-md-3 col-form-label">Fecha nacimiento:</label>
            <div class="col-md-6">
              <input type="date" class="form-control" id="fecha_nacimiento_personajegenerador" name="fecha_nacimiento_personajegenerador">
            </div>
          </div>
          <div class="form-group row">
            <label for="fecha_disfuncion_personajegenerador" class="col-md-3 col-form-label">Fecha disfunción:</label>
            <div class="col-md-6">
              <input type="date" class="form-control" id="fecha_disfuncion_personajegenerador" name="fecha_disfuncion_personajegenerador">
            </div>
          </div>
          <div class="form-group row">
            <label for="sexo_personajegenerador" class="col-md-3 col-form-label">* ´Género:</label>
            <div class="col-md-6">
              <select class="custom-select campo" id="sexo_personajegenerador" name="sexo_personajegenerador">
                  <option value="0">´Género:</option>
                  <option value="M">Masculino</option>
                  <option value="F">Femenino</option>
                  <option value="F">Otros</option>
                </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="foto_personajegenerador" class="col-md-3 col-form-label">Foto:</label>
            <div class="col-md-6"> 
              <input type="file" class="form-control" id="foto_personajegenerador" name="foto_personajegenerador" accept="image/*"> 
            </div>
          </div>
          <div class="form-group row mostrar_foto_personajegenerador">
            <label class="col-md-3 col-form-label"></label>
            <div class="col-md-6">
              <p class="text-muted ">Peso máximo de la foto 2MB</p>
              <div class="contenedorFoto foto_personaje_editable">
                <img src="src/inicial/images/anonimo.png" id="mostrar_foto_personajegenerador" name="mostrar_foto_personajegenerador" class="img-thumbnail previsualizar mostrar_foto">
                <a href="#" class=""><i class="fa fa-trash elimminar_foto_personajegenerador" aria-hidden="true"></i></a> 
              </div> 

            </div>
          </div>
          <div class="form-group row">
            <label for="nacionalidad_personajegenerador" class="col-md-3 col-form-label">Nacionalidad:</label>
            <div class="col-md-7">
              <input type="text" class="form-control" id="nacionalidad_personajegenerador" name="nacionalidad_personajegenerador">
            </div>
          </div>
          <div class="form-group row">
            <label for="organizacion_personajegenerador" class="col-md-3 col-form-label"> Organización:</label>
            <div class="col-md-8">
              <input type="text" class="form-control" id="organizacion_personajegenerador" name="organizacion_personajegenerador">
            </div>
          </div>
          <div class="form-group row">
            <label for="alias_personajegenerador" class="col-md-3 col-form-label">Álias:</label>
            <div class="col-md-8">
              <input type="text" class="form-control" id="alias_personajegenerador" name="alias_personajegenerador">
            </div>
          </div>
          <div class="form-group row">
            <label for="descripcion_personajegenerador" class="col-md-3 col-form-label">Descripción:</label>
            <div class="col-md-8">
              <textarea class="form-control" name="descripcion_personajegenerador" id="descripcion_personajegenerador" placeholder="Descripción:" rows="3"></textarea>
            </div>
          </div>
          <div class="form-group row">
            <label for="estado_personajegenerador" class="col-md-3 col-form-label"></label>
            <div class="col-md-8">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input estado_personajegenerador" id="estado_personajegenerador" name="estado_personajegenerador">
                <label class="custom-control-label" for="estado_personajegenerador">Estado</label>
              </div>
            </div>
          </div>
        </form> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default boton_cancelar_actualizar_personajegenerador" data-dismiss="modal">CANCELAR</button><button type="buton" class="btn btn-primary actualizar_personajegenerador" id="actualizar_personajegenerador">
        </button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$('.Mantenimiento,.personajesgeneradores').addClass('active');
$('.Mantenimiento,.Mantenimiento > div').addClass('show');
</script>
<script type="module" src="app/personajesgeneradores.js"></script>