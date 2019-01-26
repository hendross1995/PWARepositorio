<?php

require 'modelos/inicioAdministrador.modelo.php'; 
require 'controladores/acceso.controlador.php';

Class inicioAdminControlador{ 
    private $documento;
    private $acceso;
    private $data = array();
    private $id = 0;

    public function __CONSTRUCT(){
        $this->incio = new inicioAdmin();
        $this->acceso = new AccesoControlador();
    }
    Public function FrmInicioAdmin(){
        if($this->acceso->ComprobarAcceso($_GET,NULL,TRUE)){
            switch ($_SESSION['rol']) { 
                case 'ADMINISTRADOR' OR 'DIGITADOR':
                    require_once 'vistas/admin/header.php';
                    require_once 'vistas/admin/home.php';
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
    public function CargarUsuariosCantidad(){
        $x = new inicioAdmin();
        $x-> MostrarFondos();
        
        echo json_encode("hola");
    }

}
