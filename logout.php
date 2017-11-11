<?php  


include_once ('defines.php');
include_once ('funcionesGenerales.php');

function doLogout($data) {
	try {

		if (!(isset($data))) {
			throw new Exception(errorInesperado);	
		}

		//chequeo version
		VersionDeAPICorrecta($data["apiVer"]);

		//conecto a la base
		$conexion = connect();

		eliminarSesion($conexion,$data['idSesion']);

		$arraySalida = armarSalida(null, "200","/logout");

		$conexion->close();

		return $arraySalida;

	} catch (Exception $e) {

		$arraySalida = armarSalida(null, $e->getMessage(),"/logout");

		if (isset($conexion)) {
			$conexion->close();
		}

		return $arraySalida;

	}
}

if(!defined('TESTING'))
{
	return doLogin($_POST);
}

?>