<?php

include_once ('validarSesion.php');
include_once ('defines.php');

function connect(){
	$connection = new mysqli(hostBase, usuarioBase, contraseñaBase, nombreBase);

	if ($connection->connect_errno) {
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