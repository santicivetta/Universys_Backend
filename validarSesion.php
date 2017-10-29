<?php

include_once ('defines.php');

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
			echo "actualizo sesion " . $sesion;
			actualizarFechaSesion($conexion,$sesion);
		}else{
			echo "elimino sesion " . $sesion;
			eliminarSesion($conexion,$sesion);
			throw new Exception(sesionVencida);
		}
	}else{
		echo "fechaAlta es hoy";
	}	
	return array('usuario'=>$row['usuario'],'tabla'=>$row['tabla']);
}

function actualizarFechaSesion($conexion,$sesion){
	$query='UPDATE Sesiones SET fechaAlta=curdate() WHERE idSesion="' . $sesion . '"';
	$result = $conexion->query($query);

	if ($conexion->connect_errno)
	    throw new Exception(errorConexionBase);

	var_dump($conexion->affected_rows);

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