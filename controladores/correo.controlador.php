<?php
	
require 'modelos/ExceptionMail.modelo.php';
require 'modelos/SMTP.modelo.php'; 
require 'modelos/PHPMailer.modelo.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

Class EnviarCorreosControlador {
	public function __CONSTRUCT(){}
	
  public function RestablecerContrasena($usuario){
    $remitente = 'Ciudad Alfaro.';
    $asunto = 'Restablececimiento de contraseña.';
    $nuevacontrasena = $this->RandomString(10); 
    $mensaje = '
      Estimado usuario.<br>
      En base a la solicitud requerida, el sistema ha procedido a asignarle una contraseña temporal.<br>
      <b>Usuario: </b>'.$usuario.'<br>
      <b>Contraseña: </b>'.$nuevacontrasena.'<br><br><br>
      <span style="color:orange">El sistema solicitará cambián su contraseña en el próximo inicio de sesión, para lo cual debe ingresar la contraseña temporal asignada.</span><br><br>
      Recuerde que para mayor seguridad, la contraseña debe incluir al menos una letra en mayúscula, minúscula y un dígito.<br><br>
      <b>Saludos,<br>
      '.$remitente.'</b><br><br><br>
      <small>Este correo fue generado automáticamente, por favor no responder al mismo.</small>
    '; 

    $mail = new PHPMailer(); 
    $mail->IsSMTP();
    $mail->CharSet = "UTF-8";
    $mail->Host = 'mail.ciudadalfaro.gob.ec';
    $mail->Port = '465';     
    $mail->SMTPAuth = true;   
    $mail->Username = 'memoria@ciudadalfaro.gob.ec';
    $mail->Password = 'memoria2019';
    $mail->SMTPSecure = 'ssl';
    $mail->AddAddress($usuario);
    $mail->SetFrom('memoria@ciudadalfaro.gob.ec');
    $mail->AddReplyTo($remitente);
    $mail->IsHTML(true);
    $mail->Subject = $asunto;    
    $mail->Body = $mensaje; 
    if($mail->Send()){
      return array('estado'=>TRUE,'contrasena'=>$nuevacontrasena);
    }else{
      return array('estado'=>FALSE,'observacion'=>$mail->ErrorInfo);
    }
  }
  private function RandomString($length) {
      $keys = array_merge(range(0,9), range('a', 'z'), range('A', 'Z'));

      $key = "";
      for($i=0; $i < $length; $i++) {
          $key .= $keys[mt_rand(0, count($keys) - 1)];
      }
      return $key;
  }
}
?>