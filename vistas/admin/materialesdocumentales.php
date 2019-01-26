<div class="container-fluid">
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">Configuraciones</a>
    </li>
    <li class="breadcrumb-item active">Materiales documentales</li>
  </ol>
  <div class="card mb-3">
    <div class="card-header">
      Lista de materiales documentales <button class="btn btn-default btns-registros pull-right boton_registrar_material_documental" data-toggle="modal" data-target="#frm_actualizar_materiales_documentales">Nuevo</button></div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered tabla_materiales_documentales datatables" id="tabla_materiales_documentales" width="100%" cellspacing="0">
          <thead>  
            <tr>
              <th style="text-align: center;">#</th>
              <th>Nombre</th>
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
<div class="modal fade" id="frm_actualizar_materiales_documentales" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" role="document" data-keyboard="false" data-backdrop="static">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title titulo_material_documental" id="exampleModalLabel"></h5>
        <small class="texto-pequeno">Los campos con * son obligatorios.</small>
        <!--<button class="close FrmActualizarMaterialDocumental" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>-->
      </div> 
      <div class="modal-body"> 
        <form autocomplete="Off" id="FrmActualizarMaterialDocumental" onsubmit="return false">
          <div class="form-group">
            <div class="form-row"> 
              <div class="col-md-12">
                <div class="form-label-group"> 
                  <input type="text" id="nombre_material_documental" name="nombre_material_documental" class="form-control campo" placeholder="Nombre*" autofocus="autofocus">
                  <label for="nombre_material_documental">*Nombre:</label> 
                </div>
              </div>
            </div> 
          </div>
          <div class="form-group">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input estado_material_documental" id="estado_material_documental" name="estado_material_documental">   
              <label class="custom-control-label" for="estado_material_documental">Estado</label>
            </div>
          </div>
        </form> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default boton_cancelar_actualizar_material_documental" data-dismiss="modal">CANCELAR</button>  
        <button type="button" class="btn btn-primary actualizar_material_documental" id="actualizar_material_documental"></button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $('.Configuraciones,.materialesdocumentales').addClass('active');
  $('.Configuraciones,.Configuraciones > div').addClass('show');
</script>
<script type="module" src="app/materialesDocumentales.js"></script>