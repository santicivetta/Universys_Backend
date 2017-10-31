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


try {

	if (!(isset($_POST))) {
		throw new Exception(errorInesperado);	
	}

	if (!(empty($_POST["idSesion"]))) {
		throw new Exception(sesionDuplicada);	
	}

	//conecto a la base
	$conexion = connect();

	//chequeo version
	VersionDeAPICorrecta($_POST["apiVer"]);

	//valido credenciales
	validoCredenciales($conexion, $_POST["mail"], $_POST["password"]);

	$datosUsuario = traerDatos($conexion, $_POST["mail"]);
	
	/*---------------FALTA VER FORMATO DE JSON Y AGREGAR IDSESION A LA SALIDA--------------*/
	$arraySalida = armarSalida($datosUsuario, "200");

	$conexion->close();

	echo json_encode($arraySalida);

} catch (Exception $e) {

	$arraySalida = armarSalida(null, $e->getMessage());

	if (isset($conexion)) {
		$conexion->close();
	}

	echo json_encode($arraySalida);

}

?>