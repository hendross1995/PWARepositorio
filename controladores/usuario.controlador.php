<?php

require 'modelos/usuario.modelo.php';
require 'controladores/acceso.controlador.php';

Class UsuarioControlador {
	private $usuario;
	private $acceso;

	private $data = array();
	private $id = 0;

	public function __CONSTRUCT(){
		$this->usuario = new Usuario();
		$this->acceso = new AccesoControlador();
	}
	public function FrmUsuarios(){
	    if($this->acceso->ComprobarAcceso($_GET,NULL,TRUE)){
      	    require_once 'vistas/admin/header.php';
	        require_once 'vistas/admin/usuarios.php';
	        require_once 'vistas/admin/footer.php';
		}
  	}
  	public function FrmUsuariosLectores(){
	    if($this->acceso->ComprobarAcceso($_GET,NULL,TRUE)){
	  	    require_once 'vistas/admin/header.php';
	        require_once 'vistas/admin/lectores.php';
	        require_once 'vistas/admin/footer.php';
        }
  	}
    
     public function FrmPerfil(){
    	if($this->acceso->ComprobarAcceso($_GET,NULL,TRUE)){
			switch ($_SESSION['rol']) {
		        case 'ADMINISTRADOR':
			        require_once 'vistas/admin/header.php';
			        require_once 'vistas/admin/miPerfil.php';
			        require_once 'vistas/admin/footer.php';
			        break;
				case 'DOCUMENTALISTA':
					require_once 'vistas/admin/header.php';
					require_once 'vistas/admin/miperfil.php';
					require_once 'vistas/admin/footer.php';
					break;
				case 'LECTOR':
					require_once 'vistas/lector/header.php';
		            require_once 'vistas/lector/miPerfil.php';
					require_once 'vistas/lector/footer.php';
				default:
		          # code...
		        break;
		    }

  		}			
	}
    
	public function MostrarUsuarios(){
			if($this->acceso->ComprobarAcceso($_GET,$_POST['id'],FALSE)){
				$x = new Usuario();
				foreach ($this->usuario->MostrarUsuarios($x) as $r) {
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
								'rol' => $r->rol,
								'sexo' => $r->sexo,
								'convencional'=>$r->convencional,
								'celular' => $r->celular,
								'idprovincia' => $r->idprovincia,
								'provincia' => $r->provincia,
								'idcanton' =>$r->idcanton,
								'canton' =>$r->canton,
								'idparroquia' => $r->idparroquia,
								'parroquia' => $r->parroquia,
								'direccion' => $r->direccion,
								'idrol' => $r->idrol,
								'usuario' => $r->usuario,
								'contrasena'=>$r->contrasena,
								'idpersona' => $r->idpersona,
								'estado'=> $estado,
								'accion'=> ('<button class="btn boton_modificar_usuario" data-toggle="modal" data-target="#frm_actualizar_usuarios" type="button" id="'.$this->acceso->Crypto('encrypt',$r->idusuario).'"><i class="fa fa-edit"></i></button>'),
						);
				}
				if(!$data) $data = '';
				$results = array(
						"data"	=>	$data
				);
			echo json_encode($results);
		}
	}
	public function MostrarUsuariosLectores(){
			if($this->acceso->ComprobarAcceso($_GET,$_POST['id'],FALSE)){
				foreach ($this->usuario->MostrarUsuariosLectores() as $r) {
						$id++;
						$data[] = array(
								'num'=> $id,
								'cedula'=> $r->cedula,
								'nombres' => $r->nombres." ".$r->apellidos,
								'fecha_creacion' => $r->fecha_creacion,
								'genero' => $this->VerGenero($r->sexo),
								'convencional'=>$r->convencional,
								'celular' => $r->celular,
								'idprovincia' => $r->idprovincia,
								'provincia' => $r->provincia,
								'idcanton' =>$r->idcanton,
								'canton' =>$r->canton,
								'idparroquia' => $r->idparroquia,
								'parroquia' => $r->parroquia,
								'direccion' => $r->direccion,
								'idrol' => $r->idrol,
								'usuario' => $r->usuario,
								'contrasena'=>$r->contrasena,
								'idpersona' => $r->idpersona,
								'estado'=> $estado,
						);
				}
				if(!$data) $data = '';
				$results = array(
						"data"	=>	$data
				);
			echo json_encode($results);
		}
	}
	public function ActualizarUsuarios(){
	if($this->acceso->ComprobarAcceso($_GET,$_POST['accion'],FALSE)){
				$x = new Usuario();
				$x->id = $this->acceso->Crypto('decrypt',$_POST['codigo']);
				$x->cedula = strip_tags($_POST['cedula_usuario']);
				$x->apellidos = strip_tags($_POST['apellidos_usuario']);
				$x->nombres = strip_tags($_POST['nombres_usuario']);
				$x->sexo = strip_tags($_POST['sexo_usuario']);
				$x->convencional = strip_tags($_POST['convencional_usuario']);
				$x->celular = strip_tags($_POST['celular_usuario']);
				$x->parroquias_id = $this->acceso->Crypto('decrypt',strip_tags($_POST['parroquia_usuario']));
				$x->direccion = strip_tags($_POST['direccion_usuario']);
				$x->roles_id = $this->acceso->Crypto('decrypt',strip_tags($_POST['rol_usuario']));
				$x->correo = strip_tags($_POST['correo_usuario']);
				$x->contrasena = $this->acceso->Crypto('encrypt',strip_tags($_POST['cedula_usuario']));
				$x->estado=strip_tags($_POST['estado_usuario'])=="on"?1:0;
				$x->accion=strip_tags($_POST['accion']);
				$x->usuario=$this->acceso->Crypto('decrypt',$_SESSION['idusuario']);
				foreach (json_decode($this->usuario->ActualizarUsuarios($x)[0]->resultado) as $r):endforeach;
				echo json_encode(array('estado'=>$r->estado,'observacion'=>$r->observacion));
			}
	}
    
    public function ActualizarPerfil(){ 
	if($this->acceso->ComprobarAcceso($_GET,$_POST['accion'],FALSE)){
				$x = new Usuario();
				$x->cedula = strip_tags($_POST['cedula_usuario']);
				$x->apellidos = strip_tags($_POST['apellidos_usuario']);
				$x->nombres = strip_tags($_POST['nombres_usuario']);
				$x->sexo = strip_tags($_POST['sexo_usuario']); 
				$x->convencional = strip_tags($_POST['convencional_usuario']);
				$x->celular = strip_tags($_POST['celular_usuario']);  
                $x->parroquias_id = $this->acceso->Crypto('decrypt',strip_tags($_POST['parroquia_usuario']));
				$x->direccion = strip_tags($_POST['direccion_usuario']);
				$x->correo = strip_tags($_POST['correo_usuario']);
				$x->accion = strip_tags($_POST['accion']);
				
				$x->usuario=$this->acceso->Crypto('decrypt',$_SESSION['idusuario']);
				foreach (json_decode($this->usuario->ActualizarPerfil($x)[0]->resultado) as $r):endforeach;
				echo json_encode(array('estado'=>$r->estado,'observacion'=>$r->observacion));
			}
	}
    
	public function RegistrarUsuarios(){
		if (isset($_POST['apellidos'],$_POST['nombres'],$_POST['sexo'],$_POST['ocupacion'],$_POST['organizacion'],$_POST['celular'],$_POST['nuevo_correo'],$_POST['nueva_contrasena'])){
		$x = new Usuario();
		$x->apellidos = strip_tags($_POST['apellidos']);
		$x->nombres = strip_tags($_POST['nombres']);
		$x->sexo = strip_tags($_POST['sexo']);
		$x->ocupacion = strip_tags($_POST['ocupacion']);;
		$x->organizacion = strip_tags($_POST['organizacion']);
		$x->telefono = strip_tags($_POST['celular']);
		$x->correo = strip_tags($_POST['nuevo_correo']);
		$x->password = $this->acceso->Crypto('encrypt',$_POST['nueva_contrasena']);
		foreach (json_decode($this->usuario->RegistrarUsuarios($x)[0]->resultado) AS $r):
			if($r->estado){
				$_SESSION['estado'] = $r->estado;
				$_SESSION['idpersona'] = $this->acceso->Crypto('encrypt',$r->idpersona);
				$_SESSION['idusuario'] = $this->acceso->Crypto('encrypt',$r->idusuario);
				$_SESSION['usuario'] = $r->usuario;
				$_SESSION['nombresusuario'] = $r->nombres_usuario;
				$_SESSION['rol'] = $r->rol;
				$_SESSION['modulos'] = $this->acceso->IniciarModulos($r->rol);
			}else{
				$_SESSION = array();
			}
		endforeach;
		echo json_encode(array('estado' => $r->estado,'observacion' => $r->observacion));
		}else{
			echo json_encode('error');
		}
	}
	public function CargarUsuarios(){
			$x = new Usuario();
			$datos = '<option value="0" selected>*Seleccionar usuario:</option>';
			foreach ($this->usuario->CargarUsuarios() as $r) {
					$datos .= '<option value="'.$this->acceso->Crypto('encrypt',$r->id).'">'.$r->nombre.'</option>';
			}
			echo json_encode($datos);
	}
    
    public function MostrarPerfil(){
        if($this->acceso->ComprobarAcceso($_GET,$_POST['idUsuario'],FALSE)){
			$x = new Usuario();
            $x->idUsuario = $this->acceso->Crypto('decrypt',$_SESSION['idusuario']);
			foreach ($this->usuario->MostrarPerfil($x) as $r) {
				
					$data[] = array(
							'cedula'=> $r->cedula,
							'apellidos'=> $r->apellidos,
							'nombres' => $r->nombres,
							'sexo' => $r->sexo,
							'convencional'=>$r->convencional,
							'celular' => $r->celular,
							'idprovincia'=>$this->acceso->Crypto('encrypt',$r->idprovincia),
							'provincia' => $r->provincia,
							'idcanton' =>$this->acceso->Crypto('encrypt',$r->idcanton),
							'canton' =>$r->canton,
							'idparroquia'=>$this->acceso->Crypto('encrypt',$r->idparroquia),
							'parroquia' => $r->parroquia,
							'direccion' => $r->direccion,
							'usuario' => $r->usuario, 
					);
			}
			if(!$data) $data = '';
			$results = array("data"	=>	$data);
			echo json_encode($results);
    	}
  	}

  	private function VerGenero($genero){
  		if($genero == "M")
  			return "Masculino";
  		elseif($genero == "F")
  			return "Femenino";
  		else
  			return "Otros";
  	}
}
?>
