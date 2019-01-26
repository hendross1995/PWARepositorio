<main class="">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 col-md-4 col-lg-4">
        <div class="md-form">
          <div class="md-form input-group mb-3" style="display: flex;align-items: center;justify-content: center;">
            <input type="text" class="form-control" id="inputBuscarPrincipal" placeholder="Buscar:" aria-label="Buscar:" aria-describedby="MaterialButton-addon2">
            <div class="input-group-append">
             <i class="fa fa-search waves-effect" role="button" id="btn_buscar_principal"></i>
              <!--<button class="btn btn-md m-0 px-3" type="button" id="btn_buscar_principal">
                <i class="fa fa-search" role="button" id="btn_buscar_principal" style="color: #000;"></i></button>-->
            </div>
          </div>
        </div>
        <div class="card">
          <h6 class="card-header danger-color-dark white-text py-2">
            <span>Filtrar por categorías</span>
          </h6>
          <div class="card-body row">
            <div class="col-md-12" style="margin-top: -13px;">
              <label class="form-label" for="filtrar_fondo"><small>Fondo:</small></label>
              <select data-live-search="true" class="show-tick form-control form-control-sm selectpicker" id="filtrar_fondo" name="filtrar_fondo" title="Seleccionar:">
              </select>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
              <label class="form-label" for="filtrar_coleccion"><small>Colección:</small></label>
              <select data-live-search="true" class="show-tick form-control form-control-sm selectpicker" id="filtrar_coleccion" name="filtrar_coleccion" title="Seleccione una coleccion:"></select>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
              <label class="form-label" for="filtrar_generadores"><small>Generadores del documento:</small></label>
              <select data-live-search="true" class="show-tick form-control form-control-sm selectpicker" multiple id="filtrar_generadores" name="filtrar_generadores" title="Seleccionar:"></select>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
              <label class="form-label" for="filtrar_personajes"><small>Personajes que intervienen:</small></label>
              <select data-live-search="true" class="show-tick form-control form-control-sm selectpicker" multiple id="filtrar_personajes" name="filtrar_personajes" title="Seleccionar:"></select>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
              <label class="form-label" for="filtrar_toponimia"><small>Toponimia:</small></label>
              <select data-live-search="true" class="show-tick form-control form-control-sm selectpicker" multiple id="filtrar_toponimia" name="filtrar_toponimia" title="Seleccionar:"></select>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
              <label class="form-label" for="filtrar_idioma"><small>Idioma:</small></label>
              <select data-live-search="true" id="filtrar_idioma" class="show-tick form-control form-control-sm selectpicker" multiple name="filtrar_idioma" title="Seleccionar:">
              </select>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
              <label class="form-label" for="filtrar_material_soporte"><small>Material soporte:</small></label>
              <select multiple data-live-search="true" id="filtrar_material_soporte" name="filtrar_material_soporte" class="show-tick form-control form-control-sm selectpicker" title="Seleccionar:"></select>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
              <label class="form-label" for="filtrar_material_documento"><small>Material del documento:</small></label>
              <select multiple data-live-search="true" id="filtrar_material_documento" name="filtrar_material_documento" class="show-tick form-control form-control-sm selectpicker" title="Seleccionar:"></select>
            </div>
            <div id="seccion_fecha_especifica" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <label class="form-label" for="filtrar_idioma"><small>Años críticos:</small></label>
                <input type="text" id="inputFechaEspecifica" name="inputFechaEspecifica"  class="form-control form-control-sm"  data-role="tagsinput">
            </div>
            <div id="seccion_palabras_claves" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <label class="form-label" for="filtrar_idioma"><small>Palabras claves:</small></label>
                <input type="text" id="inputPalabrasClaves" name="inputPalabrasClaves"  class="form-control form-control-sm"  data-role="tagsinput">
            </div>
          </div>
        </div> 
      </div>
      <div class="contenedor-ficha col-sm-12 col-md-8 col-lg-8">
        <div class="accordion" id="documentosAcordeon" role="tablist" aria-multiselectable="true">
            <ol class="breadcrumb"><li><span class="busquedaResultado"></span></li></ol>
        </div>
        <div class="contenedorCarga">
          <div class="mensajeNoEncontrado error404"></div>
          <div class="contenedorMensaje"></div> 
          <img src="" alt="" class="fichaBusqueda"> 
          <img src="" alt="" class="contenedorSpiner">    
        </div> 
        <div id="paginacion" class="paginationjs-theme-blue paginationjs-small"></div> 
        </div>
      </div>
    </div>
</main>
<script type="text/javascript">
  $('.inicio_lector').css({'background':'#e9ecef'});
  $('.favoritos_lector').css({'background':'#f7f7f7'});
</script>
<script type="module" src="app/buscadorModerno.js"></script>
<script type="module" src="app/lector.js"></script>
