<?php

public static function validarSesion($conexion, $sesion){
	$query='SELECT u.usuario,r.tabla
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

	$row = $result->fetch_array(MYSQL_ASSOC);
	return array('usuario'=>$row['usuario'],'tabla'=>$row['tabla']);
}

?>