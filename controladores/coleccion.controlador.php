<?php

require 'modelos/coleccion.modelo.php';
require 'controladores/acceso.controlador.php';

Class ColeccionControlador{
    private $coleccion;
    private $acceso;

    private $data = array();
    private $id = 0;

    public function __CONSTRUCT(){
        $this->coleccion = new Coleccion();
        $this->acceso = new AccesoControlador();
    }
    Public function FrmColecciones(){
        if($this->acceso->ComprobarAcceso($_GET,NULL,TRUE)){
            require_once 'vistas/admin/header.php';
            require_once 'vistas/admin/colecciones.php';
            require_once 'vistas/admin/footer.php';
        }
    }
    public function MostrarColecciones(){
        if($this->acceso->ComprobarAcceso($_GET,$_POST['id'],FALSE)){
            $x = new Coleccion();
            foreach ($this->coleccion->MostrarColecciones($x) as $r) {
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
                    'fecha_registro'=> $r->fecha_registro,
                    'fondo'=> $r->fondo,
                    'estado'=> $estado,
                    'accion'=> ('<button class="btn boton_modificar_coleccion" data-toggle="modal" data-target="#frm_actualizar_colecciones" type="button" id="'.$this->acceso->Crypto('encrypt',$r->id).'"><i class="fa fa-edit"></i></button>'),
                );
            }
            if(!$data) $data = '';
			$results = array("data"	=>	$data);
		    echo json_encode($results);
         }
    }

    public function ActualizarColecciones(){
		if($this->acceso->ComprobarAcceso($_GET,$_POST['accion'],FALSE)){
			$x = new Coleccion();
			$x->id = $this->acceso->Crypto('decrypt',$_POST['codigo']);
            $x->idfondo = $this->acceso->Crypto('decrypt',$_POST['fondo_coleccion']);
            $x->fecharegistro = strip_tags($_POST['fecha_registro_coleccion']);
			$x->nombre = strip_tags($_POST['nombre_coleccion']);
			$x->descripcion = strip_tags($_POST['descripcion_coleccion']);
            $x->estado=strip_tags($_POST['estado_coleccion'])=="on"?1:0;
            $x->accion=strip_tags($_POST['accion']);
            $x->usuario=$this->acceso->Crypto('decrypt',$_SESSION['idusuario']);
            foreach (json_decode($this->coleccion->ActualizarColecciones($x)[0]->resultado) as $r):endforeach;
            echo json_encode(array('estado'=>$r->estado,'observacion'=>$r->observacion));
        }
    }

    public function CargarColecciones(){
        $x = new Coleccion();
        $x->id_fondo = $this->acceso->Crypto('decrypt',$_POST['fondo_coleccion']);
        $datos = '<option value="0" selected>Seleccionar colección:</option>';
        if($x->id_fondo != 0 || $x->id_fondo != null){
            $resultado = $this->coleccion->CargarColecciones($x);
            foreach ($resultado as $r) {
                $datos .= '<option value="'.$this->acceso->Crypto('encrypt',$r->id).'">'.$r->nombre.'</option>';
            }
        }
        echo json_encode($datos);
    }
}
