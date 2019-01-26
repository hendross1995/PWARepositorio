<?php

require('modelos/idioma.modelo.php');
require('controladores/acceso.controlador.php');

class IdiomaControlador{
  private $idioma;
  private $acceso;
  private $data = array();
  private $id = 0;

  public function __CONSTRUCT(){
    $this->idioma = new Idioma();
    $this->acceso = new AccesoControlador();
  }
  public function FrmIdiomas(){
    if($this->acceso->ComprobarAcceso($_GET,NULL,TRUE)){
      require_once 'vistas/admin/header.php';
      require_once 'vistas/admin/idiomas.php';
      require_once 'vistas/admin/footer.php';
    }
  }
  public function MostrarIdiomas(){
    if($this->acceso->ComprobarAcceso($_GET,$_POST['id'],FALSE)){
      $x = new Idioma();
      foreach ($this->idioma->MostrarIdiomas($x) as $r) {
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
          'accion'=> ('<button class="btn boton_modificar_idioma" data-toggle="modal" data-target="#frm_actualizar_idiomas" type="button" id="'.$this->acceso->Crypto('encrypt',$r->id).'"><i class="fa fa-edit"></i></button>')
        );
      }
      if (!$data) $data = '';
      $results = array("data" => $data);
      echo json_encode($results);
    }
  }
  public function ActualizarIdiomas(){
  if($this->acceso->ComprobarAcceso($_GET,$_POST['accion'],FALSE)){
        $x = new Idioma();
        $x->id = $this->acceso->Crypto('decrypt',$_POST['codigo']);
        $x->nombre = strip_tags($_POST['nombre_idioma']);
        $x->accion=strip_tags($_POST['accion']);
        $x->estado=strip_tags($_POST['estado_idioma'])=="on"?1:0;
        $x->usuario=$this->acceso->Crypto('decrypt',$_SESSION['idusuario']);
        foreach (json_decode($this->idioma->ActualizarIdiomas($x)[0]->resultado) as $r):endforeach;
        echo json_encode(array('estado'=>$r->estado,'observacion'=>$r->observacion));
      }
  }
  public function CargarIdiomas(){
      $x = new Idioma();
      foreach ($this->idioma->CargarIdiomas() as $r) {
        $datos .= '<option value="'.$this->acceso->Crypto('encrypt',$r->id).'">'.$r->nombre.'</option>';
      }
      echo json_encode($datos);
  }
}

 ?>
