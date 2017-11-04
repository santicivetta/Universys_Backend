<?php

include_once ('defines.php');

function connect($testing=False){
	if($testing || defined('TESTING'))
		$connection = new mysqli(hostBase, usuarioBase, contraseñaBase, nombreBaseTesting);
	else
		$connection = new mysqli(hostBase, usuarioBase, contraseñaBase, nombreBase);

	if ($connection->connect_errno) {
		throw new Exception(errorConexionBase);
	}

	$connection->query("SET NAMES 'utf8'");

	return $connection;
}


function VersionDeAPICorrecta($versionAChequear){
	if ($versionAChequear == apiVersionActual) {
		return True;
	} else {
		throw new Exception(apiNoCompatible);
	}
}

function validoCredenciales($conexion, $usuario, $contraseña){
	
	$query = "SELECT * FROM Usuarios WHERE usuario = '" . $usuario . "' and contraseña = '" . md5($contraseña) ."'";
	
	$result = $conexion->query($query);

	if ($conexion->connect_errno) {
		throw new Exception(errorConexionBase);
	}

	if ($result->num_rows == 1) {
		$row = $result->fetch_array(MYSQLI_ASSOC);
		
		return True;
		
	} else {
		throw new Exception(credencialesIncorrectas);
	}

}

function armarSalida($misDatos, $codigoSalida){

	if (isset($misDatos)){
		$result = json_encode(array_merge(array
							("direccionServidor"=>"http://universys.site/login", 
							 "apiVer"=>apiVersionActual,
							 "errorId"=>$codigoSalida), $misDatos)
						);
	} else {
		$result = json_encode(array
							("direccionServidor"=>"http://universys.site/login", 
							 "apiVer"=>apiVersionActual,
							 "errorId"=>$codigoSalida)
						);
	}

    //si no hay errores lo devuelvo
	if($result) {
		return $result;
	}

	throw new Exception(errorInesperado);
}

function altaSesion($conexion, $idUsuario){
	
	if ($conexion->connect_errno) {
		throw new Exception(errorConexionBase);
	}

	$query = "INSERT INTO Sesiones(usuario,fechaAlta) values ('".$idUsuario."',CURDATE())";

	if ($conexion->query($query) === FALSE) {
		throw new Exception(errorConexionBase);
	};

	$query = "SELECT max(idSesion) as idSesion from Sesiones WHERE Usuario = '".$idUsuario."'";

	$result = $conexion->query($query);

	if ($result->num_rows == 1) {
		$row = $result->fetch_array(MYSQLI_ASSOC);
		
		return $row["idSesion"];
		
	} else {
		throw new Exception(errorConexionBase);
	}

}


function validarSesion($conexion, $sesion){
	$query='SELECT u.usuario,r.tabla,s.fechaAlta
			FROM 	Sesiones s,
					Usuarios u,
					Roles r
			WHERE 	s.usuario=u.usuario
			and		u.idRol=r.idRol
			and s.idSesion="' . $sesion . '"';
	$result = $conexion->query($query);

	if ($conexion->connect_errno)
	    throw new Exception(errorConexionBase);


	if ($result->num_rows != 1)
		throw new Exception(sesionInexistente);

	$row = $result->fetch_array(MYSQLI_ASSOC);

	$fecha = new DateTime($row['fechaAlta']);
	if($fecha->format('Y-m-d')!=date("Y-m-d")){
		$fecha->add(new DateInterval('P' . diasSesiones . 'D'));
		if ($fecha->format('Y-m-d')>=date("Y-m-d")){
			//echo "actualizo sesion " . $sesion;
			actualizarFechaSesion($conexion,$sesion);
		}else{
			//echo "elimino sesion " . $sesion;
			eliminarSesion($conexion,$sesion);
			throw new Exception(sesionVencida);
		}
	}	
	return array('usuario'=>$row['usuario'],'tabla'=>$row['tabla']);
}

function actualizarFechaSesion($conexion,$sesion){
	$query='UPDATE Sesiones SET fechaAlta=curdate() WHERE idSesion="' . $sesion . '"';
	$result = $conexion->query($query);

	if ($conexion->connect_errno)
	    throw new Exception(errorConexionBase);

	if ($conexion->affected_rows != 1)
		throw new Exception(sesionInexistente);

}

function eliminarSesion($conexion,$sesion){
	$query='DELETE FROM Sesiones WHERE idSesion="' . $sesion . '"';
	$result = $conexion->query($query);

	if ($conexion->connect_errno)
	    throw new Exception(errorConexionBase);


	if ($conexion->affected_rows != 1)
		throw new Exception(sesionInexistente);
}

?>