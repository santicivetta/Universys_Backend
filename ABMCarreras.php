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
		if strcmp($miUsuario["tabla"],"Administradores") != 0 {
			throw new Exception(permisosErroneos);	
		}

		$query3='SELECT * FROM Carreras WHERE nombre="' . $data["carrera"] . '"';

		$arrayCarrera = $conexion->query($query3);

		if (strcmp($data["operacion"], "alta") == 0) {

			if ( empty($data['carrera']) ) 
				throw new Exception(errorEnJson);

			if ($arrayCarrera->num_rows == 1){
				$carrera = $arrayCarrera->fetch_array(MYSQLI_ASSOC);
				if($carrera['fechaHasta']==null){
					throw new Exception(carreraDuplicada);
				}else{
					$query4='UPDATE Carreras set fechaHasta=null WHERE nombre="' . $data['carrera'] . '"';
					if ($conexion->query($query4) === FALSE) {
						throw new Exception(errorConexionBase);
					};
					$data["operacion"]='modificacion';
				}
			}else{

				$query = "INSERT INTO Carreras (nombre) values ('".$data["carrera"]."'')";

				if ($conexion->query($query) === FALSE) {
					throw new Exception(errorConexionBase);
				};

				$arraySalida = armarSalida(null, salidaExitosa, "/ABMCarreras");
			}

			if strcmp($data["operacion"], "modificacion") {

				if ( empty($data['carrera']) or empty($data['id_carrera']) ) 
					throw new Exception(errorEnJson);

				if ($arrayCarrera->num_rows == 1){

					$query = "UPDATE Carreras SET nombre = '".$data["carrera"]."'
					WHERE idCarrera = '".$data["id_carrera"] . "'";

					if ($conexion->query($query) === FALSE) {
						throw new Exception(errorConexionBase);
					};

					$arraySalida = armarSalida(null, salidaExitosa, "/ABMCarreras");
				} else {
					throw new Exception(carreraInexistente);
				}

			}

			if strcmp($data["operacion"], "baja") {
				
				if ( empty($data['id_carrera']) ) 
					throw new Exception(errorEnJson);

				if ($arrayUsuario->num_rows == 1){

					$query = "	UPDATE Carreras 
					SET fechaHasta = curdate()
					WHERE idCarrera = '".$data["id_carrera"] . "'";

					if ($conexion->query($query) === FALSE) {
						throw new Exception(errorConexionBase);
					};

					$query2 = "	update Materias
								set fechaHasta = curdate()
								where idMateria in (	select idMateria
														from MateriasXCarreras 
														where idCarrera = '".$data["id_carrera"]."'
														minus 
														select idMateria 
														from MateriasXCarreras
														where idCarrera != '".$data["id_carrera"]."')";

					if ($conexion->query($query2) === FALSE) {
						throw new Exception(errorConexionBase);
					};

					$query3 = "	update Catedras
								set fechaHasta = curdate()
								where idMateria in (	select idMateria
														from MateriasXCarreras 
														where idCarrera = '".$data["id_carrera"]."'
														minus 
														select idMateria 
														from MateriasXCarreras
														where idCarrera != '".$data["id_carrera"]."')";

					if ($conexion->query($query3) === FALSE) {
						throw new Exception(errorConexionBase);
					};

					$query4 = "	update Cursadas
								set fechaHasta = curdate()
								where idMateria in (	select idMateria
														from MateriasXCarreras 
														where idCarrera = '".$data["id_carrera"]."'
														minus 
														select idMateria 
														from MateriasXCarreras
														where idCarrera != '".$data["id_carrera"]."')";								

					if ($conexion->query($query4) === FALSE) {
						throw new Exception(errorConexionBase);
					};

					$arraySalida = armarSalida(null, salidaExitosa, "/ABMCarreras");
				}else{
					throw new Exception(carreraInexistente);
				}
			}

			$conexion->close();

			echo $arraySalida;

	} catch (Exception $e) {

		$arraySalida = armarSalida(null, $e->getMessage(), "/ABMCarreras");

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