<?php

require 'modelos/materialsoporte.modelo.php';
require 'controladores/acceso.controlador.php';

Class MaterialSoporteControlador{
    private $materialsoporte;
    private $acceso;

    private $data = array();
    private $id = 0;

    public function __CONSTRUCT(){
        $this->materialsoporte = new MaterialSoporte();
        $this->acceso = new AccesoControlador();
    }
    Public function FrmMaterialesSoporte(){
        if($this->acceso->ComprobarAcceso($_GET,NULL,TRUE)){
            switch ($_SESSION['rol']) {
                case 'ADMINISTRADOR' OR 'DIGITADOR':
                    require_once 'vistas/admin/header.php';
                    require_once 'vistas/admin/materialsoporte.php';
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
    public function MostrarMaterialesSoporte(){
        if($this->acceso->ComprobarAcceso($_GET,$_POST['id'],FALSE)){
            $x = new MaterialSoporte();
            foreach ($this->materialsoporte->MostrarMaterialesSoporte($x) as $r) {
                $id++;
                if($r->estado){
                    $estado = '<small class="badge badge-success">ACTIVO</small>';
                }else{
                    $estado = '<small class="badge badge-dark">INACTIVO</small>';
                }
                $data[] = array(
                    'num'=> $id,
                    'nombre'=> $r->nombre,
                    'estado'=> $estado,
                    'accion'=> ('<button class="btn boton_modificar_material_soporte" data-toggle="modal" data-target="#frm_actualizar_materiales_soporte" type="button" id="'.$this->acceso->Crypto('encrypt',$r->id).'"><i class="fa fa-edit"></i></button>'),
                );
            }
            if(!$data) $data = '';
			$results = array(
			    "data"	=>	$data
			);
		    echo json_encode($results);
         }
    }

    public function ActualizarMaterialesSoporte(){
		if($this->acceso->ComprobarAcceso($_GET,$_POST['accion'],FALSE)){
    			$x = new MaterialSoporte();
    			$x->id = $this->acceso->Crypto('decrypt',$_POST['codigo']);
    			$x->nombre = strip_tags($_POST['nombre_material_soporte']);
                $x->estado=strip_tags($_POST['estado_material_soporte'])=="on"?1:0;
                $x->accion=strip_tags($_POST['accion']);
                $x->usuario=$this->acceso->Crypto('decrypt',$_SESSION['idusuario']);
                foreach (json_decode($this->materialsoporte->ActualizarMaterialesSoporte($x)[0]->resultado) as $r):endforeach;
                echo json_encode(array('estado'=>$r->estado,'observacion'=>$r->observacion));
        }
    }

    public function CargarMaterialesSoporte(){
        $x = new MaterialSoporte();
        foreach ($this->materialsoporte->CargarMaterialesSoporte() as $r) {
            $datos .= '<option value="'.$this->acceso->Crypto('encrypt',$r->id).'">'.$r->nombre.'</option>';
        }
        echo json_encode($datos);
    }
}
