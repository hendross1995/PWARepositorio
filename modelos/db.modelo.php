<?php
 class Database{
  public static function Conectar(){
    try {
      $pdo = new PDO('pgsql:host=localhost;port=5432;dbname=archivos_cca;', 'stefano', '123');
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      #$pdo->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
		  ##print "Conexión a postgres correcta.";
	    return $pdo;
      $pdo = null;
  	} catch (Exception $e) {
  		#echo "No se puede conectar con el servidor";
      echo "Error de conexión: " . $e->getMessage();
  	}
  }
} 
?>
