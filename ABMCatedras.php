<?php  

include_once ('defines.php');
include_once ('funcionesGenerales.php');

function doABMCatedras($data) {
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
		//echo "<pre>".var_dump($conexion). "</pre> <br> <pre>". var_dump($data["idSesion"])."</pre>";
/*
		$query3="SELECT * FROM Sesiones";

		$arrayCatedra = $conexion->query($query3);
		$catedra = $arrayCatedra->fetch_array(MYSQLI_ASSOC);
		//echo var_dump($catedra);


*/
		$miUsuario = validarSesion($conexion, $data["idSesion"]);

	//valido permisos
		if (strcmp($miUsuario["tabla"],"Administradores") != 0) {
			throw new Exception(permisosErroneos);	
		}
		

		if (strcmp($data["operacion"], "alta")==0) {

			if ( empty($data['idMateria']) or empty($data['catedra']) or empty($data['titularDeCatedra']) or empty($data['horasCatedra']) ){
				throw new Exception(errorEnJson);
			}

			$query3="SELECT * FROM Catedras WHERE catedra='" . $data["catedra"] . "' and idMateria = '".$data["idMateria"]."'";

			$arrayCatedra = $conexion->query($query3);

			if ($arrayCatedra->num_rows == 1){
				$catedra = $arrayCatedra->fetch_array(MYSQLI_ASSOC);
				if($catedra['fechaHasta']==null){
					throw new Exception(catedraDuplicada);
				}else{
					$query4="UPDATE Catedras set fechaHasta=null WHERE catedra= '" . $data['catedra'] . "' and idMateria = '".$data["idMateria"]."'";
					if ($conexion->query($query4) === FALSE) {
						throw new Exception(errorConexionBase);
					};
					$data["operacion"]='modificacion';
					$data["idCatedra"]=$catedra["idCatedra"];
				}
			}else{

				$query = "INSERT INTO Catedras (idMateria, catedra, horasCatedra, titular) values ('".$data["idMateria"]."','".$data["catedra"]."','".$data["horasCatedra"]."','".$data["titularDeCatedra"]."')";

				if ($conexion->query($query) === FALSE) {
					throw new Exception(errorConexionBase);
				};

				$arraySalida = armarSalida(null, salidaExitosa, "/ABMCatedras");
			}	
		}



		if (strcmp($data["operacion"], "modificacion")==0) {

			if ( empty($data['idCatedra']) or empty($data['catedra']) or empty($data['titularDeCatedra']) or empty($data['horasCatedra']) ) 
				throw new Exception(errorEnJson);

			$query3='SELECT * FROM Catedras WHERE idCatedra="' . $data["idCatedra"] . '"';

			$arrayCatedra = $conexion->query($query3);

			if ($arrayCatedra->num_rows == 1){

				$query = "UPDATE Catedras 
				SET catedra = '".$data["catedra"]."',
				horasCatedra = '".$data["horasCatedra"]."',
				titular = '".$data["titularDeCatedra"]."' 
				WHERE idCatedra = '".$data["idCatedra"]."'";

				if ($conexion->query($query) === FALSE) {
					throw new Exception(errorConexionBase);
				};

				$arraySalida = armarSalida(null, salidaExitosa, "/ABMCatedras");
			} else {
				throw new Exception(catedraInexistente);
			}
		}


		if (strcmp($data["operacion"], "baja")==0) {

			if ( empty($data['idCatedra']) ) 
				throw new Exception(errorEnJson);

			$query3='SELECT * FROM Catedras WHERE idCatedra="' . $data["idCatedra"] . '"';

			$arrayCatedra = $conexion->query($query3);

			if ($arrayCatedra->num_rows == 1){

				$query = "	UPDATE Catedras 
				SET fechaHasta = curdate()
				WHERE idCatedra = '".$data["idCatedra"] . "'";

				if ($conexion->query($query) === FALSE) {
					throw new Exception(errorConexionBase);
				};

				$query3 = "	update Cursadas
				set fechaHasta = curdate()
				where idCatedra = '".$data["idCatedra"]."'";							

				if ($conexion->query($query3) === FALSE) {
					throw new Exception(errorConexionBase);
				};

				$arraySalida = armarSalida(null, salidaExitosa, "/ABMCatedras");
			}else{
				throw new Exception(catedraInexistente);
			}
		}

		$conexion->close();

		return $arraySalida;

	} catch (Exception $e) {

		$arraySalida = armarSalida(null, $e->getMessage(), "/ABMCatedras");

		if (isset($conexion)) {
			$conexion->close();
		}

		return $arraySalida;

	}
}


if(!defined('TESTING'))
{
	return doABMCatedras($_POST);
}

?>