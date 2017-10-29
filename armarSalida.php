<?php
include_once ('defines.php');

//espera json con datos y un codigo de salida
//devuelve json con salida
function armarSalidaTest($misDatos, $codigoSalida){

	$result = json_encode(array
							("direccionServidor"=>"http://universys.site/login", 
							 "apiVer"=>apiVersionActual,
							 "errorId"=>$codigoSalida,
							 "usuario"=>$misDatos)
						);

    //si no hay errores lo devuelvo
	if($result) {
		return $result;
	}

	throw new Exception(errorInesperado);
}

?>