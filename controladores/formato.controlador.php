<?php

require('modelos/formato.modelo.php');
require('controladores/acceso.controlador.php');

class FormatoControlador{
  private $formato;
  private $acceso;
  private $data = array();
  private $id = 0;

  public function __CONSTRUCT(){
    $this->formato = new Formato();
    $this->acceso = new AccesoControlador();
  }
  public function FrmFormatos(){
    if($this->acceso->ComprobarAcceso($_GET,NULL,TRUE)){
      require_once 'vistas/admin/header.php';
      require_once 'vistas/admin/formatos.php';
      require_once 'vistas/admin/footer.php';
    }
  }
  public function MostrarFormatos(){
    if($this->acceso->ComprobarAcceso($_GET,$_POST['id'],FALSE)){
      $x = new Formato();
      foreach ($this->formato->MostrarFormatos($x) as $r) {
        $id++;
        if ($r->estado) {
          $estado = '<small class="badge badge-success">ACTIVO</small>';
        }else{
          $estado = '<small class="badge badge-dark">INACTIVO</small>';
        }
        $data[] = array(
          'num' => $id,
          'nombre' => $r->nombre,
          'accion'=> ('<button class="btn boton_modificar_formato" data-toggle="modal" data-target="#frm_actualizar_formatos" type="button" id="'.$this->acceso->Crypto('encrypt',$r->id).'"><i class="fa fa-edit"></i></button>')
        );
      }
      if (!data) $data = '';
      $results = array("data" => $data);
      echo json_encode($results);
    }
  }
  public function ActualizarFormatos(){
  if($this->acceso->ComprobarAcceso($_GET,$_POST['accion'],FALSE)){
        $x = new Formato();
        $x->id = $this->acceso->Crypto('decrypt',$_POST['codigo']);
        $x->nombre = strip_tags($_POST['nombre_formato']);
        $x->accion=strip_tags($_POST['accion']);
        $x->usuario=$this->acceso->Crypto('decrypt',$_SESSION['idusuario']);
        foreach (json_decode($this->formato->ActualizarFormatos($x)[0]->resultado) as $r):endforeach;
        echo json_encode(array('estado'=>$r->estado,'observacion'=>$r->observacion));
      }
  }
  public function CargarFormatos(){
      $x = new Formato();
      foreach ($this->formato->CargarFormatos() as $r) {
          $datos .= '<option value="'.$this->acceso->Crypto('encrypt',$r->id).'">'.$r->nombre.'</option>';
      }
      echo json_encode($datos);
  }
}

 ?>
