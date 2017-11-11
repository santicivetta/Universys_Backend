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

function getIdCarrera($conexion, $nombreCarrera){

	$query="SELECT idCarrera
			FROM 	Carreras
			WHERE 	fechaHasta is null
			and nombre = '" .$nombreCarrera."'";

	$result = $conexion->query($query);

	if ($result->num_rows != 1)
		throw new Exception(carreraInexistente);

	$row = $result->fetch_array(MYSQLI_ASSOC);

	return $row["idCarrera"];

}

function getIdMateria($conexion, $nombreMateria){

	$query="SELECT idMateria
			FROM 	Materias
			WHERE 	fechaHasta is null
			and nombre = '" .$nombreMateria."'";

	$result = $conexion->query($query);

	if ($result->num_rows != 1)
		throw new Exception(materiaInexistente);

	$row = $result->fetch_array(MYSQLI_ASSOC);

	return $row["idMateria"];

}

function doABMCarreras($data) {
	try {

		if (!(isset($_POST))) {
			throw new Exception(errorInesperado);	
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

		//para cualquier accion con materia, tiene que existir la carrera a la que pertenece
		$idCarrera = getIdCarrera($conexion, $_POST["carrera"]);

		if strcmp($_POST["operacion"], "alta") {

			$query = "INSERT INTO Materias (nombre) values ('".$_POST["materia"]."')";

			if ($conexion->query($query) === FALSE) {
				throw new Exception(errorConexionBase);
			};

			$idMateria = getIdMateria($conexion, $_POST["materia"]);

			$query2 = "INSERT INTO MateriasXCarreras (idCarrera, idMateria) values ('".$idCarrera."','".$idMateria."')";

			if ($conexion->query($query2) === FALSE) {
				throw new Exception(errorConexionBase);
			};

			$arraySalida = armarSalida(null, "200");
		}

		if strcmp($_POST["operacion"], "modificacion") {

			$query = "UPDATE Materias SET nombre = '".$_POST["materia"]."'
						WHERE idMateria = '".$_POST["idMateria"] . "'";

			if ($conexion->query($query) === FALSE) {
				throw new Exception(errorConexionBase);
			};

			$arraySalida = armarSalida(null, "200");

		}

		if strcmp($_POST["operacion"], "baja") {

			$query = "	UPDATE 
					FROM Materias 
					SET fechaHasta = curdate()
					WHERE idMateria = '".$_POST["idMateria"] . "'";

			if ($conexion->query($query) === FALSE) {
				throw new Exception(errorConexionBase);
			};

			$query = "	DELETE
					FROM MateriasXCarreras 
					WHERE idMateria = '".$_POST["idMateria"] . "'
					and idCarrera = '".$idCarrera"'";

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
}


if(!defined('TESTING'))
{
	return doABMCarreras($_POST);
}

?>