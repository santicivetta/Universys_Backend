<?php  


include_once ('defines.php');
include_once ('funcionesGenerales.php');

function doABMUsuarios($data) {
	try {
		if (!(isset($data))) {
			throw new Exception(errorInesperado);	
		}

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

		$query3='SELECT u.*,r.tabla FROM Usuarios u, Roles r WHERE u.idRol=r.idRol and usuario="' . $data['mail'] . '"';

		$arrayUsuario = $conexion->query($query3);

		if ($arrayUsuario->num_rows == 1){
			$usuario = $arrayUsuario->fetch_array(MYSQLI_ASSOC);
		}

		if ($conexion->connect_errno) {
			throw new Exception(errorConexionBase);
		}

		if (strcmp($data["operacion"], "alta")==0) {

			if ($arrayUsuario->num_rows == 1){

				if($usuario['fechaHasta']==null){
					throw new Exception(usuarioDuplicado);
				}else{

					$query4='UPDATE Usuarios set fechaHasta=null WHERE usuario="' . $data['mail'] . '"';
					if ($conexion->query($query4) === FALSE) {
						throw new Exception(errorConexionBase);
					};
					$data["operacion"]='modificacion';

				}
			}else{

				if (strcmp($data["rol"],"Administrador")==0)
					$tabla="Administradores";
				elseif (strcmp($data["rol"],"Profesor")==0)
					$tabla="Administradores";
				elseif (strcmp($data["rol"],"Alumno")==0)
					$tabla="Alumnos";
				else
					throw new Exception(errorEnJson);

				$query6='SELECT * FROM Roles WHERE tabla="' . $tabla . '"';
				if (($arrayIdRol=$conexion->query($query6)) === FALSE) {
					throw new Exception(errorConexionBase);
				};

				if ($arrayIdRol->num_rows == 1){
					$idRol = $arrayIdRol->fetch_array(MYSQLI_ASSOC);
				}else{
					throw new Exception(errorInesperado);
				}

				$query = "INSERT INTO Usuarios(usuario, contraseña, idRol, fechaHasta) values ('".$data["mail"]."',md5('".$data["contraseña"]."')," . $idRol['idRol'] . ",null)";
					
				$query2 = "INSERT INTO " . $tabla . "(matricula, documento, nombre, apellido, mail, fechaNacimiento, genero, domicilio,telefono) values ('".$data["identificador"]."','".$data["documento"]."','".$data["nombre"]."','".$data["apellido"]."','".$data["mail"]."','".$data["fnac"]."','".$data["genero"]."','".$data["domicilio"]."','".$data["telefono"]."')";

				if ($conexion->query($query) === FALSE) {
					throw new Exception(errorConexionBase);
				};

				if ($conexion->query($query2) === FALSE) {
					throw new Exception(errorConexionBase);
				};

			}

		}

		if (strcmp($data["operacion"], "modificacion")==0) {

			if ($arrayUsuario->num_rows == 1){

				$query = 'UPDATE ' . $usuario['tabla'] . ' SET documento="' . $data['documento'] . '" , nombre="' . $data['nombre'] . '" , apellido="' . $data['apellido'] . '" , fechaNacimiento="' . $data['fnac'] . '" , genero="' . $data['genero'] . '" , domicilio="' . $data['domicilio'] . '" , telefono="' . $data['telefono'] . '" WHERE mail = "' . $data["mail"] . '"';

				$query5 = 'UPDATE Usuarios set contraseña=md5("' . $data['contraseña'] . '") WHERE usuario = "' . $data["mail"] . '"';

				if ($conexion->query($query) === FALSE) {
					throw new Exception(errorConexionBase);
				};

				if ($conexion->query($query5) === FALSE) {
					throw new Exception(errorConexionBase);
				};
			}else{
				throw new Exception(usuarioInexistente);
			}
			
		}

		if (strcmp($data["operacion"], "baja")==0) {

			if ($arrayUsuario->num_rows == 1){

				$query = 'UPDATE Usuarios SET fechaHasta = curdate() WHERE usuario = "' . $data["mail"] . '"';

				if ($conexion->query($query) === FALSE) {
					throw new Exception(errorConexionBase);
				};

			}else{
				throw new Exception(usuarioInexistente);
			}
		}

		$arraySalida = armarSalida(null, "200","/ABMUsuarios");

		$conexion->close();

		return $arraySalida;

	} catch (Exception $e) {

		$arraySalida = armarSalida(null, $e->getMessage(),"/ABMUsuarios");

		if (isset($conexion)) {
			$conexion->close();
		}

		return $arraySalida;

	}
}

if(!defined('TESTING'))
{
	return doABMUsuarios($_POST);
}
?>