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
	    throw new Exception('802');


	if ($result->num_rows != 1)
		throw new Exception('1501');

	$row = $result->fetch_array(MYSQLI_ASSOC);

	$fecha = new DateTime($row['fechaAlta']);
	$fecha->add(new DateInterval('P' . diasSesiones . 'D'));
	echo $fecha->format('Y-m-d') . "\n";

	//*return array('usuario'=>$row['usuario'],'tabla'=>$row['tabla']);
}

?>