<?php


include_once ('defines.php');
include_once ('funcionesGenerales.php');

function traerDatos($conexion, $idUsuario, $tabla){
	$result = $conexion->query("SELECT * FROM " . $tabla . " where usuario='" . $idUsuario."'");

	if ($conexion->connect_errno) {
		throw new Exception(errorConexionBase);
	}

	while($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$myArray[] = $row;
	}

	return $myArray;
}

//espera json con datos y un codigo de salida
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

try {
	
	if ($_POST["idSesion"] != "") {
		throw new Exception("799");	
	}

	//conecto a la base
	$conexion = connect();

	//valido credenciales
	$idUsuario = validoCredenciales($conexion, $_POST["mail"], $_POST["password"]);

	//ejecuto query para traer datos necesarios
	$arrayDatosUsuario = traigoDatos($conexion, $idUsuario);

	$arraySalida = armarSalidaTest($arrayDatosUsuario[0], "200");

	$conexion->close();

	echo $arraySalida;

} catch (Exception $e) {

	$arraySalida = armarSalidaTest($arrayDatosUsuario[0], $e->getMessage() );

	if (isset($conexion)) {
		$conexion->close();
	}
	
	echo $arraySalida;

};

?>