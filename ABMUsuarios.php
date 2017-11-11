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

		if strcmp($_POST["tabla"],"administrador") {
			$query = "INSERT INTO Usuarios(usuario, contraseña, fechaAlta) values ('".$_POST["mail"]."','".$_POST["contrasena"]."',CURDATE())";
			
			$query2 = "INSERT INTO Administradores(legajo, documento, nombre, apellido, mail, fechaNacimiento, genero, domicilio) values ('".$_POST["identificador"]."','".$_POST["documento"]."','".$_POST["nombre"]."','".$_POST["apellido"]."','".$_POST["mail"]."','".$_POST["fnac"]."','".$_POST["genero"]."','".$_POST["domicilio"]."')";

		}

		if strcmp($_POST["tabla"],"profesor") {
			$query = "INSERT INTO Usuarios(usuario, contraseña, fechaAlta) values ('".$_POST["mail"]."','".$_POST["contrasena"]."',CURDATE())";

			$query2 = "INSERT INTO Profesores(legajo, documento, nombre, apellido, mail, fechaNacimiento, genero, domicilio) values ('".$_POST["identificador"]."','".$_POST["documento"]."','".$_POST["nombre"]."','".$_POST["apellido"]."','".$_POST["mail"]."','".$_POST["fnac"]."','".$_POST["genero"]."','".$_POST["domicilio"]."')";

		}

		if strcmp($_POST["tabla"],"alumno") {
			$query = "INSERT INTO Usuarios(usuario, contraseña, fechaAlta) values ('".$_POST["mail"]."','".$_POST["contrasena"]."',CURDATE())";

			$query2 = "INSERT INTO Alumnos(matricula, documento, nombre, apellido, mail, fechaNacimiento, genero, domicilio) values ('".$_POST["identificador"]."','".$_POST["documento"]."','".$_POST["nombre"]."','".$_POST["apellido"]."','".$_POST["mail"]."','".$_POST["fnac"]."','".$_POST["genero"]."','".$_POST["domicilio"]."')";

		}

		if ($conexion->query($query) === FALSE) {
			throw new Exception(errorConexionBase);
		};

		if ($conexion->query($query2) === FALSE) {
			throw new Exception(errorConexionBase);
		};

		$arraySalida = armarSalida(null, "200");
	}

	if strcmp($_POST["operacion"], "modificacion") {

		if strcmp($_POST["tabla"],"administrador") {
			$query = "UPDATE Administradores SET (legajo, documento, nombre, apellido, mail, fechaNacimiento, genero, domicilio) = ('".$_POST["identificador"]."','".$_POST["documento"]."','".$_POST["nombre"]."','".$_POST["apellido"]."','".$_POST["mail"]."','".$_POST["fnac"]."','".$_POST["genero"]."','".$_POST["domicilio"]."')";

		}

		if strcmp($_POST["tabla"],"profesor") {
			$query = "UPDATE Profesores SET (legajo, documento, nombre, apellido, mail, fechaNacimiento, genero, domicilio) = ('".$_POST["identificador"]."','".$_POST["documento"]."','".$_POST["nombre"]."','".$_POST["apellido"]."','".$_POST["mail"]."','".$_POST["fnac"]."','".$_POST["genero"]."','".$_POST["domicilio"]."')";

		}

		if strcmp($_POST["tabla"],"alumno") {
			$query = "UPDATE Alumnos SET (matricula, documento, nombre, apellido, mail, fechaNacimiento, genero, domicilio) = ('".$_POST["identificador"]."','".$_POST["documento"]."','".$_POST["nombre"]."','".$_POST["apellido"]."','".$_POST["mail"]."','".$_POST["fnac"]."','".$_POST["genero"]."','".$_POST["domicilio"]."')";

		}

		if ($conexion->query($query) === FALSE) {
			throw new Exception(errorConexionBase);
		};

		$arraySalida = armarSalida(null, "200");
		
	}

	if strcmp($_POST["operacion"], "baja") {

		$query = '	UPDATE 
					FROM Usuarios 
					SET fechaHasta = curdate()
					WHERE mail = "' . $_POST["mail"] . '"';

		if ($conexion->query($query) === FALSE) {
			throw new Exception(errorConexionBase);
		};

		$arraySalida = armarSalida(null, "200");
		
	}

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