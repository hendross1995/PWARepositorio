<?php

require('modelos/lineainteres.modelo.php');
require('controladores/acceso.controlador.php');

class LineaInteresControlador{
  private $lineainteres;
  private $acceso;
  private $data = array();
  private $id = 0;

  public function __CONSTRUCT(){
    $this->lineainteres = new LineaInteres();
    $this->acceso = new AccesoControlador();
  }
  public function FrmLineasInteres(){
    if($this->acceso->ComprobarAcceso($_GET,NULL,TRUE)){
      switch ($_SESSION['rol']) {
        case 'ADMINISTRADOR' OR 'DIGITADOR':
          require_once 'vistas/admin/header.php';
          require_once 'vistas/admin/lineasinteres.php';
          require_once 'vistas/admin/footer.php';
          break;
        case 'LECTOR':
        #require_once 'vistas/lector/header.php';
        require_once 'vistas/lector/index.html';
        #require_once 'vistas/lector/footer.php';
        default:
          // code...
          break;
      }
    }
  }
  public function MostrarLineasInteres(){
    if($this->acceso->ComprobarAcceso($_GET,$_POST['id'],FALSE)){
      $x = new LineaInteres();
      foreach ($this->lineainteres->MostrarLineasInteres($x) as $r) {
        $id++;
        if ($r->estado) {
          $estado = '<small class="badge badge-success">ACTIVO</small>';
        }else{
          $estado = '<small class="badge badge-dark">INACTIVO</small>';
        }
        $data[] = array(
          'num' => $id,
          'nombre' => $r->nombre,
          'estado' => $estado,
          'accion'=> ('<button class="btn boton_modificar_lineainteres" data-toggle="modal" data-target="#frm_actualizar_lineasinteres" type="button" id="'.$this->acceso->Crypto('encrypt',$r->id).'"><i class="fa fa-edit"></i></button>')
        );
      }
      if (!$data) $data = '';
      $results = array("data" => $data);
      echo json_encode($results);
    }
  }
  public function ActualizarLineasInteres(){
  if($this->acceso->ComprobarAcceso($_GET,$_POST['accion'],FALSE)){
        $x = new LineaInteres();
        $x->id = $this->acceso->Crypto('decrypt',$_POST['codigo']);
        $x->nombre = strip_tags($_POST['nombre_lineainteres']);
        $x->accion=strip_tags($_POST['accion']);
        $x->estado=strip_tags($_POST['estado_lineainteres'])=="on"?1:0;
        $x->usuario=$this->acceso->Crypto('decrypt',$_SESSION['idusuario']);
        foreach (json_decode($this->lineainteres->ActualizarLineasInteres($x)[0]->resultado) as $r):endforeach;
        echo json_encode(array('estado'=>$r->estado,'observacion'=>$r->observacion));
      }
  }
  public function CargarLineasInteres(){
      $x = new LineaInteres();
      $datos = '<option value="0" selected>*Seleccionar linea de interes:</option>';
      foreach ($this->lineainteres->MostrarLineasInteres() as $r) {
          $datos .= '<option value="'.$this->acceso->Crypto('encrypt',$r->id).'">'.$r->nombre.'</option>';
      }
      echo json_encode($datos);
  }
}

 ?>
