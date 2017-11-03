<?php


include_once ('defines.php');
include_once ('funcionesGenerales.php');

function traerDatos($conexion, $idUsuario){

	$query="SELECT u.usuario,r.tabla, r.descripcion
			FROM 	Usuarios u,
					Roles r
			WHERE 	u.idRol=r.idRol
			and u.usuario = '" .$idUsuario."'";

	$result = $conexion->query($query);

	if ($conexion->connect_errno)
	    throw new Exception(errorConexionBase);


	if ($result->num_rows != 1)
		throw new Exception(sesionInexistente);

	$row = $result->fetch_array(MYSQLI_ASSOC);

	$query2 = "SELECT nombre, apellido FROM " . $row["tabla"] . " where mail = '" . $row["usuario"] . "'";

	$result2 = $conexion->query($query2);

	if ($conexion->connect_errno) {
		throw new Exception(errorConexionBase);
	}

	if ($result2->num_rows != 1)
		throw new Exception(sesionInexistente);

	$row2 = $result2->fetch_array(MYSQLI_ASSOC);

	return array($row2, "rol"=> $row["descripcion"]);
}

//espera json con datos y un codigo de salida
function armarSalidaTest($misDatos, $codigoSalida){

	$result = json_encode(array_merge(array
							("direccionServidor"=>"http://universys.site/login", 
							 "apiVer"=>apiVersionActual,
							 "errorId"=>$codigoSalida), $misDatos)
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
	validoCredenciales($conexion, $_POST["mail"], $_POST["password"]);

	//ejecuto query para traer datos necesarios
	$arrayDatosUsuario = traerDatos($conexion, $_POST["mail"]);

	//$arraySalida = armarSalidaTest($arrayDatosUsuario[0], "200");

	$arraySalida2 = armarSalida(array("usuario"=>$arrayDatosUsuario[0]), "200");

	$conexion->close();

	echo $arraySalida2;

} catch (Exception $e) {

	$arraySalida = armarSalidaTest(null, $e->getMessage() );

	if (isset($conexion)) {
		$conexion->close();
	}
	
	echo $arraySalida;

};

?>