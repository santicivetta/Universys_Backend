<?php  

include_once ('defines.php');
include_once ('funcionesGenerales.php');

function doABMCursadas($data) {
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
		

		if (strcmp($data["operacion"], "alta")==0) {

			if ( empty($data['idCatedra']) or empty($data['cuatrimestre']) or empty($data['año']) or empty($data['horario']) or empty($data['fechaParcial']) or empty($data['fechaRecuperatorio1']) or empty($data['fechaRecuperatorio2']) ) 
				throw new Exception(errorEnJson);

			$query3 = "SELECT * FROM Cursadas WHERE cuatrimestre='" . $data["cuatrimestre"] . "' and idCatedra = '".$data["idCatedra"]."'";

			$arrayCursada = $conexion->query($query3);

			if ($arrayCursada->num_rows == 1){
				$cursada = $arrayCursada->fetch_array(MYSQLI_ASSOC);
				if($cursada['fechaHasta']==null){
					throw new Exception(cursadaDuplicada);
				}else{
					$query4="UPDATE Cursadas set fechaHasta=null WHERE cuatrimestre='" . $data["cuatrimestre"] . "' and idCatedra = '".$data["idCatedra"]."'";
					if ($conexion->query($query4) === FALSE) {
						throw new Exception(errorConexionBase);
					};
					$data["idCursada"]=$arrayCursada["idCursada"];
					$data["operacion"]='modificacion';
				}
			}else{

				$query = "INSERT INTO Cursadas (cuatrimestre, año, horario,	parcial, recuperatorio1, recuperatorio2) 
						  values ('".$data['cuatrimestre']."' , '".$data['año']."' , '".$data['horario']."' , '".$data['fechaParcial']."' , '".$data['fechaRecuperatorio1']."' , '".$data['fechaRecuperatorio2']."'";

				if ($conexion->query($query) === FALSE) {
					throw new Exception(errorConexionBase);
				};

				$arraySalida = armarSalida(null, salidaExitosa, "/ABMCursadas");
			}	
		}



		if (strcmp($data["operacion"], "modificacion")==0) {

			if ( empty($data['idCursada']) or empty($data['año']) or empty($data['horario']) or empty($data['fechaParcial']) or empty($data['fechaRecuperatorio1']) or empty($data['fechaRecuperatorio2']) ) 
				throw new Exception(errorEnJson);

			$query3='SELECT * FROM Cursadas WHERE idCursada="' . $data["idCursada"] . '"';

			$arrayCursada = $conexion->query($query3);

			if ($arrayCursada->num_rows == 1){

				$query = "UPDATE Cursadas 
				SET cuatrimestre = '".$data["cuatrimestre"]."',
					año = '".$data["año"]."',
					horario = '".$data["horario"]."' 
					parcial = '".$data["fechaParcial"]."',
					recuperatorio1 = '".$data["fechaRecuperatorio1"]."',
					recuperatorio2 = '".$data["fechaRecuperatorio2"]."'
				WHERE idCursada = '".$data["idCursada"]."'";

				if ($conexion->query($query) === FALSE) {
					throw new Exception(errorConexionBase);
				};

				$arraySalida = armarSalida(null, salidaExitosa, "/ABMCursadas");
			} else {
				throw new Exception(cursadaInexistente);
			}
		}


		if (strcmp($data["operacion"], "baja")==0) {

			if ( empty($data['idCursada']) ) 
				throw new Exception(errorEnJson);

			$query3='SELECT * FROM Cursadas WHERE idCursada="' . $data["idCursada"] . '"';

			$arrayCursada = $conexion->query($query3);

			if ($arrayCursada->num_rows == 1){

				$query = "	UPDATE Cursadas 
				SET fechaHasta = curdate()
				WHERE idCursada = '".$data["idCursada"] . "'";

				if ($conexion->query($query) === FALSE) {
					throw new Exception(errorConexionBase);
				};

				$query2 = "	update ProfesoresXCursada
				set fechaHasta = curdate()
				where idCursada = '".$data["idCursada"]."'";							

				if ($conexion->query($query2) === FALSE) {
					throw new Exception(errorConexionBase);
				};

				$query3 = "	update AlumnosXCursada
				set fechaHasta = curdate()
				where idCursada = '".$data["idCursada"]."'";							

				if ($conexion->query($query3) === FALSE) {
					throw new Exception(errorConexionBase);
				};

				$arraySalida = armarSalida(null, salidaExitosa, "/ABMCursadas");
			}else{
				throw new Exception(cursadaInexistente);
			}
		}

		$conexion->close();

		echo $arraySalida;

	} catch (Exception $e) {

		$arraySalida = armarSalida(null, $e->getMessage(), "/ABMCursadas");

		if (isset($conexion)) {
			$conexion->close();
		}

		echo $arraySalida;

	}
}


if(!defined('TESTING'))
{
	return doABMCursadas($_POST);
}

?>