<?php  

include_once ('defines.php');
include_once ('funcionesGenerales.php');

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

function verificarIdMateria($conexion, $idMateria){

	$query="SELECT *
	FROM 	Materias
	WHERE 	idMateria ='" .$idMateria."'";

	$result = $conexion->query($query);

	if ($result->num_rows != 1)
		throw new Exception(materiaInexistente);

}

function doABMMaterias($data) {
	try {
		
		if (!(isset($data))) {
			throw new Exception(errorInesperado);	
		}

		if ( empty($data['idSesion']) or empty($data['apiVer']) or empty($data['operacion']) ) 
			throw new Exception(errorEnJson);	

	//chequeo version
		VersionDeAPICorrecta($data["apiVer"]);

	//conecto a la base
		$conexion = connect();

	//valido sesion
		$miUsuario = validarSesion($conexion, $data["idSesion"]);

	//valido permisos
		if (strcmp($miUsuario["tabla"],"Administradores") != 0 ){
			throw new Exception(permisosErroneos);	
		}
		
		if (strcmp($data["operacion"], "alta")==0) {

			$query3='SELECT * FROM Materias WHERE nombre="' . $data["materia"] . '"';

			$arrayMateria = $conexion->query($query3);

			if ( empty($data['materia']) ) 
				throw new Exception(errorEnJson);
			
			if ($arrayMateria->num_rows == 1){
				$materia = $arrayMateria->fetch_array(MYSQLI_ASSOC);
				if($materia['fechaHasta']==null){
					throw new Exception(materiaDuplicada);
				}else{
					$query4='UPDATE Materias set fechaHasta=null WHERE idMateria="' . $materia['idMateria'] . '"';
					if ($conexion->query($query4) === FALSE) {
						throw new Exception(errorConexionBase);
					};
				}
			}else{

				$query = "INSERT INTO Materias (nombre) values ('".$data["materia"]."')";

				if ($conexion->query($query) === FALSE) {
					throw new Exception(errorConexionBase);
				};
			}
		}elseif (strcmp($data["operacion"], "modificacion")==0) {

			if ( empty($data['materia']) or empty($data['idMateria']) ) 
				throw new Exception(errorEnJson);

			verificarIdMateria($conexion,$data['idMateria']);

			$query = "UPDATE Materias SET nombre = '".$data["materia"]."'
						WHERE idMateria = '".$data["idMateria"] . "'";

			if ($conexion->query($query) === FALSE) {
				throw new Exception(errorConexionBase);
			};

		}elseif (strcmp($data["operacion"], "baja")==0) {
				
			if ( empty($data['idMateria']) ) 
				throw new Exception(errorEnJson);

			verificarIdMateria($conexion,$data['idMateria']);
			
			$query = "	UPDATE Materias 
			SET fechaHasta = curdate()
			WHERE idMateria = '".$data["idMateria"] . "'";

			if ($conexion->query($query) === FALSE) {
				throw new Exception(errorConexionBase);
			};

			$query2 = "	update Catedras
						set fechaHasta = curdate()
						where idMateria = '".$data["idMateria"]."'";

			if ($conexion->query($query2) === FALSE) {
				throw new Exception(errorConexionBase);
			};

			$query3 = "	update Cursadas
						set fechaHasta = curdate()
						where idMateria = '".$data["idMateria"]."'";							

			if ($conexion->query($query3) === FALSE) {
				throw new Exception(errorConexionBase);
			};

		}else{
			throw new Exception(errorEnJson);
		}

		$conexion->close();

		$arraySalida = armarSalida(null, salidaExitosa, "/ABMMaterias");

		return $arraySalida;

	} catch (Exception $e) {

		$arraySalida = armarSalida(null, $e->getMessage(), "/ABMMaterias");

		if (isset($conexion)) {
			$conexion->close();
		}

		return $arraySalida;

	}
}


if(!defined('TESTING'))
{
	return doABMMaterias($_POST);
}

?>