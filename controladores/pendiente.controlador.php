<?php

require 'modelos/pendiente.modelo.php';
require 'controladores/acceso.controlador.php';

Class PendienteControlador{
    private $documento;
    private $acceso;
    private $data = array();
    private $id = 0;

    public function __CONSTRUCT(){
        $this->pendiente = new Pendiente();
        $this->acceso = new AccesoControlador();
    }
    Public function FrmPendientes(){
        if($this->acceso->ComprobarAcceso($_GET,NULL,TRUE)){
            switch ($_SESSION['rol']) {
                case 'ADMINISTRADOR' OR 'DIGITADOR':
                    require_once 'vistas/admin/header.php';
                    require_once 'vistas/admin/pendientes.php';
                    require_once 'vistas/admin/footer.php';
                    break;
                case 'LECTOR':
                    #require_once 'vistas/lector/header.php';
                    require_once 'vistas/lector/index.html';
                    #require_once 'vistas/lector/footer.php';
                    break;

                default:
                    # code...
                    break;
            }
        }
    }

    public function MostrarPendientes(){
        if($this->acceso->ComprobarAcceso($_GET,$_POST['id'],FALSE)){
            $x = new Pendiente();
            foreach ($this->pendiente->MostrarPendientes($x) as $r) {
                $id++;
                if($r->estado){
                    $estado = '<small class="badge badge-success">ACTIVO</small>';
                }else{
                    $estado = '<small class="badge badge-dark">INACTIVO</small>';
                }
                $data[] = array(
                    'num'=> $id,
                    'num_ficha' =>  $r->numero,
                    'nombre' => $r->nombre,
                    'creador'=>$r->creador,
                    'estado'=> $estado,
                    'accion'=> '<button class="btn boton_ver_ficha" type="button" id="'.$this->acceso->Crypto('encrypt',$r->idfichatecnica).'"><i class="fa fa-eye"></i></button>
                        <button class="btn boton_aprobar_ficha" type="button" id="'.$this->acceso->Crypto('encrypt',$r->idfichatecnica).'" data-toggle="modal" data-target="#frm_aprobar_ficha" style="margin-left: 6px;"><i class="fa fa-check"></i></button>
                        <button class="btn boton_reprobar_ficha" type="button" id="'.$this->acceso->Crypto('encrypt',$r->idfichatecnica).'" data-toggle="modal" data-target="#frm_reprobar_ficha" style="margin-left: 6px;"><i class="fa fa-ban"></i></button>',
                );
            }
            if(!$data) $data = '';
            $results = array("data" =>  $data);
            echo json_encode($results);
         }
    }

    public function VerDetallePendiente(){
        if(isset($_SESSION['idusuario'])){
            if(isset($_POST['idfichatecnica'])) {
                $x = new Pendiente();
                $x->idfichatecnica = $this->acceso->Crypto('decrypt',$_POST['idfichatecnica']);
                foreach ($this->pendiente->VerDetallePendiente($x) as $r) {
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
                                    <tr><td style="padding: 0.5em;"><b>N° Ficha: </b><br><span>'.$r->numero.'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Código institucional: </b><br><span>'.$r->codigo_institucional.'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Código patrimonial: </b><br><span>'.$r->codigo_patrimonial.'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Código digital: </b><br><span>'.$r->codigo_digital.'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Nombre: </b><br><span>'.$r->nombre.'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Nombre sugerido: </b><br><span>'.$r->nombre_sugerido.'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Extensión: </b><br><span>'.$r->numero_extension.'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Estado conservación: </b><br><span>'.$r->estado_conservacion.'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Observaciones: </b><br><span>'.$r->observaciones.'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Estado: </b><br><span>'.$estado.'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Portada: </b><br><span>'.($r->portada != null?'
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
                                    <tr><td style="padding: 0.5em;"><b>Fondo: </b><br><span>'.$r->fondo.'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Colección: </b><br><span>'.$r->coleccion.'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Asunto / tema: </b><br><span>'.$r->asunto_tema.'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Lugar emisión: </b><br><span>'.$r->lugar_emision.'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Toponimia: </b><br><span>'.$this->MostrarJSON($r->toponimia,'nombre').'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Generadores: </b><br><span>'.$this->MostrarJSON($r->generadores,'nombres').'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Personajes: </b><br><span>'.$this->MostrarJSON($r->personajes,'nombres').'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Idiomas: </b><br><span>'.$this->MostrarJSON($r->idiomas,'nombre').'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Años críticos: </b><br><span>'.$this->MostrarTagsJSON($r->anios_criticos,'anio').'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Palabras claves: </b><br><span>'.$this->MostrarTagsJSON($r->palabras_claves,'nombre').'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Descripción: </b><br><span>'.$r->descripcion.'</span></td></tr>
                                    <tr><td style="padding: 0.5em;"><b>Transcripción: </b><br><span>'.$r->transcripcion.'</span></td></tr>
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
                                        <tr><td style="padding: 0.5em;"><b>Archivo: </b><br><span>'.$r->archivo.'</span></td></tr>
                                        <tr><td style="padding: 0.5em;"><b>Seccción: </b><br><span>'.$r->seccion.'</span></td></tr>
                                        <tr><td style="padding: 0.5em;"><b>Nivel: </b><br><span>'.$r->nivel.'</span></td></tr>
                                        <tr><td style="padding: 0.5em;"><b>Contenedor: </b><br><span>'.$r->contenedor.'</span></td></tr>
                                        <tr><td style="padding: 0.5em;"><b>Formato: </b><br><span>'.$this->MostrarJSON($r->formatos,'nombre').'</span></td></tr>
                                        <tr><td style="padding: 0.5em;"><b>Tipo material soporte: </b><br><span>'.$this->MostrarJSON($r->tipo_material_soporte,'nombre').'</span></td></tr>
                                        <tr><td style="padding: 0.5em;"><b>Material soporte: </b><br><span>'.$this->MostrarJSON($r->material_soporte,'nombre').'</span></td></tr>
                                        <tr><td style="padding: 0.5em;"><b>Materiales documento: </b><br><span>'.$this->MostrarJSON($r->materiales_documentos,'nombre').'</span></td></tr>
                                        <tr><td style="padding: 0.5em;"><b>Largo: </b><br><span>'.$r->largo.'</span></td></tr>
                                        <tr><td style="padding: 0.5em;"><b>Ancho: </b><br><span>'.$r->ancho.'</span></td></tr>
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
                echo json_encode(array('estado'=>true,"datos"=>$datos));
            }else{
                echo json_encode(array('estado'=>false,"datos"=>'No se reconoce la petición.'));
            }
        }else{
            echo json_encode(array('estado'=>false,"datos"=>'Acceso denegado. Primero debes iniciar sesión'));
        }
    }

    public function AprobarDocumentos(){
        if($this->acceso->ComprobarAcceso($_GET,$_POST['idfichatecnica'],FALSE)){
            $x = new Pendiente();
            $x->revisor=$this->acceso->Crypto('decrypt',$_SESSION['idusuario']);
            $x->idfichatecnica = $this->acceso->Crypto('decrypt',$_POST['idfichatecnica']);
            $x->usuario=$this->acceso->Crypto('decrypt',$_SESSION['idusuario']);
            foreach (json_decode($this->pendiente->AprobarDocumentos($x)[0]->resultado) as $r):
                echo json_encode(array('estado'=>$r->estado,'observacion'=>$r->observacion));
            endforeach;
        }
    }

    public function ReprobarDocumentos(){
        if($this->acceso->ComprobarAcceso($_GET,$_POST['idfichatecnica'],FALSE)){
            $x = new Pendiente();
            $x->revisor=$this->acceso->Crypto('decrypt',$_SESSION['idusuario']);
            $x->idfichatecnica = $this->acceso->Crypto('decrypt',$_POST['idfichatecnica']);
            $x->observaciones = strip_tags($_POST['observaciones_pendiente']);
            $x->usuario=$this->acceso->Crypto('decrypt',$_SESSION['idusuario']);
            foreach (json_decode($this->pendiente->ReprobarDocumentos($x)[0]->resultado) as $r):
                echo json_encode(array('estado'=>$r->estado,'observacion'=>$r->observacion));
            endforeach;
        }
    }

    private function MostrarJSON($parametros,$variable){
        if(json_encode($parametros)){
            foreach ((array)json_decode($parametros) as $r) {
                $dato .= '- '.$r->$variable.'.<br>';
            }
        }
        return $dato;
    }

    private function MostrarTagsJSON($parametros,$variable){
        if(json_encode($parametros)){
            foreach ((array)json_decode($parametros) as $r) {
                $dato .= '<span class="tag label label-info" style="margin-right: 3px;">'.$r->$variable.'</span>';
            }
        }
        return $dato;
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
}