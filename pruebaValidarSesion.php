<?php

include ('validarSesion.php');
include ('defines.php');

function connect(){
	$connection = new mysqli(hostBase, usuarioBase, contraseñaBase, nombreBase);

	if ($mysqli->connect_errno) {
	    throw new Exception('802');
	}

	return $connection;
}




try{
$conexion = connect();
validarSesion($conexion,1);
} 
catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
}

?>