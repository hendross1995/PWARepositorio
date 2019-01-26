<?php

require 'modelos/archivo.modelo.php';
require 'controladores/acceso.controlador.php';

Class ArchivoControlador{
    private $archivo;
    private $acceso;

    private $data = array();
    private $id = 0;

    public function __CONSTRUCT(){
        $this->archivo = new Archivo();
        $this->acceso = new AccesoControlador();
    }
    Public function FrmArchivos(){
        if($this->acceso->ComprobarAcceso($_GET,NULL,TRUE)){
            require_once 'vistas/admin/header.php';
            require_once 'vistas/admin/archivos.php';
            require_once 'vistas/admin/footer.php';
        }
    }
    public function MostrarArchivos(){
        if($this->acceso->ComprobarAcceso($_GET,$_POST['id'],FALSE)){
            $x = new Archivo();
            foreach ($this->archivo->MostrarArchivos($x) as $r) {
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
                    'accion'=> ('<button class="btn boton_modificar_archivo" data-toggle="modal" data-target="#frm_actualizar_archivos" type="button" id="'.$this->acceso->Crypto('encrypt',$r->id).'"><i class="fa fa-edit"></i></button>'),
                );
            }
            if(!$data) $data = '';
			$results = array(
			    "data"	=>	$data
			);
		    echo json_encode($results);
         }
    }

    public function ActualizarArchivos(){
		if($this->acceso->ComprobarAcceso($_GET,$_POST['accion'],FALSE)){
    			$x = new Archivo();
    			$x->id = $this->acceso->Crypto('decrypt',$_POST['codigo']);
    			$x->nombre = strip_tags($_POST['nombre_archivo']);
                $x->estado=strip_tags($_POST['estado_archivo'])=="on"?1:0;
                $x->accion=strip_tags($_POST['accion']);
                $x->usuario=$this->acceso->Crypto('decrypt',$_SESSION['idusuario']);
                foreach (json_decode($this->archivo->ActualizarArchivos($x)[0]->resultado) as $r):endforeach;
                echo json_encode(array('estado'=>$r->estado,'observacion'=>$r->observacion));
        }
    }

    public function CargarArchivos(){
        $x = new Archivo();
        $datos = '<option value="0" selected>Seleccionar archivo:</option>';
        foreach ($this->archivo->CargarArchivos() as $r) {
            $datos .= '<option value="'.$this->acceso->Crypto('encrypt',$r->id).'">'.$r->nombre.'</option>';
        }
        echo json_encode($datos);
    }
}
