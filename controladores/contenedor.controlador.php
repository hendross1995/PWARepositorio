<?php

require 'modelos/contenedor.modelo.php';
require 'controladores/acceso.controlador.php';

Class ContenedorControlador{
    private $contenedor;
    private $acceso;
    private $data = array();
    private $id = 0;

    public function __CONSTRUCT(){
        $this->contenedor = new Contenedor();
        $this->acceso = new AccesoControlador();
    }
    public function FrmContenedores(){
        if($this->acceso->ComprobarAcceso($_GET,NULL,TRUE)){
            require_once 'vistas/admin/header.php';
            require_once 'vistas/admin/contenedores.php';
            require_once 'vistas/admin/footer.php';
        }
    }
    public function MostrarContenedores(){
        if($this->acceso->ComprobarAcceso($_GET,$_POST['id'],FALSE)){
            $x = new Contenedor();
            foreach ($this->contenedor->MostrarContenedores($x) as $r) {
                $id++;
                if($r->estado){
                    $estado = '<small class="badge badge-success">ACTIVO</small>';
                }else{
                    $estado = '<small class="badge badge-dark">INACTIVO</small>';
                }
                $data[] = array(
                    'num'=> $id,
                    'codigo'=> $r->codigo,
                    'nombre'=> $r->nombre,
                    'nivel'=> $r->nivel,
                    'seccion'=>$r->seccion,
                    'archivo'=>$r->archivo,
                    'estado'=> $estado,
                    'accion'=> ('<button class="btn boton_modificar_contenedor" data-toggle="modal" data-target="#frm_actualizar_contenedores" type="button" id="'.$this->acceso->Crypto('encrypt',$r->id).'"><i class="fa fa-edit"></i></button>'),
                );
            }
            if(!$data) $data = '';
			$results = array("data"	=>	$data);
		    echo json_encode($results);
        }
    }
    public function ActualizarContenedores(){
  		if($this->acceso->ComprobarAcceso($_GET,$_POST['accion'],FALSE)){
      			$x = new Contenedor();
      			$x->id = $this->acceso->Crypto('decrypt',$_POST['codigo']);
            $x->idnivel = $this->acceso->Crypto('decrypt',$_POST['nivel_contenedor']);
      			$x->codigo = strip_tags($_POST['codigo_contenedor']);
      			$x->nombre = strip_tags($_POST['nombre_contenedor']);
            $x->estado=strip_tags($_POST['estado_contenedor'])=="on"?1:0;
            $x->accion=strip_tags($_POST['accion']);
            $x->usuario=$this->acceso->Crypto('decrypt',$_SESSION['idusuario']);
            foreach (json_decode($this->contenedor->ActualizarContenedores($x)[0]->resultado) as $r):endforeach;
            echo json_encode(array('estado'=>$r->estado,'observacion'=>$r->observacion));
          }
    }
    public function CargarContenedores(){
        if (isset($_POST['nivel_contenedor'])) {
            $x = new Contenedor();
            $x->idnivel = $this->acceso->Crypto('decrypt',$_POST['nivel_contenedor']);
            $datos = '<option value="0" selected>Seleccionar contenedor:</option>';
            if($x->idnivel != 0 || $x->idnivel != null){
              foreach ($this->contenedor->CargarContenedores($x) as $r) {
                  $datos .= '<option value="'.$this->acceso->Crypto('encrypt',$r->id).'">'.$r->nombre.' ('.$r->codigo.')'.'</option>';
              }
            }
            echo json_encode($datos);
        }
    }

}
