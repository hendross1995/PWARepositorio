<?php

require('modelos/tipomaterialsoporte.modelo.php');
require('controladores/acceso.controlador.php');

class TipoMaterialSoporteControlador{
  private $tms;
  private $acceso;
  private $data = array();
  private $id = 0;

  public function __CONSTRUCT(){
    $this->tms = new TipoMaterialSoporte();
    $this->acceso = new AccesoControlador();
  }
  public function FrmTMS(){
    if($this->acceso->ComprobarAcceso($_GET,NULL,TRUE)){
      switch ($_SESSION['rol']) {
        case 'ADMINISTRADOR' OR 'DIGITADOR':
          require_once 'vistas/admin/header.php';
          require_once 'vistas/admin/tiposmaterialessoporte.php';
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
  public function MostrarTMS(){
    if($this->acceso->ComprobarAcceso($_GET,$_POST['id'],FALSE)){
      $x = new TipoMaterialSoporte();
      foreach ($this->tms->MostrarTMS($x) as $r) {
        $id++;
        if ($r->estado) {
          $estado = '<small class="badge badge-success">ACTIVO</small>';
        }else{
          $estado = '<small class="badge badge-dark">INACTIVO</small>';
        }
        $data[] = array(
          'num' => $id,
          'nombre' => $r->nombre,
          'accion'=> ('<button class="btn boton_modificar_tms" data-toggle="modal" data-target="#frm_actualizar_tms" type="button" id="'.$this->acceso->Crypto('encrypt',$r->id).'"><i class="fa fa-edit"></i></button>')
        );
      }
      if (!data) $data = '';
      $results = array("data" => $data);
      echo json_encode($results);
    }
  }
  public function ActualizarTMS(){
  if($this->acceso->ComprobarAcceso($_GET,$_POST['accion'],FALSE)){
        $x = new TipoMaterialSoporte();
        $x->id = $this->acceso->Crypto('decrypt',$_POST['codigo']);
        $x->nombre = strip_tags($_POST['nombre_tms']);
        $x->accion=strip_tags($_POST['accion']);
        $x->usuario=$this->acceso->Crypto('decrypt',$_SESSION['idusuario']);
        foreach (json_decode($this->tms->ActualizarTMS($x)[0]->resultado) as $r):endforeach;
        echo json_encode(array('estado'=>$r->estado,'observacion'=>$r->observacion));
      }
  }
  public function CargarTMS(){
      $x = new TipoMaterialSoporte();
      foreach ($this->tms->CargarTMS() as $r) {
          $datos .= '<option value="'.$this->acceso->Crypto('encrypt',$r->id).'">'.$r->nombre.'</option>';
      }
      echo json_encode($datos);
  }
}
 ?>
