<?php

require 'modelos/fondo.modelo.php';
require 'controladores/acceso.controlador.php';

Class FondoControlador{
    private $fondo;
    private $acceso;

    private $data = array();
    private $id = 0;

    public function __CONSTRUCT(){
        $this->fondo = new Fondo();
        $this->acceso = new AccesoControlador(); 
    }
    Public function FrmFondos(){
        if($this->acceso->ComprobarAcceso($_GET,NULL,TRUE)){
            require_once 'vistas/admin/header.php';
            require_once 'vistas/admin/fondos.php';
            require_once 'vistas/admin/footer.php';
        }
    }
    public function MostrarFondos(){
        if($this->acceso->ComprobarAcceso($_GET,$_POST['id'],FALSE)){
            $x = new Fondo();
            foreach ($this->fondo->MostrarFondos($x) as $r) {
                $id++;
                if($r->estado){
                    $estado = '<small class="badge badge-success">ACTIVO</small>';
                }else{
                    $estado = '<small class="badge badge-dark">INACTIVO</small>';
                }
                $data[] = array(
                    'num'=> $id,
                    'nombre'=> $r->nombre,
                    'descripcion'=> $r->descripcion,
                    'estado'=> $estado,
                    'accion'=> ('<button class="btn boton_modificar_fondo" data-toggle="modal" data-target="#frm_actualizar_fondos" type="button" id="'.$this->acceso->Crypto('encrypt',$r->id).'"><i class="fa fa-edit"></i></button>'),
                );
            }
            if(!$data) $data = '';
			$results = array(
			    "data"	=>	$data
			);
		    echo json_encode($results);
         }
    }  

    public function ActualizarFondos(){
		if($this->acceso->ComprobarAcceso($_GET,$_POST['accion'],FALSE)){
    			$x = new Fondo();
    			$x->id = $this->acceso->Crypto('decrypt',$_POST['codigo']);
    			$x->nombre = strip_tags($_POST['nombre_fondo']);
    			$x->descripcion = strip_tags($_POST['descripcion_fondo']);
                $x->estado=strip_tags($_POST['estado_fondo'])=="on"?1:0;
                $x->accion=strip_tags($_POST['accion']);
                $x->usuario=$this->acceso->Crypto('decrypt',$_SESSION['idusuario']);
                foreach (json_decode($this->fondo->ActualizarFondos($x)[0]->resultado) as $r):endforeach; 
                echo json_encode(array('estado'=>$r->estado,'observacion'=>$r->observacion));
        }
    }

    public function CargarFondos(){
        $x = new Fondo();
        $datos = '<option value="0" selected>Seleccionar fondo:</option>';
        foreach ($this->fondo->CargarFondos() as $r) {
            $datos .= '<option value="'.$this->acceso->Crypto('encrypt',$r->id).'">'.$r->nombre.'</option>';
        }
        echo json_encode($datos);
    }
}
