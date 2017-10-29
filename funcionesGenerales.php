<?php

include_once ('defines.php');

function connect($testing=False){
	if($testing)
		$connection = new mysqli(hostBase, usuarioBase, contraseñaBase, nombreBaseTesting);
	else
		$connection = new mysqli(hostBase, usuarioBase, contraseñaBase, nombreBase);

	if ($connection->connect_errno) {
		throw new Exception(errorConexionBase);
	}

	return $connection;
}


function chequeoVersion($conexion, $versionAChequear){

	$result = $conexion->query("SELECT version FROM api_version WHERE fecha_hasta IS NULL");

	if ($conexion->connect_errno) {
		throw new Exception(errorConexionBase);
	}

	if ($result->num_rows == 1) {
		$row = $result->fetch_array(MYSQLI_ASSOC);
		return ($row["version"] == $versionAChequear);
	} else {
		throw new Exception(apiNoCompatible);
	}

}

function validoCredenciales($conexion, $usuario, $contraseña){
	
	
	$query = "SELECT * FROM Usuarios WHERE usuario = '" . $usuario . "' and contraseña = '" . md5($contraseña) ."'";
	
	/*
	echo "<br>";
	echo $query;
	echo "<br>";
	echo $contrasenia;
	*/
	//var_dump($conexion);
	$conexion->query("SET NAMES 'utf8'");
	$result = $conexion->query($query);

	if ($conexion->connect_errno) {
		throw new Exception(errorConexionBase);
	}
//	var_dump($result);
	if ($result->num_rows == 1) {
		$row = $result->fetch_array(MYSQLI_ASSOC);
//		var_dump($row);
		
//		echo "contraseña en la base: " . $row["contraseña"];
		return $row["usuario"];
		
	} else {
		throw new Exception(credencialesIncorrectas);
	}

}

function traigoDatos($conexion, $idUsuario){
	$result = $conexion->query("SELECT * FROM Usuarios where usuario='" . $idUsuario."'");

	if ($conexion->connect_errno) {
		throw new Exception(errorConexionBase);
	}

	while($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$myArray[] = $row;
	}

	return $myArray;

}

function armarSalida($misDatos, $codigoSalida){

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