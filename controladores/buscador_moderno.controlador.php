<?php

require 'modelos/buscador_moderno.modelo.php';
require 'controladores/acceso.controlador.php';

Class BuscadorControlador{
    private $documento;
    private $acceso;
    private $data = array();
    private $id = 0;

    public function __CONSTRUCT(){ 
        $this->buscador = new Buscador();
        $this->acceso = new AccesoControlador();
    }
    public function FrmBuscador(){
      if($this->acceso->ComprobarAcceso($_GET,NULL,TRUE)){
        require_once 'vistas/admin/header.php';
        require_once 'vistas/admin/documentos.php';
        require_once 'vistas/admin/footer.php'; 
      }
    }
    
 private function dscpt($dato){
     $resultado = $dato;
      if(is_array($resultado)){ 
        $idsDesencript = array();
         if(!empty($resultado)) 
           foreach ($resultado as $id)   
             array_push($idsDesencript,$this->acceso->Crypto('decrypt',$id));
           $resultado = $idsDesencript; 
     }else $resultado = $this->acceso->Crypto('decrypt',$resultado);
    return  $resultado;   
  }    

  private function ecpt($dato){ 
    return $this->acceso->Crypto('encrypt',$dato); 
  }   
    
  private function consulta($sql){
    $cadena = "";
    foreach ($sql as $elem){
      if($cadena == ""){
          if($elem != "") 
              $cadena = "AND {$elem}";
      }else if($elem != "") $cadena = "{$cadena} AND {$elem}"; 
    }
    return $cadena;
  }

  public function ListarDocumentos(){
    if(isset($_SESSION['idusuario'])){
      if(isset($_POST['accion'])) {
        $pagina = 1;
        $frase = '';
        $itemsPorPagina = 5;
        
        $x = new Buscador();
            
        $fondo = strip_tags($_POST['fondo']);
        $coleccion = strip_tags($_POST['coleccion']);
        $generadores = strip_tags($_POST['generadores']);
        $personajes = strip_tags($_POST['personajes']);
        $toponimia = strip_tags($_POST['toponimia']);
        $material_soporte = strip_tags($_POST['material_soporte']);
        $material_documento = strip_tags($_POST['material_documento']);
        $idioma = strip_tags($_POST['idioma']);
        $anosCriticos = strip_tags($_POST['anosCriticos']); 
        $palabrasClaves = strip_tags($_POST['palabrasClaves']);
        
       if (strip_tags($_POST['palabra']) !== "ninguno") {
            $frase = strip_tags($_POST['palabra']);
        }
        
        if ($_POST['pagina'] !== "ninguno") {
            $pagina = $_POST['pagina'];
        }
        
        $x->fondo = 
        ($fondo == "ninguno" ? $fondo : $this->dscpt($fondo));  
        $x->coleccion = 
        ($coleccion == "ninguno" ? $coleccion : $this->dscpt($coleccion));
        $x->generadores = 
        ($generadores == "ninguno" ? $generadores : $this->dscpt(explode( ',', $generadores))); 
        $x->personajes = 
        ($personajes == "ninguno" ? $personajes : $this->dscpt(explode( ',', $personajes))); 
        $x->toponimia = 
        ($toponimia == "ninguno" ? $toponimia : $this->dscpt(explode( ',', $toponimia))); 
        $x->idioma = 
        ($idioma == "ninguno" ? $idioma : $this->dscpt(explode( ',', $idioma)));
        $x->material_soporte = 
        ($material_soporte == "ninguno" ? $material_soporte : $this->dscpt(explode( ',', $material_soporte)));
        $x->material_documento = 
        ($material_documento == "ninguno" ? $material_documento : $this->dscpt(explode( ',', $material_documento)));
        $x->anosCriticos = 
        ($anosCriticos == "ninguno" ? $anosCriticos :  explode( ',',$anosCriticos));
        $x->palabrasClaves = 
        ($palabrasClaves == "ninguno" ? $palabrasClaves :  explode( ',',$palabrasClaves)); 
        
        if($x->generadores != "ninguno"){
          if(!empty($x->generadores))
            $generadoresVec = array();
            foreach ($x->generadores as $topo){
              array_push($generadoresVec,'generadores @> '."'".'[{"id": "'.$topo.'"}]'."'".'');
            }   
        }
        if($x->personajes != "ninguno"){
          if(!empty($x->personajes))
            $personajesVec = array();
            foreach ($x->personajes as $topo){
              array_push($personajesVec,'personajes @> '."'".'[{"id": "'.$topo.'"}]'."'".'');
            }   
        }
        if($x->toponimia != "ninguno"){
          if(!empty($x->toponimia))
            $topoVec = array();
            foreach ($x->toponimia as $topo){
              array_push($topoVec,'toponimia @> '."'".'[{"id": "'.$topo.'"}]'."'".'');
            }   
        }
        if($x->material_soporte != "ninguno"){
          if(!empty($x->material_soporte))
            $matVecS = array();
            foreach ($x->material_soporte as $mat){
              array_push($matVecS,'material_soporte @> '."'".'[{"id": "'.$mat.'"}]'."'".'');
            }    
        }

        if($x->material_documento != "ninguno"){
          if(!empty($x->material_documento))
            $matVecD = array();
            foreach ($x->material_documento as $mat){
              array_push($matVecD,'materiales_documentos @> '."'".'[{"id": "'.$mat.'"}]'."'".'');
            }    
        }
        
        if($x->idioma != "ninguno"){
          if(!empty($x->idioma))
            $idiVec = array();
            foreach ($x->idioma as $idio){
              array_push($idiVec,'idiomas @> '."'".'[{"id": "'.$idio.'"}]'."'".'');
            }    
        }
        
        if($x->anosCriticos != "ninguno"){
          if(!empty($x->anosCriticos))
            $anosVec = array();
            foreach ($x->anosCriticos as $anos){
              array_push($anosVec,'anios_criticos @> '."'".'[{"anio": "'.$anos.'"}]'."'".'');
            }    
        }
        if($x->palabrasClaves != "ninguno"){
          if(!empty($x->palabrasClaves))
            $palabraClaveVec = array();
            foreach ($x->palabrasClaves as $palabra){
              array_push($palabraClaveVec,'palabras_claves @> '."'".'[{"nombre": "'.$palabra.'"}]'."'".'');
            }    
        }
      
        switch($x->fondo){   
          case "ninguno": $x->fondo = "";break;  
          default: $x->fondo = "idfondo = {$x->fondo}"; 
        }
        switch($x->coleccion){   
          case "ninguno": $x->coleccion = ""; break;  
          default: $x->coleccion = "idcoleccion = {$x->coleccion}";
        }
        switch($x->generadores){   
          case "ninguno": $x->generadores = "";break;  
          default: $x->generadores = implode(" OR ",$generadoresVec);
        }
        switch($x->personajes){   
          case "ninguno": $x->personajes = "";break;  
          default: $x->personajes = implode(" OR ",$personajesVec);
        }
        switch($x->toponimia){   
          case "ninguno": $x->toponimia = "";break;  
          default: $x->toponimia = implode(" OR ",$topoVec);
        }
        switch($x->idioma){   
          case "ninguno": $x->idioma = "";break;  
          default: $x->idioma = implode(" OR ",$idiVec);
        }
        switch($x->material_soporte){   
          case "ninguno": $x->material_soporte = ""; break;  
          default: $x->material_soporte = implode(" OR ",$matVecS);
        }
        switch($x->material_documento){   
          case "ninguno": $x->material_documento = ""; break;  
          default: $x->material_documento = implode(" OR ",$matVecD);
        }
        switch($x->anosCriticos){   
            case "ninguno": $x->anosCriticos = "";break;  
          default: $x->anosCriticos = implode(" OR ",$anosVec);
        }
        switch($x->palabrasClaves){   
            case "ninguno": $x->palabrasClaves = "";break;  
          default: $x->palabrasClaves = implode(" OR ",$palabraClaveVec);
        }
        
        $x->frase_buscada = strip_tags($frase);
        $x->page = ($pagina -1)* $itemsPorPagina; 
        $x->itemsPorPage = $itemsPorPagina;
        
        $x->sql = $this->consulta(array(
                        'fondo' => $x->fondo,
                        'coleccion' => $x->coleccion,
                        'generadores' => $x->generadores ? "(".$x->generadores.")" : "",
                        'personajes' => $x->personajes ? "(".$x->personajes.")" : "",
                        'toponomia' => $x->toponimia ? "(".$x->toponimia.")" : "",
                        'material_soporte' => $x->material_soporte ? "(".$x->material_soporte.")" : "",
                        'materiales_documentos' => $x->material_documento ? "(".$x->material_documento.")" : "",
                        'idioma' => $x->idioma ? "(".$x->idioma.")" : "",
                        'anos' => $x->anosCriticos ? "(".$x->anosCriticos.")" : "",
                        'palabras_claves' => $x->palabrasClaves ? "(".$x->palabrasClaves.")" : ""
                      ));
          
        $resultado = $this->buscador->ListarDocumentos($x);
        $total = $resultado[1][0]->count;
        $paginas = ceil($total/$itemsPorPagina);
        foreach ($resultado[0] as $r) {
            $id ++;
            $data .= '
                    <div style="background: #fff;margin-bottom: 8px;">
                      <div class="card-header" role="tab" id="headingOne">
                          <div class="media">
                              <a data-toggle="collapse" href="#collapseOne'.$id.'" aria-expanded="true" aria-controls="collapseOne">
                              <img class="mr-3" src="assets/archivos-fichas-tecnicas/'.$r->codigo."/".$r->portada.'" alt="Portada" class="img-thumbnail" style="width: 100px"> </a>
                              <div class="media-body">
                                  <a data-toggle="collapse" href="#collapseOne'.$id.'" aria-expanded="true" aria-controls="collapseOne" class="text-danger">
                                  <span>'.$r->nombre.'</span></a>
                                  <div class=""><small><strong>Nombre sugerido: </strong>'.$r->nombre_sugerido.'</small></div>
                                  <div class=""><small><strong>Fondo: </strong>'.$r->fondo.'</small></div>
                                  <div class=""><small><strong>Colección: </strong>'.$r->coleccion.'</small></div>
                                  <div><small><strong>Generadores: </strong>'.$this->MostrarJSON($r->generadores,'nombres').'</small></div>
                              </div>
                          </div>
                      </div>
                      <div id="collapseOne'.$id.'" class="collapse" role="tabpanel" aria-labelledby="headingOne" data-parent="#documentosAcordeon">
                          <div class="card-body" style="padding-top: 0px;">
                              <div class="text-justify"><small><b>Asunto / Tema:</b> '.$r->asunto_tema.'</small></div><hr style="margin-top: 0;margin-bottom: 0;">
                              <div class="text-justify"><small><b>Lugar emisión:</b> '.$r->lugar_emision.'</small></div><hr style="margin-top: 0;margin-bottom: 0;">
                              <div class="text-justify"><small><b>Toponimia:</b> '.$this->MostrarJSON($r->toponimia,'nombre').'</small></div><hr style="margin-top: 0;margin-bottom: 0;">
                              <div class="text-justify"><small><b>Personajes:</b> '.$this->MostrarJSON($r->personajes,'nombres').'</small></div><hr style="margin-top: 0;margin-bottom: 0;">
                              <div class="text-justify"><small><b>Idiomas:</b> '.$this->MostrarJSON($r->idiomas,'nombre').'</small></div><hr style="margin-top: 0;margin-bottom: 0;">
                              <div class="text-justify"><small><b>Material de soporte:</b> '.$this->MostrarJSON($r->material_soporte,'nombre').'</small></div><hr style="margin-top: 0;margin-bottom: 0;">
                              <div class="text-justify"><small><b>Material del documento:</b> '.$this->MostrarJSON($r->materiales_documentos,'nombre').'</small></div><hr style="margin-top: 0;margin-bottom: 0;">
                              <div class="text-justify"><small><b>Años críticos:</b> '.$this->MostrarTagsJSONLector($r->anios_criticos,'anio').'</small></div><hr style="margin-top: 0;margin-bottom: 0;">
                              <div class="text-justify"><small><b>Palabras claves:</b> '.$this->MostrarTagsJSONLector($r->palabras_claves,'nombre').'</small></div><hr style="margin-top: 0;margin-bottom: 0;">
                              <div class="text-justify"><small><b>Descripción:</b> '.$r->descripcion.'</small></div><hr style="margin-top: 0;margin-bottom: 0;">
                              <div class="text-justify"><small><b>Transcripción:</b> '.$r->transcripcion.'</small></div>
                              <div class="card-footer" style="background: #fff;">
                                  '.$this->VerDocumentoFavorito($r->iddocumento,$r->usuarios_favoritos).'
                                  <button type="button" class="btnVerDocumento btn btn-outline-danger btn-sm" id="'.$this->acceso->Crypto('encrypt',$r->iddocumento).'"><i class="fa fa-bookmark"></i> VISUALIZAR DOCUMENTO</button>
                              </div>
                          </div>
                      </div>
                   </div>';
        }
      
        if($data != null) $data = '<div class="card resultados_finales" style="background: transparent;">'.$data.'</div>';   
        
        echo json_encode(
          array(
           'contenido'=> $data,
           'total'=>$total,
           'total_paginas'=>count($resultado[0]),
           'paginas'=>ceil($total/$itemsPorPagina),
           'consulta'=> $resultado[2],
           "estado" => TRUE
         )
        );
      }else{
        echo json_encode(array("estado"=>false,"observacion"=>'No se reconoce la petición.'));
      }
    }else{
      echo json_encode(array("estado"=>false,"observacion"=>'Acceso denegado. Primero debes iniciar sesión'));
    }
  }

  public function MostrarDocumentosRelevantes(){
    if(isset($_SESSION['idusuario'])){
      if(isset($_POST['accion'])) {
        foreach ($this->buscador->MostrarDocumentosRelevantes() AS $r) {
          $id ++;
          $data .= '
          <div style="background: #fff;margin-bottom: 8px;">
            <div class="card-header" role="tab" id="headingOne">
                <div class="media">
                    <a data-toggle="collapse" href="#collapseOne'.$id.'" aria-expanded="true" aria-controls="collapseOne">
                    <img class="mr-3" src="assets/archivos-fichas-tecnicas/'.$r->codigo."/".$r->portada.'" alt="Portada" class="img-thumbnail" style="width: 100px"> </a>
                    <div class="media-body">
                        <a data-toggle="collapse" href="#collapseOne'.$id.'" aria-expanded="true" aria-controls="collapseOne" class="text-danger">
                        <span>'.$r->nombre.'</span></a>
                        <div class=""><small><strong>Nombre sugerido: </strong>'.$r->nombre_sugerido.'</small></div>
                        <div class=""><small><strong>Fondo: </strong>'.$r->fondo.'</small></div>
                        <div class=""><small><strong>Colección: </strong>'.$r->coleccion.'</small></div>
                        <div><small><strong>Generadores: </strong>'.$this->MostrarJSON($r->generadores,'nombres').'</small></div>
                    </div>
                </div>
            </div>
            <div id="collapseOne'.$id.'" class="collapse" role="tabpanel" aria-labelledby="headingOne" data-parent="#documentosAcordeon">
                <div class="card-body" style="padding-top: 0px;">
                    <div class="text-justify"><small><b>Asunto / Tema:</b> '.$r->asunto_tema.'</small></div><hr style="margin-top: 0;margin-bottom: 0;">
                    <div class="text-justify"><small><b>Lugar emisión:</b> '.$r->lugar_emision.'</small></div><hr style="margin-top: 0;margin-bottom: 0;">
                    <div class="text-justify"><small><b>Toponimia:</b> '.$this->MostrarJSON($r->toponimia,'nombre').'</small></div><hr style="margin-top: 0;margin-bottom: 0;">
                    <div class="text-justify"><small><b>Personajes:</b> '.$this->MostrarJSON($r->personajes,'nombres').'</small></div><hr style="margin-top: 0;margin-bottom: 0;">
                    <div class="text-justify"><small><b>Idiomas:</b> '.$this->MostrarJSON($r->idiomas,'nombre').'</small></div><hr style="margin-top: 0;margin-bottom: 0;">
                    <div class="text-justify"><small><b>Material de soporte:</b> '.$this->MostrarJSON($r->material_soporte,'nombre').'</small></div><hr style="margin-top: 0;margin-bottom: 0;">
                    <div class="text-justify"><small><b>Material del documento:</b> '.$this->MostrarJSON($r->materiales_documentos,'nombre').'</small></div><hr style="margin-top: 0;margin-bottom: 0;">
                    <div class="text-justify"><small><b>Años críticos:</b> '.$this->MostrarTagsJSONLector($r->anios_criticos,'anio').'</small></div><hr style="margin-top: 0;margin-bottom: 0;">
                    <div class="text-justify"><small><b>Palabras claves:</b> '.$this->MostrarTagsJSONLector($r->palabras_claves,'nombre').'</small></div><hr style="margin-top: 0;margin-bottom: 0;">
                    <div class="text-justify"><small><b>Descripción:</b> '.$r->descripcion.'</small></div><hr style="margin-top: 0;margin-bottom: 0;">
                    <div class="text-justify"><small><b>Transcripción:</b> '.$r->transcripcion.'</small></div>
                    <div class="card-footer" style="background: #fff;">
                        '.$this->VerDocumentoFavorito($r->iddocumento,$r->usuarios_favoritos).'
                        <button type="button" class="btnVerDocumento btn btn-outline-danger btn-sm" id="'.$this->acceso->Crypto('encrypt',$r->iddocumento).'"><i class="fa fa-bookmark"></i> VISUALIZAR DOCUMENTO</button>
                    </div>
                </div>
            </div>
         </div>';
        }
        if($data != null) $data = '<div class="card resultados_finales" style="background: transparent;">'.$data.'</div>';   
        echo json_encode(
          array(
           "contenido"=> $data,
           "estado" => TRUE
         )
        );
      }else{
        echo json_encode(array("estado"=>false,"observacion"=>'No se reconoce la petición.'));
      }
    }else{
        echo json_encode(array("estado"=>false,"observacion"=>'Acceso denegado. Primero debes iniciar sesión'));
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
              $res .= '{"'.$variable.'": "'.$r.'"},';
          }
          return "[".rtrim($res,",")."]";
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
      return "[".rtrim($dato,",")."]";
      }
  }

  private function MostrarTagsJSON($parametros,$variable){
      if(json_encode($parametros)){
          foreach ((array)json_decode($parametros) as $r) {
              $dato .= '<span class="tag label label-info" style="margin-right: 3px;">'.$r->$variable.'</span>';
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

  private function VerDocumentoFavorito($iddocumento,$usuarios){
    $favorito = FALSE;
    if(json_encode($usuarios)){
      foreach ((array)json_decode($usuarios) as $r) {
        if($this->acceso->Crypto("decrypt",$_SESSION['idusuario']) == $r->idusuario){
          $favorito = TRUE;
        }
      }
    }
    return ($favorito?'<button type="button" class="btn-favorito btn btn-outline-danger btn-sm" id="'.$this->acceso->Crypto('encrypt',$iddocumento).'" es-favorito="'.$favorito.'"><i class="fa fa-star"></i> QUITAR DE FAVORITOS</button>':'<button type="button" class="btn-favorito btn btn-outline-danger btn-sm" id="'.$this->acceso->Crypto('encrypt',$iddocumento).'" es-favorito="'.$favorito.'"><i class="fa fa-star-o"></i> AGREGAR A FAVORITOS</button>');
  }
}
