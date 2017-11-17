<?php  


include_once ('defines.php');
include_once ('funcionesGenerales.php');

function doABMCarreras($data) {
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
		if (strcmp($miUsuario["tabla"],"Administradores") != 0) {
			throw new Exception(permisosErroneos);	
		}

		if (strcmp($data["operacion"], "alta") == 0) {

			if ( empty($data['carrera']) ) 
				throw new Exception(errorEnJson);

			$query3='SELECT * FROM Carreras WHERE nombre="' . $data["carrera"] . '"';

			$arrayCarrera = $conexion->query($query3);

			if ($arrayCarrera->num_rows == 1){

				$carrera = $arrayCarrera->fetch_array(MYSQLI_ASSOC);
				if($carrera['fechaHasta']==null){
					throw new Exception(carreraDuplicada);
				}else{
					$query4='UPDATE Carreras set fechaHasta=null WHERE nombre="' . $data['carrera'] . '"';
					if ($conexion->query($query4) === FALSE) {
						throw new Exception(errorConexionBase);
					};
				}

			}else{

				$query = "INSERT INTO Carreras (nombre) values ('" . $data["carrera"] . "')";

				if ($conexion->query($query) === FALSE) {
					throw new Exception(errorConexionBase);
				};

			}
		}elseif (strcmp($data["operacion"], "modificacion")==0) {

			if ( empty($data['carrera']) or empty($data['idCarrera']) ) 
				throw new Exception(errorEnJson);

			$query3='SELECT * FROM Carreras WHERE nombre="' . $data["carrera"] . '"';

			$arrayCarrera = $conexion->query($query3);

			if ($arrayCarrera->num_rows == 1)
				throw new Exception(carreraDuplicada);
				
			$query = "UPDATE Carreras SET nombre = '".$data["carrera"]."'
			WHERE idCarrera = '".$data["idCarrera"] . "'";
				
			if ($conexion->query($query) === FALSE)
				throw new Exception(errorConexionBase);

			if ($conexion->affected_rows==0)
				throw new Exception(carreraInexistente);

		}elseif (strcmp($data["operacion"], "baja")==0) {
			
			if ( empty($data['idCarrera']) ) 
				throw new Exception(errorEnJson);

			$query = "UPDATE Carreras 
			SET fechaHasta = curdate()
			WHERE idCarrera = '".$data["idCarrera"] . "'";

			if ($conexion->query($query) === FALSE) {
				throw new Exception(errorConexionBase);
			};

			if ($conexion->affected_rows==0)
				throw new Exception(carreraInexistente);

		}else{
			throw new Exception(errorEnJson);
		}

		$arraySalida = armarSalida(null, salidaExitosa, "/ABMCarreras");

		$conexion->close();

		return $arraySalida;

	} catch (Exception $e) {

		$arraySalida = armarSalida(null, $e->getMessage(), "/ABMCarreras");

		if (isset($conexion)) {
			$conexion->close();
		}

		return $arraySalida;

	}
}


if(!defined('TESTING'))
{
	return doABMCarreras($_POST);
}

?>