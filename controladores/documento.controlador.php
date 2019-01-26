<?php

require 'modelos/documento.modelo.php';
require 'controladores/acceso.controlador.php';

Class DocumentoControlador{
    private $documento;
    private $acceso;
    private $data = array();
    private $id = 0;

    public function __CONSTRUCT(){
        $this->documento = new Documento();
        $this->acceso = new AccesoControlador();
    }
    Public function FrmDocumentos(){
        if($this->acceso->ComprobarAcceso($_GET,NULL,TRUE)){
            require_once 'vistas/admin/header.php';
            require_once 'vistas/admin/documentos.php';
            require_once 'vistas/admin/footer.php';
        }
    }

   

    private function RecorrerJSON2($parametros,$variable){

        if(json_encode($parametros)){
            foreach ((array)json_decode($parametros) as $r) {
            $dato .= '- '.$r.'';
            }
        }else{
            $dato = NULL;
        }
        return $dato;
    }

    public function MostrarDocumentos(){
        if($this->acceso->ComprobarAcceso($_GET,$_POST['id'],FALSE)){
            $x = new Documento();
            $x->usuario=$this->acceso->Crypto('decrypt',$_SESSION['idusuario']);
            $x->rol = $_SESSION['rol'];
            foreach ($this->documento->MostrarDocumentos($x) as $r) {
                $id++;
                if($r->estado == 'true'){
                    $estado = '<small class="badge badge-success">ACTIVO</small>';
                }else{
                    $estado = '<small class="badge badge-dark">INACTIVO</small>';
                }
                if($r->estado_verificacion == 'BORRADOR'){
                    $estado_verificacion = '<small class="badge badge-dark">BORRADOR</small>';
                }elseif($r->estado_verificacion == 'REVISIÓN'){
                    $estado_verificacion = '<small class="badge badge-info">REVISIÓN</small>';
                }elseif($r->estado_verificacion == 'APROBADO'){
                    $estado_verificacion = '<small class="badge badge-success">APROBADO</small>';
                }
                $data[] = array(
                    'num'=> $id,
                    'num_ficha' =>  $r->numero,
                    'nombre' => $r->nombre,
                    "estado"=> $estado,
                    "estado_verificacion"=> $estado_verificacion,
                    'accion'=> '<button class="btn boton_ver_documento" type="button" id="'.$this->acceso->Crypto('encrypt',$r->idfichatecnica).'"><i class="fa fa-eye"></i></button>'.($r->estado_verificacion == 'BORRADOR' || $_SESSION['rol'] == 'ADMINISTRADOR'?'<button class="btn boton_modificar_documento" type="button" id="'.$this->acceso->Crypto('encrypt',$r->idfichatecnica).'" style="margin-left: 6px;"><i class="fa fa-edit"></i></button>':''),
                );
            }
            if(!$data) $data = '';
            $results = array("data" =>  $data);
            echo json_encode($results);
         }
    }

    public function VerDetalleDocumento(){
        if(isset($_SESSION['idusuario'])){
            if(isset($_POST['idfichatecnica'])) {
                $x = new Documento();
                $x->idfichatecnica = $this->acceso->Crypto('decrypt',$_POST['idfichatecnica']);
                foreach ($this->documento->VerDetalleDocumento($x) as $r) {
                if($r->estado){
                    $estado = '<small class="badge badge-success">ACTIVO</small>';
                }else{
                    $estado = '<small class="badge badge-dark">INACTIVO</small>';
                }
                $datos = '
                    <div id="accordion">
                      <div class="card">
                        <div class="card-header" id="datos_generales">
                          <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseDatosGenerales" aria-expanded="true" aria-controls="collapseDatosGenerales">
                              DATOS GENERALES
                            </button>
                          </h5>
                        </div>
                        <div id="collapseDatosGenerales" class="collapse show" aria-labelledby="datos_generales" data-parent="#accordion">
                          <div class="card-body">
                            <table class="table">
                                <tdoby>
                                    <tr><td style="padding: 0.5em;"><b>N° Ficha: </b><span>'.$r->numero.'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Código institucional: </b><span>'.$r->codigo_institucional.'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Código patrimonial: </b><span>'.$r->codigo_patrimonial.'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Código digital: </b><span>'.$r->codigo_digital.'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Nombre: </b><span>'.$r->nombre.'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Nombre sugerido: </b><span>'.$r->nombre_sugerido.'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Extensión: </b><span>'.$r->numero_extension.'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Estado conservación: </b><span>'.$r->estado_convervacion.'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Observaciones: </b><span>'.$r->observaciones.'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Estado: </b><span>'.$estado.'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Portada: </b><span>'.($r->portada != null?'
                                        <img class="img-circle" src="assets/archivos-fichas-tecnicas/'.$r->codigo.'/thumbnails/sm-'.$r->portada.'?nocache='.time().'" alt="Foto">':'').'</span></td></tr>
                                </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                      <div class="card">
                        <div class="card-header" id="descripcion_documental">
                          <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseDescripcionDocumental" aria-expanded="false" aria-controls="collapseDescripcionDocumental">
                              DESCRIPCIÓN DOCUMENTAL
                            </button>
                          </h5>
                        </div>
                        <div id="collapseDescripcionDocumental" class="collapse" aria-labelledby="descripcion_documental" data-parent="#accordion">
                          <div class="card-body">
                            <table class="table">
                                <tdoby>
                                    <tr><td style="padding: 0.5em;"><b>Fondo: </b><span>'.$r->fondo.'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Colección: </b><span>'.$r->coleccion.'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Asunto / tema: </b><span>'.$r->asunto_tema.'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Lugar emisión: </b><span>'.$r->lugar_emision.'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Toponimia: </b><span>'.$this->MostrarJSON($r->toponimia,'nombre').'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Generadores: </b><span>'.$this->MostrarJSON($r->generadores,'nombres').'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Personajes: </b><span>'.$this->MostrarJSON($r->personajes,'nombres').'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Idiomas: </b><span>'.$this->MostrarJSON($r->idiomas,'nombre').'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Años críticos: </b><span style="display: flex;
    flex-wrap: wrap;">'.$this->MostrarTagsJSON($r->anios_criticos,'anio').'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Palabras claves: </b><span style="display: flex;
    flex-wrap: wrap;">'.$this->MostrarTagsJSON($r->palabras_claves,'nombre').'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Descripción: </b><span>'.$r->descripcion.'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Transcripción: </b><span>'.$r->transcripcion.'</span></td></tr>
                                </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                      <div class="card">
                        <div class="card-header" id="ubicacion">
                          <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseUbicacion" aria-expanded="false" aria-controls="collapseUbicacion">
                              UBICACIÓN Y ESPECIFICACIÓN TÉCNICA
                            </button>
                          </h5>
                        </div>
                        <div id="collapseUbicacion" class="collapse" aria-labelledby="ubicacion" data-parent="#accordion">
                            <div class="card-body">
                                <table class="table">
                                    <tdoby>
                                        <tr><td style="padding: 0.5em;"><b>Archivo: </b><span>'.$r->archivo.'</span></td></tr>
                                        <tr><td style="padding: 0.5em;"><b>Seccción: </b><span>'.$r->seccion.'</span></td></tr>
                                        <tr><td style="padding: 0.5em;"><b>Nivel: </b><span>'.$r->nivel.'</span></td></tr>
                                        <tr><td style="padding: 0.5em;"><b>Contenedor: </b><span>'.$r->contenedor.'</span></td></tr>
                                        <tr><td style="padding: 0.5em;"><b>Formato: </b><span>'.$this->MostrarJSON($r->formatos,'nombre').'</span></td></tr>
                                        <tr><td style="padding: 0.5em;"><b>Tipo material soporte: </b><span>'.$this->MostrarJSON($r->tipo_material_soporte,'nombre').'</span></td></tr>
                                        <tr><td style="padding: 0.5em;"><b>Material soporte: </b><span>'.$this->MostrarJSON($r->material_soporte,'nombre').'</span></td></tr>
                                        <tr><td style="padding: 0.5em;"><b>Materiales documento: </b><span>'.$this->MostrarJSON($r->materiales_documentos,'nombre').'</span></td></tr>
                                        <tr><td style="padding: 0.5em;"><b>Largo: </b><span>'.$r->largo.'</span></td></tr>
                                        <tr><td style="padding: 0.5em;"><b>Ancho: </b><span>'.$r->ancho.'</span></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                      </div>
                      <div class="card">
                        <div class="card-header" id="archivos">
                          <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseArchivos" aria-expanded="false" aria-controls="collapseArchivos">
                              ARCHIVOS
                            </button>
                          </h5>
                        </div>
                        <div id="collapseArchivos" class="collapse" aria-labelledby="archivos" data-parent="#accordion">
                          <div class="card-body">
                            <div class="form-group row scroll">
                            '.$this->VerArchivosDetalles($r->archivos_documentos,FALSE).'
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                ';
                }
                echo json_encode(array("estado"=>true,"datos"=>$datos));
            }else{
                echo json_encode(array("estado"=>false,"observacion"=>'No se reconoce la petición.'));
            }
        }else{
            echo json_encode(array("estado"=>false,"observacion"=>'Acceso denegado. Primero debes iniciar sesión'));
        }
    }

    public function VerDocumentoModificar(){
        if(isset($_SESSION['idusuario'])){
            if(isset($_POST['idfichatecnica'])) {
                $x = new Documento();
                $data = array();
                $x->idfichatecnica = $this->acceso->Crypto('decrypt',$_POST['idfichatecnica']);
                foreach ($this->documento->VerDetalleDocumento($x) as $r) {
                    array_push($data, array(
                        "idfichatecnica"=>$this->acceso->Crypto('encrypt',$r->idfichatecnica),
                        "numero_ficha"=>$r->numero,
                        "codigo_institucional"=>$r->codigo_institucional,
                        "codigo_patrimonial"=>$r->codigo_patrimonial,
                        "codigo_digital"=>$r->codigo_digital,
                        "nombre"=>$r->nombre,
                        "nombre_sugerido"=>$r->nombre_sugerido,
                        "numero_extension"=>$r->numero_extension,
                        "estado_conservacion"=>$this->acceso->Crypto('encrypt',$r->idestado_conservacion),
                        "portada"=>$r->portada!=null?"assets/archivos-fichas-tecnicas/".$r->codigo.'/thumbnails/sm-'.$r->portada."?nocache=".time():NULL,
                        "path"=>$r->portada,
                        "observaciones"=>$r->observaciones,
                        "estado"=>$r->estado,
                        
                        "fondo"=>$this->acceso->Crypto('encrypt',$r->idfondo),
                        "coleccion"=>$this->acceso->Crypto('encrypt',$r->idcoleccion),
                        "asunto_tema"=>$r->asunto_tema,
                        "lugar_emision"=>$r->lugar_emision,
                        "toponimia"=>$this->EncriptarIds($r->toponimia),
                        "generadores"=>$this->EncriptarIds($r->generadores),
                        "personajes"=>$this->EncriptarIds($r->personajes),
                        "idiomas"=>$this->EncriptarIds($r->idiomas),
                        "anios_criticos"=>$this->CrearTagsJSON($r->anios_criticos,'anio'),
                        "palabras_claves"=>$this->CrearTagsJSON($r->palabras_claves,'nombre'),
                        "descripcion"=>$r->descripcion,
                        "transcripcion"=>$r->transcripcion,

                        "archivo"=>$this->acceso->Crypto('encrypt',$r->idarchivo),
                        "seccion"=>$this->acceso->Crypto('encrypt',$r->idseccion),
                        "nivel"=>$this->acceso->Crypto('encrypt',$r->idnivel),
                        "contenedor"=>$this->acceso->Crypto('encrypt',$r->idcontenedor),
                        "formatos"=>$this->EncriptarIds($r->formatos),
                        "tipo_material_soporte"=>$this->EncriptarIds($r->tipo_material_soporte),
                        "material_soporte"=>$this->EncriptarIds($r->material_soporte),
                        "materiales_documentos"=>$this->EncriptarIds($r->materiales_documentos),
                        "largo"=>$r->largo,
                        "ancho"=>$r->ancho,
                        "archivos_documentos"=>$this->VerArchivosDetalles($r->archivos_documentos,TRUE),
                    ));
                }
                echo json_encode(array("estado"=>true,'datos'=>$data));
            }else{
                echo json_encode(array("estado"=>false,"observacion"=>'No se reconoce la petición.'));
            }
        }else{
            echo json_encode(array("estado"=>false,"observacion"=>'Acceso denegado. Primero debes iniciar sesión'));
        }
    }

    public function ActualizarDocumentos(){
        if($this->acceso->ComprobarAcceso($_GET,$_POST['accion'],FALSE)){
            try {
                $x = new Documento();
                $x->id = $this->acceso->Crypto('decrypt',$_POST['codigo']);
                $x->numero_documento = strip_tags($_POST['numero_documento']);
                $x->codigo_institucional = strip_tags($_POST['codigo_institucional_documento']);
                $x->codigo_patrimonial = strip_tags($_POST['codigo_patrimonial_documento']);
                $x->codigo_digital = strip_tags($_POST['codigo_digital_documento']);
                $x->nombre = strip_tags($_POST['nombre_documento']);
                $x->nombre_sugerido = strip_tags($_POST['nombre_sugerido_documento']);
                $x->extension = strip_tags($_POST['extension_documento']);
                $x->estado_conservacion = $this->acceso->Crypto('decrypt',$_POST['estado_conservacion_documento']);
                $x->accion = strip_tags($_POST['accion']);
                
                $arch = $this->SubirFotoPortada($_POST['nombreFoto'],$_POST['vaFoto'],$_POST['eliminarFoto'],$_FILES['portada_documento'],$x->accion,$x->id);
                
                $x->portada = $arch["nombrefoto"];
                $x->ruta = $arch["ruta"];
                $x->observaciones = strip_tags($_POST['observaciones_documento']);
                $x->estado= strip_tags($_POST['estado_documento'])=="on"?1:0;
                
                $x->coleccion = isset($_POST['coleccion_documento'])?$this->acceso->Crypto('decrypt',$_POST['coleccion_documento']):null;
                $x->asunto_tema = strip_tags($_POST['asunto_tema_documento']);
                $x->lugar_emision = strip_tags($_POST['lugar_emision_documento']);
                $x->toponimia = $this->DesencriptarIds($_POST['toponimia_documento']);
                $x->generadores = $this->DesencriptarIds($_POST['generadores_documento']);
                $x->personajes = $this->DesencriptarIds($_POST['personajes_documento']);
                $x->idiomas = $this->DesencriptarIds($_POST['idiomas_documento']);
                $x->anios_criticos = $this->CrearJSON($_POST['anos_criticos_documento'],'anio');
                $x->palabras_claves = $this->CrearJSON($_POST['palabras_claves_documento'],'nombre');
                $x->descripcion = strip_tags($_POST['descripcion_documento']);
                $x->transcripcion = strip_tags($_POST['transcripcion_documento']);
                
                $x->contenedor = isset($_POST['contenedor_documento'])?$this->acceso->Crypto('decrypt',$_POST['contenedor_documento']):null;
                $x->formato = $this->DesencriptarIds($_POST['formato_documento']);
                $x->tipo_material = $this->DesencriptarIds($_POST['tipo_material_documento']);
                $x->material_soporte = $this->DesencriptarIds($_POST['material_soporte_documento']);
                $x->material_documento=$this->DesencriptarIds($_POST['material_documento_documento']);
                $x->largo = strip_tags($_POST['largo_documento']);
                $x->ancho = strip_tags($_POST['ancho_documento']);
                
                $x->estado_verificacion = strip_tags($_POST['estado_verificacion_documento']);
                $x->extensiones_archivos = strip_tags($_POST['archivos']);
                
                $x->usuario=$this->acceso->Crypto('decrypt',$_SESSION['idusuario']);
                
                #print_r($x);
                foreach (json_decode($this->documento->ActualizarDocumentos($x)[0]->resultado) as $r):
                    try {
                        if(!$r->estado){
                            $this->RollbackArchivosCreados($x->extensiones_archivos,$x->accion,$x->id);
                            $this->RollbackArchivosCreados($x->portada,$x->accion,$x->id);
                        }
                        echo json_encode(array("estado"=>$r->estado,'observacion'=>$r->observacion));
                    } catch (Exception $e) {
                        $this->RollbackArchivosCreados($x->extensiones_archivos,$x->accion,$x->id);
                        $this->RollbackArchivosCreados($x->portada,$x->accion,$x->id);
                    }
                    #sleep(1);
                endforeach;
            }catch (Exception $e) {
                $this->RollbackArchivosCreados($x->extensiones_archivos,$x->accion,$x->id);
                $this->RollbackArchivosCreados($x->portada,$x->accion,$x->id);
                echo json_encode(array("estado"=>false,"observacion"=>"Ocurrió un error inesperado al guardar el documento. Contáctese con el administradorde la aplicación."));
            }
        }
    }

    private function EncriptarIds($parametros){
        if(json_encode($parametros)){
            foreach ((array)json_decode($parametros) as $r) {
                $dato[] = $this->acceso->Crypto('encrypt',$r->id);
            }
        }
        return $dato;
    }
    private function DesencriptarIds($parametro){
        $datos = null;
        foreach (explode(",", strip_tags($parametro)) as $r) {
            $datos .= $this->acceso->Crypto('decrypt',$r).',';
        }
        return rtrim($datos,',');    
    }

    private function CrearJSON($datos,$variable){
        if($datos){
            foreach (explode(",", strip_tags($datos)) as $r) {
                $res .= '{"'.$variable.'": "'.TRIM($r).'"},';
            }
            return '['.rtrim($res,',').']';
        }else{
            return "[{}]";
        }
    }

    private function MostrarJSON($parametros,$variable){
        if(json_encode($parametros)){
          foreach ((array)json_decode($parametros) as $r) {
            $dato .= ''.$r->$variable.', ';
          }
        }
        return $dato?rtrim($dato,", ").".":"";
      }

    private function CrearTagsJSON($parametros,$variable){
        if(json_encode($parametros)){
            foreach ((array)json_decode($parametros) as $r) {
                $dato .= '"'.$r->$variable.'",';
            }
        return '['.rtrim($dato,',').']';
        }
    }

    private function MostrarTagsJSON($parametros,$variable){
        if(json_encode($parametros)){
            foreach ((array)json_decode($parametros) as $r) {
                $dato .= '<span class="tag label label-info" style="margin-right: 3px;margin-bottom: 3px;">'.$r->$variable.'</span>';
            }
        }
        return $dato;
    }

    private function MostrarTagsJSONLector($parametros,$variable){
        if(json_encode($parametros)){
            foreach ((array)json_decode($parametros) as $r) {
                $dato .= '<span class="badge badge-danger" style="margin-right: 2px;">'.$r->$variable.'</span>';
            }
        }
        return $dato;
    }

    private function RollbackArchivosCreados($archivos,$accion,$iddocumento){
        if($archivos){
            $x = new Documento();
            $x->accion = $accion;
            $x->iddocumento = $iddocumento;
            $ruta = "assets/archivos-fichas-tecnicas/";
            foreach (json_decode($this->documento->VerRutaDocumentos($x)[0]->resultado) as $r) {
                $ruta .= $r->observacion."/";
                if (!file_exists($ruta))mkdir($ruta, 0777, true);
                if (!file_exists($ruta."thumbnails/"))mkdir($ruta."thumbnails/", 0777, true);
            }
            var_dump($ruta);
            foreach (explode(",", strip_tags($archivos)) as $r) {
                if(file_exists($ruta.$r)){
                    chmod( $ruta.$r, 0777 );
                    unlink( $ruta.$r );
                }
                if(file_exists($ruta.'thumbnails/sm-'.$r)){
                    chmod( $ruta.'thumbnails/sm-'.$r, 0777 );
                    unlink( $ruta.'thumbnails/sm-'.$r );
                }
            }
        }
    }

    private function VerArchivosDetalles($parametros,$borrar){
        if(json_encode($parametros)){
            foreach ((array)json_decode($parametros) as $r) {
                $ext = pathinfo($r->nombre, PATHINFO_EXTENSION);
                $dato .= '
                    <div class="col-md-2 col-sm-2 col-lg-2 col-xs-2" style="margin-bottom: 10px;" title="'.$r->nombre.'">
                      <div class="card text-muted">
                        '.$this->VerFormatoArchivo($ext,$r->ruta,$r->nombre).'
                        <div class="card-block truncar-texto-imagen">'.$r->nombre.'
                        </div>'.($borrar?
                        '<div class="card-block">
                          <button class="btn btn-sm btn-danger eliminar_archivo pull-right" type="button" data-toggle="modal" data-target="#frm_eliminar_archivo" title="Eliminar archivo" data-nombre="'.$r->nombre.'"
                              data-id="'.$this->acceso->Crypto('encrypt',$r->iddocumento).'"
                            title="'.$r->nombre.'"><i class="fa fa-trash"></i></button>
                        </div>':'').'
                      </div>
                    </div>';
            }
        }else{
            $dato = '<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12" style="margin-bottom: 10px;">
                <center>No hay archivos registrados para este documento.</center>
            </div>';
        }
        return $dato;
    }
    protected function VerFormatoArchivo($ext,$ruta,$archivo){
        $ext = strtolower($ext);
        if(file_exists($ruta.$archivo) || file_exists($ruta."thumbnails/sm-".$archivo)){
          if(preg_match('/(doc|docx)/', $ext)){
            return '<i class="fa fa-file-word text-primary fa-4x text-center" style="margin-bottom: 10px;"></i>';
          }elseif(preg_match('/(xls|xlsx)/', $ext)){
            return '<i class="fa fa-file-excel text-success fa-4x text-center" style="margin-bottom: 10px;"></i>';
          }elseif(preg_match('/(ppt|pptx)/', $ext)){
            return '<i class="fa fa-file-powerpoint text-danger fa-4x text-center" style="margin-bottom: 10px;"></i>';
          }elseif(preg_match('/(pdf)/', $ext)){
            return '<i class="fa fa-file-pdf text-danger fa-4x text-center" style="margin-bottom: 10px;"></i>';
          }elseif(preg_match('/(zip|rar|tar|gzip|gz|7z)/', $ext)){
            return '<i class="fa fa-file-archive text-muted fa-4x text-center" style="margin-bottom: 10px;"></i>';
          }elseif(preg_match('/(htm|html)/', $ext)){
            return '<i class="fa fa-file-code text-info fa-4x text-center" style="margin-bottom: 10px;"></i>';
          }elseif(preg_match('/(txt|ini|csv|java|php|js|css)/', $ext)){
            return '<i class="fa fa-file-text text-info fa-4x text-center" style="margin-bottom: 10px;"></i>';
          }elseif(preg_match('/(avi|mpg|mkv|mov|mp4|3gp|webm|wmv)/', $ext)){
            return '<i class="fa fa-file-movie text-warning fa-4x text-center" style="margin-bottom: 10px;"></i>';
          }elseif(preg_match('/(mp3|wav)/', $ext)){
            return '<i class="fa fa-file-audio text-warning fa-4x text-center" style="margin-bottom: 10px;"></i>';
          }elseif($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg' || $ext == 'bmp' || $ext == 'bwmp' || $ext == 'ico'){
            if(file_exists($ruta."thumbnails/sm-".$archivo)){
                return '<img class="card-img-top" alt="Archivo subido" src="'.$ruta.'/thumbnails/sm-'.$archivo.'">';
            }else{
                return '<img class="card-img-top" alt="Archivo subido" src="'.$ruta.$archivo.'">'; 
            }
          }else{
            return '<i class="fa fa-file fa-4x text-center" style="margin-bottom: 10px;"></i>';
          }
        }else{
          return '<i class="fa fa-ban text-danger fa-4x text-center" style="margin-bottom: 10px;" title="Archivo no existe"></i>';
        }
    }
    private function SubirFotoPortada($nombre_foto,$va_foto,$eliminar_foto,$archivo,$accion,$iddocumento){
        try {
            $x = new Documento();
            $x->iddocumento = $iddocumento;
            $x->accion = $accion;
            $nombrefoto = $nombre_foto;
            $ruta = "assets/archivos-fichas-tecnicas/";
            foreach (json_decode($this->documento->VerRutaDocumentos($x)[0]->resultado) as $r) {
                $ruta .= $r->observacion."/";
                if (!file_exists($ruta))mkdir($ruta, 0777, true);
                if (!file_exists($ruta."thumbnails/"))mkdir($ruta."thumbnails/", 0777, true);
            }
            switch ($accion) {
                case 'registrar':
                    if ($va_foto == 'true'){
                        $portada = $this->ComprimirArchivos($archivo['name'],$archivo['type'],$archivo['tmp_name'],$ruta);
                        if(!$portada[0]["estado"]){
                            $nombrefoto = $portada[0]["observacion"]; exit;
                        }else {$nombrefoto = $portada[0]["nombre"];}
                    }
                    break;
                case 'modificar':
                    if ($va_foto == 'true'){
                        if(file_exists($ruta.$nombrefoto)){
                            chmod( $ruta.$nombrefoto, 0777 );
                            unlink( $ruta.$nombrefoto );
                        }
                        if(file_exists($ruta.'thumbnails/sm-'.$nombrefoto)){
                            chmod( $ruta.'thumbnails/sm-'.$nombrefoto, 0777 );
                            unlink( $ruta.'thumbnails/sm-'.$nombrefoto );
                        }
                        $portada = $this->ComprimirArchivos($archivo['name'],$archivo['type'],$archivo['tmp_name'],$ruta);
                        if(!$portada[0]["estado"]){
                            $nombrefoto = $portada[0]["observacion"]; exit;
                        }else {$nombrefoto = $portada[0]["nombre"];}
                    }elseif($eliminar_foto == 'true'){
                        if(file_exists($ruta.$nombrefoto)){
                            chmod( $ruta.$nombrefoto, 0777 );
                            unlink( $ruta.$nombrefoto );
                        }
                        if(file_exists($ruta.'thumbnails/sm-'.$nombrefoto)){
                            chmod( $ruta.'thumbnails/sm-'.$nombrefoto, 0777 );
                            unlink( $ruta.'thumbnails/sm-'.$nombrefoto );
                        }
                        $nombrefoto = null;
                    }
                    break;
                default:
                    $nombrefoto = null;
                    break;
            }
            return array("nombrefoto"=>$nombrefoto,"ruta"=>$ruta);
        } catch (Exception $e) {
            return 'No se guardó la fotografía porque sucedió un error inesperado.';
        }
    }

    public function CargarDocumentos(){
        $x = new Documento();
        $x->id_fondo = $this->acceso->Crypto('decrypt',$_POST['fondo_documento']);
        $datos = '<option value="0" selected>*Seleccionar colección:</option>';
        if($x->id_fondo != 0 || $x->id_fondo != null){
            $resultado = $this->documento->CargarDocumentos($x);
            foreach ($resultado as $r) {
                $datos .= '<option value="'.$this->acceso->Crypto('encrypt',$r->id).'">'.$r->nombre.'</option>';
            }
        }
        echo json_encode($datos);
    }

    public function SubirArchivos(){
        if(isset($_SESSION['idusuario'])){
            if(isset($_POST['accion'])) {
                $x = new Documento();
                $x->iddocumento = $this->acceso->Crypto('decrypt',$_POST['iddocumento']);
                $x->accion = strip_tags($_POST["accion"]);
                $ruta = "assets/archivos-fichas-tecnicas/";
                foreach (json_decode($this->documento->VerRutaDocumentos($x)[0]->resultado) as $r) {
                    $ruta .= $r->observacion."/";
                    if (!file_exists($ruta)){
                        mkdir($ruta, 0777, true);
                    }
                    if (!file_exists($ruta."thumbnails/")){
                        mkdir($ruta."thumbnails/", 0777, true);
                    }
                }
                $estado = true;
                $archivos = array();
                $img = count(isset($_FILES['archivos']['name'])?$_FILES['archivos']['name']:0);
                for($i = 0; $i < $img; $i++) {
                    $archivo = $this->ComprimirArchivos(
                        $_FILES['archivos']['name'][$i],
                        $_FILES['archivos']['type'][$i],
                        $_FILES['archivos']['tmp_name'][$i],
                        $ruta);
                    if(!$archivo[0]["estado"]){
                        $estado = $archivo[0]["estado"];
                        $archivo = $archivo[0]["observacion"];
                        exit;
                    }else{
                        array_push($archivos, $archivo[0]["nombre"]);
                    }
                    $archivo = "";
                }
                echo json_encode(array("estado"=>$estado,"archivos"=>$archivos,"observacion"=>$observacion));
            }else{
                echo json_encode(array("estado"=>false,"observacion"=>'No se recibieron los datos correctamente. Vuelva a realizar la petición.'));
            }
        }else{
          echo json_encode(array("estado"=>false,"observacion"=>'Acceso denegado, primero debes iniciar sesión.'));
        }
    }

    public function EliminarArchivos(){
        if(isset($_SESSION['idusuario'])){
            if(isset($_POST['iddocumento'])){
                $x = new Documento();
                $x->iddocumento = $this->acceso->Crypto("decrypt",$_POST["iddocumento"]);
                $x->accion = "eliminar";
                $x->nombre = strip_tags($_POST["nombre"]);
                $x->usuario=$this->acceso->Crypto('decrypt',$_SESSION['idusuario']);
                $ruta = "assets/archivos-fichas-tecnicas/";
                
                foreach (json_decode($this->documento->VerRutaDocumentos($x)[0]->resultado) as $r) {
                    $ruta .= $r->observacion."/";
                }
                foreach (json_decode($this->documento->EliminarArchivos($x)[0]->resultado) as $s):
                    if($s->estado){
                        if(file_exists($ruta.$x->nombre)){
                            unlink($ruta.$x->nombre);
                        }
                        if(file_exists($ruta.'/thumbnails/sm-'.$x->nombre)){
                            unlink($ruta.'/thumbnails/sm-'.$x->nombre);
                        }
                    }
                    echo json_encode(array("estado"=>$r->estado,"observacion"=>$s->observacion));
                endforeach;
            }else{
                echo json_encode(array("estado"=>$r->estado,"observacion"=>'No se recibieron los datos correctamente. Vuelva a realizar la petición.'));
            }
        }else{
          echo json_encode(array("estado"=>$r->estado,"observacion"=>'Acceso denegado, primero debes iniciar sesión.'));
        }
    }

    public function CargarLibro(){
        if(isset($_SESSION['idusuario'])){
            if(isset($_POST['iddocumento'])) {
                $x = new Documento();
                $x->iddocumento = $this->acceso->Crypto('decrypt',$_POST['iddocumento']);
                $x->usuario=$this->acceso->Crypto('decrypt',$_SESSION['idusuario']);
                $paginas = array();
                $total = 0;
                foreach ($this->documento->CargarLibro($x)[1] as $r) {
                    array_push($paginas, "assets/archivos-fichas-tecnicas/".$r->codigo."/".$r->portada);
                    if(json_encode($r->archivos_documentos)){
                        foreach ((array)json_decode($r->archivos_documentos) as $s) {
                            $tipo = pathinfo($s->nombre, PATHINFO_EXTENSION);
                            array_push($paginas, "assets/archivos-fichas-tecnicas/".$r->codigo."/".$s->nombre);
                            $total++;
                        }
                    }
                }    
                $datos = array("tipo"=>$tipo,"paginas"=>$paginas,"total_paginas"=>$total);
                echo json_encode(array("estado"=>true,"datos"=>$datos));
            }else{
                echo json_encode(array("estado"=>false,"observacion"=>'No se reconoce la petición.'));
            }
        }else{
            echo json_encode(array("estado"=>false,"observacion"=>'Acceso denegado. Primero debes iniciar sesión'));
        }
    }

    private function ComprimirArchivos($nombre,$tipo,$tmp_name,$ruta){
        $retorno = array();

        $contac = date("YmdHis").rand(100, 999);
        $nombre_archivo = isset($nombre)?$nombre:null;
        $nombre_original = $nombre_archivo;
        $nombre_archivo = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $nombre_archivo);
        $nombre_archivo = str_replace('ñ', '.', $nombre_archivo);
        $nombre_archivo = str_replace("'", "", $nombre_archivo);
        $nombre_archivo = str_replace("#", "", $nombre_archivo);

        $tmp = isset($tmp_name) ? $tmp_name : null;
        $tipo = pathinfo($nombre, PATHINFO_EXTENSION);

        $solonombre = str_replace($tipo, "", $nombre_archivo);
        $nombre_archivo = $ruta.$solonombre."_".$contac.'.'.$tipo;
        $nombre_archivo_thumb = $ruta."thumbnails/sm-".$solonombre."_".$contac.'.'.$tipo;

        $nombre = $solonombre."_".$contac.'.'.$tipo;
        if(strtolower($tipo) == 'jpg' || strtolower($tipo) == 'jpeg' || strtolower($tipo) == 'png' || strtolower($tipo) == 'gif' || strtolower($tipo) == 'bmp'){
            if(number_format((filesize($tmp)/1024)/1000,3) > 1.000){
                $this->EscribirArchivo($tmp,$nombre,$nombre_archivo_thumb,256);
                $retorno = $this->EscribirArchivo($tmp,$nombre,$nombre_archivo,1024);
            }else{
                $this->EscribirArchivo($tmp,$nombre,$nombre_archivo_thumb,256);
                if(move_uploaded_file($tmp,$nombre_archivo)){
                    array_push($retorno, array("estado"=>TRUE,"nombre"=>$nombre,"observacion"=>NULL));
                }else{
                    array_push($retorno, array("estado"=>FALSE,"nombre",NULL,"observacion"=>"No se pudo subir el archivo <b>".$nombre_original.".</b> Contáctese con el administrador de la aplicación."));
                    exit;
                }
            }
        }else{
            if(move_uploaded_file($tmp,$nombre_archivo)){
                array_push($retorno, array("estado"=>TRUE,"nombre"=>$nombre,"observacion"=>NULL));
            }else{
                array_push($retorno, array("estado"=>FALSE,"nombre",NULL,"observacion"=>"No se pudo subir el archivo <b>".$nombre_original.".</b> Contáctese con el administrador de la aplicación."));
                exit;
            }
        }
        return $retorno;
    }
    
    protected function EscribirArchivo($tmp,$nombre,$nombre_archivo,$tamano){
        $retorno = array();
        $src_properties = getimagesize($tmp);
        $image_type = $src_properties[2];
        if( $image_type == IMAGETYPE_JPEG ) {   
          $img_src = imagecreatefromjpeg($tmp);  
          $tg_layer = $this->fn_resize($img_src,$src_properties[0],$src_properties[1],$tamano);
          imagejpeg($tg_layer,$nombre_archivo);
          array_push($retorno, array("estado"=>TRUE,"nombre"=>$nombre,"observacion"=>NULL));
        }elseif( $image_type == IMAGETYPE_GIF )  {  
          $img_src = imagecreatefromgif($tmp);
          $tg_layer = $this->fn_resize($img_src,$src_properties[0],$src_properties[1],$tamano);
          imagegif($tg_layer,$nombre_archivo);
          array_push($retorno, array("estado"=>TRUE,"nombre"=>$nombre,"observacion"=>NULL));
        }elseif( $image_type == IMAGETYPE_PNG ) {
          $img_src = imagecreatefrompng($tmp); 
          $tg_layer = $this->fn_resize($img_src,$src_properties[0],$src_properties[1],$tamano);
          imagepng($tg_layer,$nombre_archivo);
          array_push($retorno, array("estado"=>TRUE,"nombre"=>$nombre,"observacion"=>NULL));
        }elseif( $image_type == IMAGETYPE_BMP ) {
          $img_src = imagecreatefrombmp($tmp); 
          $tg_layer = $this->fn_resize($img_src,$src_properties[0],$src_properties[1],$tamano);
          imagebmp($tg_layer,$nombre_archivo);
          array_push($retorno, array("estado"=>TRUE,"nombre"=>$nombre,"observacion"=>NULL));
        }
        return $retorno;
    }

    protected function fn_resize($img_src,$width,$height,$tamano) {
        $target_width = $tamano;
        $target_height=($height/$width)*$target_width;
        $tg_layer=imagecreatetruecolor($target_width,$target_height);
        imagecopyresampled($tg_layer,$img_src,0,0,0,0,$target_width,$target_height, $width,$height);
        return $tg_layer;
    }

    public function BuscarDocumentos(){
        if ($_POST['palabraBuscar']) {
            $x = new Documento();
            $x->frase_buscada = strip_tags($_POST['palabraBuscar']);
            $resultado = $this->documento->BuscarDocumentos($x);
            echo json_encode(array("documento"=>$resultado));
            foreach ($resultado as $r){
               
            }
        }

    }

}
