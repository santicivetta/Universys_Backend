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

	return array_merge($row2, array("rol"=> $row["descripcion"]));
}


try {

	if (!(isset($_POST))) {
		throw new Exception(errorInesperado);	
	}

	if (!(empty($_POST["idSesion"]))) {
		throw new Exception(sesionDuplicada);	
	}

	//chequeo version
	VersionDeAPICorrecta($_POST["apiVer"]);

	//conecto a la base
	$conexion = connect();

	//valido sesion
	$miUsuario = validarSesion($conexion, $_POST["idSesion"]);

	//valido permisos
	if strcmp($miUsuario["tabla"],"Administradores") != 0 {
		throw new Exception(permisosErroneos);	
	}





	if strcmp($_POST["operacion"], "alta") {
		/*
		“operacion” :”modificacion”,
		“nombre” : “Gaston”,
		“apellido” : “Bodeman”,
		“fnac” : “20/09/1994”,
		“genero” : “masculino”,
		“domicilio” : “blanco encalada 4892”,
		“telefono” : “45228786”,
		“matrícula“ : “03140”,
		“mail” : ”pepito@gmail.com”
		*/


		//----------$query = "INSERT INTO Usuarios(usuario,fechaAlta) values ('".$idUsuario."',CURDATE())";

		if ($conexion->query($query) === FALSE) {
			throw new Exception(errorConexionBase);
		};

		$arraySalida = armarSalida(null, "200");
	}

	if strcmp($_POST["operacion"], "modificacion") {

		//----------$query = "INSERT INTO Usuarios(usuario,fechaAlta) values ('".$idUsuario."',CURDATE())";

		if ($conexion->query($query) === FALSE) {
			throw new Exception(errorConexionBase);
		};

		$arraySalida = armarSalida(null, "200");
		
	}

	if strcmp($_POST["operacion"], "baja") {

		//----------$query = "INSERT INTO Usuarios(usuario,fechaAlta) values ('".$idUsuario."',CURDATE())";

		if ($conexion->query($query) === FALSE) {
			throw new Exception(errorConexionBase);
		};

		$arraySalida = armarSalida(null, "200");
		
	}


	//$arraySalida = armarSalida(array("usuario"=>$salidaFinal), "200");

	$conexion->close();

	echo $arraySalida;

} catch (Exception $e) {

	$arraySalida = armarSalida(null, $e->getMessage());

	if (isset($conexion)) {
		$conexion->close();
	}

	echo $arraySalida;

}

?>