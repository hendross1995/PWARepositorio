<?php

require 'modelos/favorito.modelo.php';
require 'controladores/acceso.controlador.php';

Class FavoritoControlador {
	private $favorito;
	private $acceso;

	private $data = array();
	private $id = 0;

	public function __CONSTRUCT(){
		$this->favorito = new Favorito();
		$this->acceso = new AccesoControlador();
	}
	public function FrmFavoritos(){
	    if($this->acceso->ComprobarAcceso($_GET,NULL,TRUE)){
	      	switch ($_SESSION['rol']) {
		        case 'ADMINISTRADOR':
		          	require_once 'vistas/admin/header.php';
		          	require_once 'vistas/admin/favoritos.php';
		          	require_once 'vistas/admin/footer.php';
		          	break;
				case 'LECTOR':
					require_once 'vistas/lector/header.php';
					require_once 'vistas/lector/favoritos.php';
				  	require_once 'vistas/lector/footer.php';
					break;
		        default:
		          # code...
		          break;
	      	}
	    }
  	}
	public function MostrarFavoritos(){
		if($this->acceso->ComprobarAcceso($_GET,$_POST['id'],FALSE)){
			$x = new Favorito();
			$x->usuario = 'usuarios_favoritos @> '."'".'[{"idusuario": "'.$this->acceso->Crypto("decrypt",$_SESSION["idusuario"]).'"}]'."'".'';
			foreach ($this->favorito->MostrarFavoritos($x) as $r) {
				$id++;
				if($r->estado){
						$estado = '<small class="badge badge-success">ACTIVO</small>';
				}else{
						$estado = '<small class="badge badge-dark">INACTIVO</small>';
				}
				$data[] = array(
					'num'=> $id,
					'nombre' => '<img class="img-circle" src="assets/archivos-fichas-tecnicas/'.$r->codigo.'/thumbnails/sm-'.$r->portada.'?nocache='.time().'" alt="Foto" style="width: 4%;"> '.$r->nombre,
					'nombre_sugerido' => $r->nombre_sugerido,
					'fondo' => $r->fondo,
					'coleccion' => $r->coleccion,
					'asunto_tema' => $r->asunto_tema,
					'lugar_emision' => $r->lugar_emision,
					'toponimia' => $this->MostrarJSON($r->toponimia,'nombre'),
					'generadores' => $this->MostrarJSON($r->generadores,'nombres'),
					'personajes' => $this->MostrarJSON($r->personajes,'nombres'),
					'idiomas' => $this->MostrarJSON($r->idiomas,'nombre'),
					'material_soporte' => $this->MostrarJSON($r->material_soporte,'nombre'),
					'materiales_documentos' => $this->MostrarJSON($r->materiales_documentos,'nombre'),
					'anios_criticos' => $this->MostrarTagsJSONLector($r->anios_criticos,'anio'),
					'palabras_claves' => $this->MostrarTagsJSONLector($r->palabras_claves,'nombre'),
					'descripcion' => $r->descripcion,
					'transcripcion' => $r->transcripcion,
					'accion'=> '
						<div class="dropdown dropleft">
						    <button class="btn btn-sm btn-outline-danger dropdown-toggle waves-effect" type="button" id="dropdownMenu1" data-toggle="dropdown"
						      aria-haspopup="true" aria-expanded="false">Opciones</button>
						    <div class="dropdown-menu dropdown-primary">
						      <a class="dropdown-item boton_ver_documento" id="'.$this->acceso->Crypto('encrypt',$r->iddocumento).'"><i class="fa fa-bookmark"></i> Visualizar documento</a>
						      <a class="dropdown-item quitar_favorito_documento" id="'.$this->acceso->Crypto('encrypt',$r->iddocumento).'"><i class="fa fa-star"></i> Quitar de favoritos</a>
						      <!--<a class="dropdown-item imprimir_documento" id="'.$this->acceso->Crypto('encrypt',$r->iddocumento).'"><i class="fa fa-print"></i> Imprimir ficha</a>-->
						    </div>
					  	</div>',
				);
			}
			if(!$data) $data = "";
			$results = array("data"	=> $data);
			echo json_encode($results);
		}
	}
	public function CargarFavoritos(){
		if($this->acceso->ComprobarAcceso($_GET,$_POST['id'],FALSE)){
			$id = 1;
			foreach ($this->favorito->TotalFavoritos() as $r) {
				$data[] = array(
					'num'=> $id++,
					'ficha' => $r->numero,
					'nombre' => $r->nombre,
					'total' => $r->total_favoritos
				);
			}
			if(!$data) $data = "";
			$results = array("data"	=> $data);
			echo json_encode($results);
		}
	}
	public function ActualizarFavoritos(){
		if(isset($_SESSION['idusuario'])){
			if(isset($_POST['iddocumento'])) {
				$x = new Favorito();
				$x->usuario = $this->acceso->Crypto('decrypt',$_SESSION['idusuario']);
				$x->iddocumento = $this->acceso->Crypto('decrypt',$_POST['iddocumento']);
				#$x->accion_favorito = strip_tags($_POST['accion_favorito']);
				foreach (json_decode($this->favorito->ActualizarFavoritos($x)[0]->resultado) as $r) {}
				echo json_encode(array("estado" => $r->estado,"accion"=>$r->accion,"observacion" => $r->observacion));
			}else{
                echo json_encode(array("estado"=>false,"observacion"=>'No se reconoce la petición.'));
            }
		}else{
			echo json_encode(array("estado"=>false,"observacion"=>'Acceso denegado. Primero debes iniciar sesión'));
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
  	private function MostrarTagsJSONLector($parametros,$variable){
      	if(json_encode($parametros)){
          	foreach ((array)json_decode($parametros) as $r) {
          		if('<span class="badge badge-danger" style="margin-right: 2px;">'.$r->$variable.'</span>' !=
          			'<span class="badge badge-danger" style="margin-right: 2px;"></span>'){
      				$dato .= '<span class="badge badge-danger" style="margin-right: 2px;">'.$r->$variable.'</span>';
      			}
          	}
      	}
      	return trim($dato)?trim($dato):"";
  	}
}
?>
