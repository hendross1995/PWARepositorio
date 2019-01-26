<?php
require 'modelos/personajegenerador.modelo.php';
require 'controladores/acceso.controlador.php';
require 'modelos/subirimagen.modelo.php';

Class PersonajeGeneradorControlador{
    private $personajegenerador;
    private $acceso;
    private $data = array();
    private $id = 0;

    public function __CONSTRUCT(){
        $this->personajegenerador = new PersonajeGenerador();
        $this->acceso = new AccesoControlador();
    }
    Public function FrmPersonajesGeneradores(){
        if($this->acceso->ComprobarAcceso($_GET,NULL,TRUE)){
            switch ($_SESSION['rol']) {
                case 'ADMINISTRADOR' OR 'DIGITADOR':
                    require_once 'vistas/admin/header.php';
                    require_once 'vistas/admin/personajesgeneradores.php';
                    require_once 'vistas/admin/footer.php';
                    break;
                case 'LECTOR':
                    #require_once 'vistas/lector/header.php';
                    require_once 'vistas/lector/index.html';
                    #require_once 'vistas/lector/footer.php';
                    break;

                default:
                    # code...
                    break;
            }
        }
    }
    public function MostrarPersonajesGeneradores(){
        if($this->acceso->ComprobarAcceso($_GET,$_POST['id'],FALSE)){
            $x = new PersonajeGenerador();
            foreach ($this->personajegenerador->MostrarPersonajesGeneradores($x) as $r) {
                $id++;
                if($r->estado){
                    $estado = '<small class="badge badge-success">ACTIVO</small>';
                }else{
                    $estado = '<small class="badge badge-dark">INACTIVO</small>';
                }
                $data[] = array(
                    'num'=> $id,
                    'cedula'=> $r->cedula,
                    'apellidos'=> $r->apellidos,
                    'nombres' => $r->nombres,
                    'lugar_nacimiento' => $r->lugar_nacimiento,
                    'fecha_nacimiento' => $r->fecha_nacimiento,
                    'fecha_disfuncion' => $r->fecha_disfuncion,
                    'sexo' => $r->sexo,
                    'foto_carnet'=>$r->foto_carnet!=null?'assets/personajesgeneradoresfotos/'.$r->foto_carnet.'?nocache='.time():NULL,
                    'path'=>$r->foto_carnet,
                    'nacionalidad' => $r->nacionalidad,
                    'organizacion' => $r->organizacion,
                    'alias' => $r->alias,
                    'descripcion' => $r->descripcion,
                    'estado'=> $estado,
                    'accion'=> ('<button class="btn boton_modificar_personajegenerador" data-toggle="modal" data-target="#frm_actualizar_personajesgeneradores" type="button" id="'.$this->acceso->Crypto('encrypt',$r->idpersona).'"><i class="fa fa-edit"></i></button>'),
                );
            }
            if(!$data) $data = '';
			$results = array(
			    "data"	=>	$data
			);
		    echo json_encode($results);
         }
    }

    public function ActualizarPersonajesGeneradores(){
		if($this->acceso->ComprobarAcceso($_GET,$_POST['accion'],FALSE)){
          $x = new PersonajeGenerador();
          $x->foto_carnet = $_POST['nombreFoto'];
          if ($_POST['vaFoto'] == 'true') {
            $subirimagen = new imgUpldr();
            $subirimagen->_width=200;
            $subirimagen->_height=200;
            $subirimagen->_size=2000000;
            $subirimagen->_exts = array("image/jpeg", "image/png");
            $resultado = $subirimagen->init($_FILES['foto_personajegenerador']);
            if(isset($resultado) && (json_decode($resultado)->estado == TRUE)){
                $x->foto_carnet = json_decode($resultado)->path;
            }
            //echo "vaFoto: ".$vaFoto;
          }elseif ($_POST['eliminarFoto'] == 'true' && $_POST['nombreFoto'] !== '') {
            if(file_exists('assets/personajesgeneradoresfotos/'.$_POST['nombreFoto'])){
              chmod( 'assets/personajesgeneradoresfotos/'.$_POST['nombreFoto'], 0777 );
              unlink( 'assets/personajesgeneradoresfotos/'.$_POST['nombreFoto']);
            }
            //echo "eliminaFoto :".$eliminarFoto;
            $x->foto_carnet = '';
          }
     			$x->id = $this->acceso->Crypto('decrypt',$_POST['codigo']);
          $x->cedula = strip_tags($_POST['cedula_personajegenerador']);
    			$x->apellidos = strip_tags($_POST['apellidos_personajegenerador']);
          $x->nombres = strip_tags($_POST['nombres_personajegenerador']);
    			$x->lugar_nacimiento = strip_tags($_POST['lugar_nacimiento_personajegenerador']);
          $x->fecha_nacimiento = strip_tags($_POST['fecha_nacimiento_personajegenerador']);
          $x->fecha_disfuncion = strip_tags($_POST['fecha_disfuncion_personajegenerador']);
          $x->sexo = strip_tags($_POST['sexo_personajegenerador']);
          $x->nacionalidad = strip_tags($_POST['nacionalidad_personajegenerador']);
          $x->organizacion = strip_tags($_POST['organizacion_personajegenerador']);
          $x->alias = strip_tags($_POST['alias_personajegenerador']);
          $x->descripcion = strip_tags($_POST['descripcion_personajegenerador']);
          $x->estado=strip_tags($_POST['estado_personajegenerador'])=="on"?1:0;
          $x->accion=strip_tags($_POST['accion']);
          $x->usuario=$this->acceso->Crypto('decrypt',$_SESSION['idusuario']);
          foreach (json_decode($this->personajegenerador->ActualizarPersonajesGeneradores($x)[0]->resultado) as $r):endforeach;
          echo json_encode(array('estado'=>$r->estado,'observacion'=>$r->observacion));
        }
    }
    public function CargarPersonajesGeneradores(){
        $x = new PersonajeGenerador();
        foreach ($this->personajegenerador->CargarPersonajesGeneradores() as $r) {
            $datos .= '<option value="'.$this->acceso->Crypto('encrypt',$r->idpersona).'" data-subtext="'.$r->alias.'">'.$r->nombres.' '.$r->apellidos.'</option>';
        }
        echo json_encode($datos);
    }
    public function CargarPersonajes(){
        $x = new PersonajeGenerador();
        foreach ($this->personajegenerador->CargarPersonajes() as $r) {
            $datos .= '<option value="'.$this->acceso->Crypto('encrypt',$r->idpersona).'" data-subtext="'.$r->alias.'">'.$r->nombres.' '.$r->apellidos.'</option>';
        }
        echo json_encode($datos);
    }
    public function CargarGeneradores(){
        $x = new PersonajeGenerador();
        foreach ($this->personajegenerador->CargarGeneradores() as $r) {
            $datos .= '<option value="'.$this->acceso->Crypto('encrypt',$r->idpersona).'" data-subtext="'.$r->alias.'">'.$r->nombres.' '.$r->apellidos.'</option>';
        }
        echo json_encode($datos);
    }
}
