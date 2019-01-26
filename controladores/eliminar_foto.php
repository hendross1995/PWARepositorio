<?php 
	function EliminarFoto($foto)
	{
			if(file_exists($foto)){
              	chmod( $foto, 0777 );
            	unlink( $foto );
              #unlink($foto,0777);
            }

	}

	function EliminarOtro($otro)
	{
			if(file_exists("app/source/".$otro)){
              unlink("app/source/".$otro);
            }

	}
 ?>