<?php

require 'modelos/seccion.modelo.php';
require 'controladores/acceso.controlador.php';

Class SeccionControlador{
    private $seccion;
    private $acceso;

    private $data = array();
    private $id = 0;

    public function __CONSTRUCT(){
        $this->seccion = new Seccion();
        $this->acceso = new AccesoControlador();
    }
    Public function FrmSecciones(){
        if($this->acceso->ComprobarAcceso($_GET,NULL,TRUE)){
            switch ($_SESSION['rol']) {
                case 'ADMINISTRADOR' OR 'DIGITADOR':
                    require_once 'vistas/admin/header.php';
                    require_once 'vistas/admin/secciones.php';
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
    public function MostrarSecciones(){
        if($this->acceso->ComprobarAcceso($_GET,$_POST['id'],FALSE)){
            $x = new Seccion();
            foreach ($this->seccion->MostrarSecciones($x) as $r) {
                $id++;
                if($r->estado){
                    $estado = '<small class="badge badge-success">ACTIVO</small>';
                }else{
                    $estado = '<small class="badge badge-dark">INACTIVO</small>';
                }
                $data[] = array(
                    'num'=> $id,
                    'nombre'=> $r->nombre,
                    'archivo'=> $r->archivo,
                    'estado'=> $estado,
                    'accion'=> ('<button class="btn boton_modificar_seccion" data-toggle="modal" data-target="#frm_actualizar_secciones" type="button" id="'.$this->acceso->Crypto('encrypt',$r->id).'"><i class="fa fa-edit"></i></button>'),
                );
            }
            if(!$data) $data = '';
			$results = array("data"	=>	$data);
		    echo json_encode($results);
         }
    }
    public function ActualizarSecciones(){
		if($this->acceso->ComprobarAcceso($_GET,$_POST['accion'],FALSE)){
			$x = new Seccion();
			$x->id = $this->acceso->Crypto('decrypt',$_POST['codigo']);
            $x->idarchivo = $this->acceso->Crypto('decrypt',$_POST['archivo_seccion']);
			$x->nombre = strip_tags($_POST['nombre_seccion']);
            $x->estado=strip_tags($_POST['estado_seccion'])=="on"?1:0;
            $x->accion=strip_tags($_POST['accion']);
            $x->usuario=$this->acceso->Crypto('decrypt',$_SESSION['idusuario']);
            foreach (json_decode($this->seccion->ActualizarSecciones($x)[0]->resultado) as $r):endforeach;
            echo json_encode(array('estado'=>$r->estado,'observacion'=>$r->observacion));
        }
    }

public function CargarSecciones(){
        $x = new Seccion();
        $x->idarchivo = $this->acceso->Crypto('decrypt',$_POST['archivo_seccion']);
        $datos = '<option value="0" selected>Seleccionar sección:</option>';
        if($x->idarchivo != 0 || $x->idarchivo != null){
            $resultado = $this->seccion->CargarSecciones($x);
            foreach ($resultado as $r) {
                $datos .= '<option value="'.$this->acceso->Crypto('encrypt',$r->id).'">'.$r->nombre.'</option>';
            }
        }
        echo json_encode($datos);
    }

}
