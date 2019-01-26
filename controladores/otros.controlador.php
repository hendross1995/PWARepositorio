<?php

require 'modelos/otros.modelo.php';
require 'controladores/acceso.controlador.php';

Class OtrosControlador{
    private $otros;
    private $acceso;
    public function __CONSTRUCT(){
      $this->otros = new Otros();
      $this->acceso = new AccesoControlador();
    }
    public function CargarInicio(){
      $x = new Otros();
      $nombres = array();
      $totales = array();
      foreach (json_decode($this->otros->CargarInicio($x)[0][0]->resultado) as $r):
        $cantidades = array(
          "estado"=>$r->estado,
          "cantidad_usuarios"=>$r->usuarios,
          "cantidad_favoritos"=>$r->favoritos,
          "cantidad_documentos"=>$r->documentos,
          "cantidad_pendientes"=>$r->pendientes,
          "observacion"=>$r->observacion
        );
      endforeach;
      foreach ($this->otros->CargarInicio($x)[1] as $r):
        array_push($nombres,$r->nombre);
        array_push($totales,$r->total);
        $estado = true;
      endforeach;
      
      echo json_encode(array($cantidades,$nombres,$totales,$estado));
    }
    public function CargarPaises(){
      $x = new Otros();
      $datos = '<option value="0" selected>*Seleccionar país:</option>';
      foreach ($this->otros->CargarPaises() as $r) {
          $datos .= '<option value="'.$this->acceso->Crypto('encrypt',$r->id).'">'.$r->nombre.'</option>';
      }
      echo json_encode($datos);
    }
    public function CargarProvincias(){
      $x = new Otros();
      if (isset($_POST['pais'])) {
        $x->id_pais = $_POST['pais'];
      }else{
        $x->id_pais = 66;
      }
      $datos = '<option value="0" selected>*Seleccionar provincia:</option>';
      if($x->id_pais !== 0 || $x->id_pais !== null){
          foreach ($this->otros->CargarProvincias($x) as $r) {
              $datos .= '<option value="'.$this->acceso->Crypto('encrypt',$r->id).'">'.$r->nombre.'</option>';
          }
      }
      echo json_encode($datos);
    }
    public function CargarCantones(){
      $x = new Otros();
      $x->id_provincia = $this->acceso->Crypto('decrypt',$_POST['provincia']);
      $datos = '<option value="0" selected>*Seleccionar cantón:</option>';
      //if($x->id_provincia != 0 || $x->id_provincia != null){
          foreach ($this->otros->CargarCantones($x) as $r) {
              $datos .= '<option value="'.$this->acceso->Crypto('encrypt',$r->id).'">'.$r->nombre.'</option>';
          }
      //}
      echo json_encode($datos);
    }
    public function CargarParroquias(){
      $x = new Otros();
      $x->id_canton = $this->acceso->Crypto('decrypt',$_POST['canton']);
      $datos = '<option value="0" selected>*Seleccionar parroquia:</option>';
      if($x->id_canton != 0 || $x->id_canton != null){
          foreach ($this->otros->CargarParroquias($x) as $r) {
              $datos .= '<option value="'.$this->acceso->Crypto('encrypt',$r->id).'">'.$r->nombre.'</option>';
          }
      }
      echo json_encode($datos);
    }
    public function CargarEstadoCivil(){
        $x = new Otros();
        $datos = '<option value="0" selected>*Seleccionar estado civil:</option>';
        foreach ($this->otros->CargarEstadoCivil() as $r) {
            $datos .= '<option value="'.$this->acceso->Crypto('encrypt',$r->id).'">'.$r->nombre.'</option>';
        }
        echo json_encode($datos);
    }
    public function CargarOcupaciones(){
        $x = new Otros();
        $datos = '<option value="0" selected>*Seleccionar ocupación:</option>';
        foreach ($this->otros->CargarOcupaciones() as $r) {
            $datos .= '<option value="'.$this->acceso->Crypto('encrypt',$r->id).'">'.$r->nombre.'</option>';
        }
        echo json_encode($datos);
    }
    public function CargarRoles(){
        $x = new Otros();
        $datos = '<option value="0" selected>*Seleccionar rol:</option>';
        foreach ($this->otros->CargarRoles() as $r) {
            $datos .= '<option value="'.$this->acceso->Crypto('encrypt',$r->id).'">'.$r->nombre.'</option>';
        }
        echo json_encode($datos);
    }
    public function CargarEstadosConservacion(){
        $x = new Otros();
        $datos = '<option value="0" selected>Seleccionar estado conservación:</option>';
        foreach ($this->otros->CargarEstadosConservacion() as $r) {
            $datos .= '<option value="'.$this->acceso->Crypto('encrypt',$r->id).'">'.$r->nombre.'</option>';
        }
        echo json_encode($datos);
    }

    public function CargarEstadosVerificacion(){
        $x = new Otros();
        $datos = '<option value="0" selected>Seleccionar estado verificación:</option>';
        foreach ($this->otros->CargarEstadosVerificacion() as $r) {
            $datos .= '<option value="'.$this->acceso->Crypto('encrypt',$r->id).'">'.$r->nombre.'</option>';
        }
        echo json_encode($datos);
    }

    public function MostrarErrores(){
        if($this->acceso->ComprobarAcceso($_GET,$_POST['id'],FALSE)){
            $x = new Otros();
            foreach ($this->otros->MostrarErrores($x) as $r) {
                $id++;
                $data[] = array(
                    'num'=> $id,
                    'usuario'=> $r->usuario,
                    'codigo'=> $r->codigo,
                    'mensaje'=> $mensaje,
                    'proceso'=> $proceso,
                    'accion'=>$accion,
                    'fecha'=>$fecha
                );
            }
            if(!$data) $data = '';
      $results = array(
          "data"	=>	$data
      );
        echo json_encode($results);
         }
    }

}
