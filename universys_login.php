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
		throw new Exception(usuarioInexistente);

	$row = $result->fetch_array(MYSQLI_ASSOC);

	$query2 = "SELECT nombre, apellido FROM " . $row["tabla"] . " where mail = '" . $row["usuario"] . "'";

	$result2 = $conexion->query($query2);

	if ($conexion->connect_errno) {
		throw new Exception(errorConexionBase);
	}

	if ($result2->num_rows != 1)
		throw new Exception(personaInexistente);

	$row2 = $result2->fetch_array(MYSQLI_ASSOC);

	return array_merge($row2, array("rol"=> $row["descripcion"]));
}


function doLogin($data) {
	try {

		if (!(isset($data))) {
			throw new Exception(errorInesperado);	
		}

		if (!(empty($data["idSesion"]))) {
			throw new Exception(sesionDuplicada);	
		}

		//chequeo version
		VersionDeAPICorrecta($data["apiVer"]);

		//conecto a la base
		$conexion = connect();

		//valido credenciales
		validoCredenciales($conexion, $data["mail"], $data["password"]);

		$idSesion = altaSesion($conexion, $data["mail"]);

		$datosUsuario = traerDatos($conexion, $data["mail"]);

		//tengo que mergear la idSesion con los datosUsuarios

		$salidaFinal = array_merge($datosUsuario, array("idSesion"=> $idSesion));

		$arraySalida = armarSalida(array("usuario"=>$salidaFinal), "200");

		$conexion->close();

		return $arraySalida;

	} catch (Exception $e) {

		$arraySalida = armarSalida(null, $e->getMessage());

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