<?php 
require 'modelos/ficha.modelo.php';
require 'modelos/acceso.modelo.php';

Class FichaControlador{
	private $ficha;
	private $acceso;
	private $data = array();
	private $id = 0;

	public function __CONSTRUCTOR(){
		$this->ficha = new Ficha();
		$this->acceso = new AccesoControlador();
	}
	public function FrmFichas(){
        if($this->acceso->ComprobarAcceso($_GET,NULL,TRUE)){
            require_once 'vistas/admin/header.php';
            require_once 'vistas/admin/fichas.php';
            require_once 'vistas/admin/footer.php';
        }
	}
	public function MostrarFichas(){
		if ($this->acceso->ComprobarAcceso($_GET,$_POST['id'],FALSE)) {
			$x = new Ficha();
			foreach (json_decode($this->ficha->MostrarFichas()[0]->resultado) as $r) {
				if ($r->estado) {}
				echo json_encode(array('estado' => $r->estado,'observacion' => $r->observacion));
			}
		}
	}
	public function ActualizarFicha(){
		if ($this->acceso->ComprobarAcceso($_GET,$_POST['id'],FALSE)) {

		}
	}

}

 ?>