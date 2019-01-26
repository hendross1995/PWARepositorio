<?php

require 'modelos/acceso.modelo.php';

Class AccesoControlador {

	private $acceso;
	private $verifica = FALSE;
	private $datos = '';

	public function __CONSTRUCT(){
		$this->acceso = new Acceso();
	}
	public function Login(){
		require_once 'vistas/inicio.php';
	}
	public function RestablecerContrasena(){
		if(isset($_POST['correo_restablecer'])){
			$x = new Acceso();
		  	$x->correo = strip_tags(strtolower($_POST['correo_restablecer']));
		  	foreach (json_decode($this->acceso->VerExisteCorreo($x)[0]->resultado) AS $r):endforeach;
	    	if($r->estado){
	    		require 'controladores/correo.controlador.php';
	    		$this->enviar_correos = new EnviarCorreosControlador();
	    		$a = $this->enviar_correos->RestablecerContrasena($x->correo);
	    		if($a['estado']){
	    			$x->contrasena1 = $this->Crypto('encrypt',$a['contrasena']);
	    			$x->contrasena2 = '';
	    			$x->contrasena3 = '';
	    			$x->accion = 'registrar';
	    			foreach (json_decode($this->acceso->ActualizarContrasena($x)[0]->resultado) as $s):endforeach;
	    			echo json_encode(array('estado'=>$s->estado,'observacion'=>$s->observacion));
	    		}else{
	    			echo json_encode(array('estado'=>$a['estado'],'observacion'=>$a['observacion']));
	    		}
	    	}else{
	    		echo json_encode(array('estado'=>$r->estado,'observacion'=>$r->observacion));
	    	}
		}else{
			$this->login();
		}
	}
	public function ActualizarContrasena(){
		if(isset($_POST['contrasena_actual'])){
			$x = new Acceso();
		   	$x->correo = strip_tags($_SESSION['usuario']);
		    $x->contrasena1 = $this->Crypto('encrypt',strip_tags($_POST['contrasena_actual']));
		    $x->contrasena2 = $this->Crypto('encrypt',strip_tags($_POST['nueva_contrasena']));
		    $x->contrasena3 = $this->Crypto('encrypt',strip_tags($_POST['repetir_contrasena']));
		    $x->usuario = $this->Crypto('decrypt',$_SESSION['idusuario']);
			$x->accion = 'modificar';
			foreach (json_decode($this->acceso->ActualizarContrasena($x)[0]->resultado) as $s):endforeach;
    			if($s->estado){
    				$_SESSION['recuperacion'] = $s->recuperacion;
    			}
    			echo json_encode(array('estado'=>$s->estado,'observacion'=>$s->observacion));
		}else{
			$this->login();
		}
	}
	public function IniciarSesion(){
        if(isset($_POST['correo'])){
		   	$x = new Acceso();
		   	$x->usuario = strip_tags($_POST['correo']);
		    $x->contrasena = $this->Crypto('encrypt',strip_tags($_POST['contrasena']));
		    foreach (json_decode($this->acceso->IniciarSesion($x)[0]->resultado) AS $r):
		    	if($r->estado){
		    	$_SESSION['estado'] = $r->estado;
					$_SESSION['idpersona'] = $this->Crypto('encrypt',$r->idpersona);
					$_SESSION['idusuario'] = $this->Crypto('encrypt',$r->idusuario);
					$_SESSION['usuario'] = $r->usuario;
					$_SESSION['nombresusuario'] = $r->nombres_usuario;
					$_SESSION['rol'] = $r->rol;
					$_SESSION['modulos'] = $this->IniciarModulos($r->rol);
					if($r->recuperacion){
		    			$_SESSION['recuperacion'] = $r->recuperacion;
		    		}else{
		    			unset($_SESSION['recuperacion']);
		    		}
		    	}else{
		    		$_SESSION = array();
		    	}
			endforeach;
		    	echo json_encode(array('estado'=>$r->estado,'observacion'=>$r->observacion));
		}else{
			if($_SESSION['idusuario']){
				header("location:/");
			}else{
				echo $this->Login()."<script>$('._iniciar_sesion').html('Tu sesión ha expirado. Debes volver a iniciar sesión.').show()</script>";
			}
		}
	}

	public function Salir(){
		$_SESSION = array();
    	header("location:/");
	}

	public function ComprobarAcceso($GET,$POST,$ESFORM){
	    if(isset($_SESSION['idusuario'])){
	    	if (!isset($_SESSION['recuperacion'])){
			    $x = new Acceso();
			   	$x->idusuario = $this->Crypto('decrypt',$_SESSION['idusuario']);
			    foreach (json_decode($this->acceso->VerEstadoUsuario($x)[0]->resultado) AS $r):endforeach;
			    if(!$r->estado){
			    	$_SESSION = array();
			    	if(!$ESFORM){
						echo "Tu sesión ha expirado. Debes volver a iniciar sesión.";
					}else{
						echo $this->Login()."
						<script>$('._iniciar_sesion').html('Tu sesión ha expirado. Debes volver a iniciar sesión.').show()</script>";
					}
			    }else{
					if($this->ExisteURLenModulos($GET['index'])){
    					return TRUE;
	    			}else{
			    		if($this->ExistePETICIONenModulos($GET['index'])){
			    			if(isset($POST)){
						    	return TRUE;
						    }else{
						    	echo $this->FormularioError();
							}
						}else{
							if(isset($POST)){
								echo json_encode(array("estado"=>false,"observacion"=>"Acceso denegado. Sin privilegios para realizar esta acción."));
							}else{
								echo $this->FormularioError();
					    	}
			    		}
			    	}
				}
			}else{
				echo $this->FormularioRestablecerContrasena();
			}
	   	}else{
			$_SESSION = array();
			if($ESFORM){
				echo $this->Login();
			}else{
				if(isset($POST)){
					echo 'Acceso denegado. Primero debes iniciar sesión.';
				}else{
					echo $this->Login();
				}
			}
		}
	}
	public function IniciarModulos($rol){
		switch ($rol) {
			case 'ADMINISTRADOR':
				return  array("","fondos","colecciones","materialesdocumentales","archivos","contenedores","fichas","secciones","materialessoporte","niveles","idiomas","toponimia","formatos","tiposmaterialessoporte","lineasinteres","personajesgeneradores","usuarios","lectores","documentos","favoritos","pendientes","perfil");
				break;
			case 'DOCUMENTALISTA':
				return  array("","documentos","perfil");
				break;
			case 'LECTOR':
				return  array("","favoritos","perfil");
				break;

			default:
				# code...
				break;
		}
	}
	private function ExistePETICIONenModulos($peticion){
		$peticion = str_replace('mostrar_',"",$peticion);
		$peticion = str_replace('cargar_',"",$peticion);
		$peticion = str_replace('actualizar_',"",$peticion);
		$peticion = str_replace('aprobar_',"",$peticion);
		$peticion = str_replace('reprobar_',"",$peticion);
		if($_SESSION['modulos']){
			return in_array($peticion,$_SESSION['modulos'])?TRUE:FALSE;
		}else{
			return FALSE;
		}
	}
	private function ExisteURLenModulos($peticion){
		if($_SESSION['modulos']){
			return in_array($peticion,$_SESSION['modulos'])?TRUE:FALSE;
		}else{
			return FALSE;
		}
	}
	public function FormularioError(){
		require_once 'vistas/error.php';
	}
	public function FormularioRestablecerContrasena(){
		require_once 'vistas/admin/restablecer.php';
	}
	function Crypto($action, $string) {
	    $salida = false;
	    $metodo = "AES-256-CBC";
	    $key = 'colegiospswd';
	    $key_iv = 'NEJpNVJISEM2Q29OMVBmY2l3QUZiejJiRm4wUXpDcUwwM29YeWpDN1Nraz0=';
	    // hash
	    $key = hash('sha256', $key);

	    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
	    #$ivSize = openssl_cipher_iv_length($metodo);
	    $iv = substr(hash('sha256', $key_iv), 0, 16);
	    if ($action == 'encrypt') {
	    	if(strlen($string) > 0){
		        $salida = openssl_encrypt($string, $metodo, $key, OPENSSL_RAW_DATA, $iv);
		        $salida = base64_encode($salida);
		    }
	    } else if($action == 'decrypt') {
	    	if(strlen($string) > 0){
	        	$salida = openssl_decrypt(base64_decode($string), $metodo, $key, OPENSSL_RAW_DATA, $iv);
	        }
	    }
	    return $salida;
	}



}
?>
