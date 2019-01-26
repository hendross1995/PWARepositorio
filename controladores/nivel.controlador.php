<?php

require 'modelos/nivel.modelo.php';
require 'controladores/acceso.controlador.php';

Class NivelControlador{
    private $nivel;
    private $acceso;

    private $data = array();
    private $id = 0;

    public function __CONSTRUCT(){
        $this->nivel = new Nivel();
        $this->acceso = new AccesoControlador();
    }
    Public function FrmNiveles(){
        if($this->acceso->ComprobarAcceso($_GET,NULL,TRUE)){
            switch ($_SESSION['rol']) {
                case 'ADMINISTRADOR' OR 'DIGITADOR':
                    require_once 'vistas/admin/header.php';
                    require_once 'vistas/admin/niveles.php';
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
    public function MostrarNiveles(){
        if($this->acceso->ComprobarAcceso($_GET,$_POST['id'],FALSE)){
            $x = new Nivel();
            foreach ($this->nivel->MostrarNiveles($x) as $r) {
                $id++;
                if($r->estado){
                    $estado = '<small class="badge badge-success">ACTIVO</small>';
                }else{
                    $estado = '<small class="badge badge-dark">INACTIVO</small>';
                }
                $data[] = array(
                    'num'=> $id,
                    'nombre'=> $r->nombre,
                    'seccion'=> $r->seccion,
                    'archivo'=>$r->archivo,
                    'estado'=> $estado,
                    'accion'=> ('<button class="btn boton_modificar_nivel" data-toggle="modal" data-target="#frm_actualizar_niveles" type="button" id="'.$this->acceso->Crypto('encrypt',$r->id).'"><i class="fa fa-edit"></i></button>'),
                );
            }
            if(!$data) $data = '';
			$results = array("data"	=>	$data);
		    echo json_encode($results);
         }
    }
    public function ActualizarNiveles(){
		if($this->acceso->ComprobarAcceso($_GET,$_POST['accion'],FALSE)){
			$x = new Nivel();
			$x->id = $this->acceso->Crypto('decrypt',$_POST['codigo']);
            $x->idseccion = $this->acceso->Crypto('decrypt',$_POST['seccion_nivel']);
			$x->nombre = strip_tags($_POST['nombre_nivel']);
            $x->estado=strip_tags($_POST['estado_nivel'])=="on"?1:0;
            $x->accion=strip_tags($_POST['accion']);
            $x->usuario=$this->acceso->Crypto('decrypt',$_SESSION['idusuario']);
            foreach (json_decode($this->nivel->ActualizarNiveles($x)[0]->resultado) as $r):endforeach;
            echo json_encode(array('estado'=>$r->estado,'observacion'=>$r->observacion));
        }
    }

    public function CargarNiveles(){
        $x = new Nivel();
        $x->idseccion = $this->acceso->Crypto('decrypt',$_POST['seccion_nivel']);
        $datos = '<option value="0" selected>Seleccionar nivel:</option>';
        if($x->idseccion != 0 || $x->idseccion != null){
            $resultado = $this->nivel->CargarNiveles($x);
            foreach ($resultado as $r) {
                $datos .= '<option value="'.$this->acceso->Crypto('encrypt',$r->id).'">'.$r->nombre.'</option>';
            }
        }
        echo json_encode($datos);
    }
}
