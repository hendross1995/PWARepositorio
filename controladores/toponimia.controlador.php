<?php

require('modelos/toponimia.modelo.php');
require('controladores/acceso.controlador.php');

class ToponimiaControlador{
  private $toponimia;
  private $acceso;
  private $data = array();
  private $id = 0;

  public function __CONSTRUCT(){
    $this->toponimia = new Toponimia();
    $this->acceso = new AccesoControlador();
  }
  public function FrmToponimia(){
    if($this->acceso->ComprobarAcceso($_GET,NULL,TRUE)){
      switch ($_SESSION['rol']) {
        case 'ADMINISTRADOR' OR 'DIGITADOR':
          require_once 'vistas/admin/header.php';
          require_once 'vistas/admin/toponimia.php';
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
  public function MostrarToponimia(){
    if($this->acceso->ComprobarAcceso($_GET,$_POST['id'],FALSE)){
      $x = new Toponimia();
      foreach ($this->toponimia->MostrarToponimia($x) as $r) {
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
          'accion'=> ('<button class="btn boton_modificar_toponimia" data-toggle="modal" data-target="#frm_actualizar_toponimia" type="button" id="'.$this->acceso->Crypto('encrypt',$r->id).'"><i class="fa fa-edit"></i></button>')
        );
      }
      if (!$data) $data = '';
      $results = array("data" => $data);
      echo json_encode($results);
    }
  }
  public function ActualizarToponimia(){
  if($this->acceso->ComprobarAcceso($_GET,$_POST['accion'],FALSE)){
        $x = new Toponimia();
        $x->id = $this->acceso->Crypto('decrypt',$_POST['codigo']);
        $x->nombre = strip_tags($_POST['nombre_toponimia']);
        $x->accion=strip_tags($_POST['accion']);
        $x->estado=strip_tags($_POST['estado_toponimia'])=="on"?1:0;
        $x->usuario=$this->acceso->Crypto('decrypt',$_SESSION['idusuario']);
        foreach (json_decode($this->toponimia->ActualizarToponimia($x)[0]->resultado) as $r):endforeach;
        echo json_encode(array('estado'=>$r->estado,'observacion'=>$r->observacion));
      }
  }
  public function CargarToponimia(){
      $x = new Toponimia();
      foreach ($this->toponimia->CargarToponimia() as $r) {
        $datos .= '<option value="'.$this->acceso->Crypto('encrypt',$r->id).'">'.$r->nombre.'</option>';
      }
      echo json_encode($datos);
  }
}

 ?>
