<link href="src/admin/vendor/subir-archivos/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
<link href="src/admin/vendor/subir-archivos/themes/explorer/theme.css" media="all" rel="stylesheet" type="text/css"/>
<style>
    .fileinput-upload-button{
        display:none !important 
    }
    div.file-preview{
      max-height: 380px;
      overflow-y: scroll;
    }
</style>

<div class="container-fluid">
  <ol class="breadcrumb"> 
    <li class="breadcrumb-item">
      <a href="#">Gestión documental</a>
    </li>
    <li class="breadcrumb-item active">Documentos históricos</li>
  </ol>
  <div class="card mb-3">
    <div class="card-header">
      Lista de documentos <button class="btn btn-default btns-registros pull-right boton_registrar_documento" data-toggle="modal" data-target="#frm_actualizar_documentos">Nuevo</button></div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered tabla_documentos datatables" id="tabla_documentos" width="100%" cellspacing="0">
          <thead>  
            <tr>
              <th style="text-align: center;">#</th>
              <th>Ficha</th>
              <th>Nombre</th>
              <th style="text-align: center;">Estado</th>
              <th style="text-align: center;">Verificación</th>
              <th style="text-align: center;">Acción</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="frm_actualizar_documentos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document" data-keyboard="false" data-backdrop="static">
    <div class="modal-content" style="width: 103% !important;">
      <div class="modal-header">
        <h5 class="modal-title titulo_documento" id="exampleModalLabel">Actualización de documentos</h5>
        <small class="texto-pequeno">Los campos con * son obligatorios.</small>
        <!--<button class="close FrmActualizarDocumento" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>-->
      </div> 
      <div class="modal-body"> 
        <form autocomplete="Off" id="FrmActualizarDocumento" onsubmit="return false">
          <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="d-generales-tab" data-toggle="pill" href="#d-generales" role="tab" aria-controls="d-generales" aria-selected="true">Datos generales</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="d-documental-tab" data-toggle="pill" href="#d-documental" role="tab" aria-controls="d-documental" aria-selected="true">Descripción documental</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="ubicacion-tab" data-toggle="pill" href="#ubicacion" role="tab" aria-controls="ubicacion" aria-selected="false">Ubicación y especificación técnica</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="_archivos-tab" data-toggle="pill" href="#_archivos" role="tab" aria-controls="_archivos" aria-selected="false">Archivos</a>
            </li>
          </ul>
          <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="d-generales" role="tabpanel" aria-labelledby="d-generales-tab">
              <div class="form-group row">
                <label for="numero_documento" class="col-md-3 col-form-label">* N° Ficha:</label>
                <div class="col-md-6">
                  <input type="text" class="form-control campo" id="numero_documento" name="numero_documento">
                </div>
              </div>
              <div class="form-group row">
                <label for="codigo_institucional_documento" class="col-md-3 col-form-label">* Código institucional:</label>
                <div class="col-md-6">
                  <input type="text" class="form-control campo" id="codigo_institucional_documento" name="codigo_institucional_documento">
                </div>
              </div>
              <div class="form-group row">
                <label for="codigo_patrimonial_documento" class="col-md-3 col-form-label">Código patrimonial:</label>
                <div class="col-md-6">
                  <input type="text" class="form-control" id="codigo_patrimonial_documento" name="codigo_patrimonial_documento">
                </div>
              </div>
              <div class="form-group row">
                <label for="codigo_digital_documento" class="col-md-3 col-form-label">* Código digital:</label>
                <div class="col-md-6">
                  <input type="text" class="form-control campo" id="codigo_digital_documento" name="codigo_digital_documento">
                </div>
              </div>
              <div class="form-group row">
                <label for="nombre_documento" class="col-md-3 col-form-label">* Nombre:</label>
                <div class="col-md-8">
                  <input type="text" class="form-control campo" id="nombre_documento" name="nombre_documento">
                </div>
              </div>
              <div class="form-group row">
                <label for="nombre_sugerido_documento" class="col-md-3 col-form-label">* Nombre sugerido:</label>
                <div class="col-md-8">
                  <input type="text" class="form-control campo" id="nombre_sugerido_documento" name="nombre_sugerido_documento">
                </div>
              </div>
              <div class="form-group row">
                <label for="extension_documento" class="col-md-3 col-form-label">* N° de Extensión:</label>
                <div class="col-md-8">
                  <input type="text" class="form-control campo" id="extension_documento" name="extension_documento">
                </div>
              </div>
              <div class="form-group row">
                <label for="estado_conservacion_documento" class="col-md-3 col-form-label">* Estado conservación:</label>
                <div class="col-md-8">
                  <select class="custom-select campo" id="estado_conservacion_documento" name="estado_conservacion_documento"></select>
                </div>
              </div>
              <div class="form-group row">
                <label for="portada_documento" class="col-md-3 col-form-label">* Portada:</label>
                <div class="col-md-8">
                  <input type="file" class="form-control campo" id="portada_documento" name="portada_documento">
                </div>
              </div>
              <div class="form-group row mostrar_portada_documento">
                <label class="col-md-3 col-form-label"></label>
                <div class="col-md-6">
                  <div class="contenedorFoto portada_documento_editable">
                    <img src="src/inicial/images/anonimo.png" id="mostrar_portada_documento" name="mostrar_portada_documento" class="img-thumbnail previsualizar mostrar_foto">
                    <a href="#" class=""><i class="fa fa-trash elimminar_portada_documento" aria-hidden="true"></i></a> 
                  </div> 
                </div>
              </div>
              <div class="form-group row">
                <label for="observaciones_documento" class="col-md-3 col-form-label">Observaciones:</label>
                <div class="col-md-8">
                  <textarea class="form-control" name="observaciones_documento" id="observaciones_documento" rows="3"></textarea>
                </div>
              </div>
              <div class="form-group row">
                <label for="estado_documento" class="col-md-3 col-form-label"></label>
                <div class="col-md-8">
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input estado_documento" id="estado_documento" name="estado_documento">
                    <label class="custom-control-label" for="estado_documento">Estado</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="d-documental" role="tabpanel" aria-labelledby="d-documental-tab">
              <div class="form-group row">
                <label for="fondo_documento" class="col-md-3 col-form-label">Fondo:</label>
                <div class="col-md-8">
                  <select class="custom-select" id="fondo_documento" name="fondo_documento"></select>
                </div>
              </div>
              <div class="form-group row">
                <label for="coleccion_documento" class="col-md-3 col-form-label">Colección:</label>
                <div class="col-md-8">
                  <select class="custom-select" id="coleccion_documento" name="coleccion_documento"></select>
                </div>
              </div>
              <div class="form-group row">
                <label for="asunto_tema_documento" class="col-md-3 col-form-label">*Asunto / Tema:</label>
                <div class="col-md-8">
                  <input type="text" class="form-control campo" id="asunto_tema_documento" name="asunto_tema_documento">
                </div>
              </div>
              <div class="form-group row">
                <label for="lugar_emision_documento" class="col-md-3 col-form-label">Lugar de emisión:</label>
                <div class="col-md-8">
                  <input type="text" class="form-control" id="lugar_emision_documento" name="lugar_emision_documento">
                </div>
              </div>
              <div class="form-group row">
                <label for="toponimia_documento" class="col-md-3 col-form-label">Toponimia:</label>
                <div class="col-md-8">
                  <select class="selectpicker show-tick" multiple data-live-search="true" id="toponimia_documento" name="toponimia_documento"></select>
                </div>
              </div>
              <div class="form-group row">
                <label for="generadores_documento" class="col-md-3 col-form-label">Generadores:</label>
                <div class="col-md-8">
                  <select class="selectpicker show-tick" multiple data-live-search="true" id="generadores_documento" name="generadores_documento"></select>
                </div>
              </div>
              <div class="form-group row">
                <label for="personajes_documento" class="col-md-3 col-form-label">Personajes:</label>
                <div class="col-md-8">
                  <select class="selectpicker show-tick" multiple data-live-search="true" id="personajes_documento" name="personajes_documento"></select>
                </div>
              </div>
              <div class="form-group row">
                <label for="idiomas_documento" class="col-md-3 col-form-label">Idiomas:</label>
                <div class="col-md-8">
                  <select class="selectpicker show-tick" multiple data-live-search="true" id="idiomas_documento" name="idiomas_documento"></select>
                </div>
              </div>
              <div class="form-group row">
                <label for="anos_criticos_documento" class="col-md-3 col-form-label">Años críticos:</label>
                <div class="col-md-8">
                  <input type="text" class="form-control" data-role="tagsinput" id="anos_criticos_documento" name="anos_criticos_documento">
                </div>
              </div>
              <div class="form-group row">
                <label for="palabras_claves_documento" class="col-md-3 col-form-label">Palabras claves:</label>
                <div class="col-md-8">
                  <input type="text" class="form-control" data-role="tagsinput" id="palabras_claves_documento" name="palabras_claves_documento">
                </div>
              </div>
              <div class="form-group row">
                <label for="descripcion_documento" class="col-md-3 col-form-label">* Descripción:</label>
                <div class="col-md-8">
                  <textarea class="form-control campo" name="descripcion_documento" id="descripcion_documento" rows="4"></textarea>
                </div>
              </div>
              <div class="form-group row">
                <label for="transcripcion_documento" class="col-md-3 col-form-label">Transcripción:</label>
                <div class="col-md-8">
                  <textarea class="form-control" name="transcripcion_documento" id="transcripcion_documento" rows="4"></textarea>
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="ubicacion" role="tabpanel" aria-labelledby="ubicacion-tab">
              <div class="form-group row">
                <label for="archivos_documento" class="col-md-3 col-form-label">Archivos:</label>
                <div class="col-md-8">
                  <select class="custom-select" id="archivos_documento" name="archivos_documento"></select>
                </div>
              </div>
              <div class="form-group row">
                <label for="secciones_documento" class="col-md-3 col-form-label">Secciones:</label>
                <div class="col-md-8">
                  <select class="custom-select" id="secciones_documento" name="secciones_documento"></select>
                </div>
              </div>
              <div class="form-group row">
                <label for="nivel_documento" class="col-md-3 col-form-label">Niveles:</label>
                <div class="col-md-8">
                  <select class="custom-select" id="nivel_documento" name="nivel_documento"></select>
                </div>
              </div>
              <div class="form-group row">
                <label for="contenedor_documento" class="col-md-3 col-form-label">Contenedor:</label>
                <div class="col-md-8">
                  <select class="custom-select" id="contenedor_documento" name="contenedor_documento"></select>
                </div>
              </div>
              <div class="form-group row">
                <label for="formato_documento" class="col-md-3 col-form-label">Formato:</label>
                <div class="col-md-8">
                  <select class="selectpicker show-tick" multiple data-live-search="true" id="formato_documento" name="formato_documento"></select>
                </div>
              </div>
              <div class="form-group row">
                <label for="tipo_material_documento" class="col-md-3 col-form-label">Tipo material soporte:</label>
                <div class="col-md-8">
                  <select class="selectpicker show-tick" multiple data-live-search="true" id="tipo_material_documento" name="tipo_material_documento"></select>
                </div>
              </div>
              <div class="form-group row">
                <label for="material_soporte_documento" class="col-md-3 col-form-label">Material de soporte:</label>
                <div class="col-md-8">
                  <select class="selectpicker show-tick" multiple data-live-search="true" id="material_soporte_documento" name="material_soporte_documento"></select>
                </div>
              </div>
              <div class="form-group row">
                <label for="material_documento_documento" class="col-md-3 col-form-label">Material documento:</label>
                <div class="col-md-8">
                  <select class="selectpicker show-tick" multiple data-live-search="true" id="material_documento_documento" name="material_documento_documento"></select>
                </div>
              </div>
              <div class="form-group row">
                <label for="largo_documento" class="col-md-3 col-form-label">Largo:</label>
                <div class="col-md-7">
                  <input type="text" class="form-control" id="largo_documento" name="largo_documento" placeholder="Largo del documento">
                </div>
              </div>
              <div class="form-group row">
                <label for="ancho_documento" class="col-md-3 col-form-label">Ancho:</label>
                <div class="col-md-7">
                  <input type="text" class="form-control" id="ancho_documento" name="ancho_documento" placeholder="Ancho del documento">
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="_archivos" role="tabpanel" aria-labelledby="_archivos-tab">
              <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist" style="background-color: #f1f1f1;">
                <li class="nav-item">
                  <a class="nav-link active" id="d-subidos-tab" data-toggle="pill" href="#d-subidos" role="tab" aria-controls="d-subidos" aria-selected="true">Subidos</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="d-porsubir-tab" data-toggle="pill" href="#d-porsubir" role="tab" aria-controls="d-porsubir" aria-selected="true">Por subir</a>
                </li>
              </ul>
              <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="d-subidos" role="tabpanel" aria-labelledby="d-subidos-tab">
                  <div class="form-group row scroll recibe_archivos_documentos"></div>
                </div>
                <div class="tab-pane fade" id="d-porsubir" role="tabpanel" aria-labelledby="d-porsubir-tab">
                  <div class="form-group row">
                    <div class="col-md-12" id="formDropZone">
                      <div class="file-loading">
                        <input id="archivos" type="file" multiple class="file" data-overwrite-initial="false" data-min-file-count="1" name="archivos[]">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default boton_cancelar_actualizar_documento" data-dismiss="modal">CANCELAR</button>
        <button type="button" verificar="1" class="btn btn-secondary boton_guardar_borrador_documento">ENVIAR A BORRADOR</button>
        <button type="button" verificar="2" class="btn btn-primary actualizar_documento" id="actualizar_documento">REGISTRAR</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="frm_ver_documentos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document" data-keyboard="false" data-backdrop="static">
    <div class="modal-content" style="width: 103% !important;">
      <div class="modal-header">
        <h5 class="modal-title titulo_documento" id="exampleModalLabel">Ver detalles del documento</h5>
        <small class="texto-pequeno">Los campos con * son obligatorios.</small>
        <button class="close FrmVerDocumento" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
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
<!--ELIMINAR ARCHIVOS--> 
<div class="modal fade" id="frm_eliminar_archivo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content" style="width: 103%;">
      <div class="modal-header">
        <h4 class="modal-title">Eliminar archivo</h4>
          <!--<button  type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <span>¿Seguro deseas eliminar este archivo?</span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" id="cancelar_eliminar">CANCELAR</button>
        <button type="button" class="btn btn-primary" id="eliminar">ELIMINAR</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$('.GestionDocumental,.documentos').addClass('active');
$('.GestionDocumental,.GestionDocumental > div').addClass('show');
</script>
<script src="src/admin/vendor/subir-archivos/js/fileinput.js" type="text/javascript"></script>
<script src="src/admin/vendor/subir-archivos/themes/explorer/theme.js" type="text/javascript"></script>

<script type="module" src="app/documentos.js"></script>
