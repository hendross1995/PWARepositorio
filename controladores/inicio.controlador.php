<?php

require 'modelos/inicio.modelo.php';
require 'controladores/acceso.controlador.php';

Class InicioControlador {
	private $inicio;
	private $acceso;

	public function __CONSTRUCT(){
		$this->inicio = new Inicio();
		$this->acceso = new AccesoControlador();
    }
    public function Inicio(){
    	if ($this->acceso->ComprobarAcceso($_GET,NULL,TRUE)) {
    		switch ($_SESSION['rol']) {
    			case 'ADMINISTRADOR':
    				require_once 'vistas/admin/header.php';
					require_once 'vistas/admin/home.php';
					require_once 'vistas/admin/footer.php';
    				break;
    			case 'LECTOR':
    			require_once 'vistas/lector/header.php';
					require_once 'vistas/lector/home.php';
				  require_once 'vistas/lector/footer.php';
    				break;
				case 'DOCUMENTALISTA':
    				require_once 'vistas/admin/header.php';
					require_once 'vistas/admin/home2.php';
					require_once 'vistas/admin/footer.php';
    				break;

    			default:
    				# code...
    				break;
    		}
    	}
	}
}
?>
