<?php

include_once (__DIR__ . '/../../defines.php');
include_once (__DIR__ . '/../../funcionesGenerales.php');
include_once (__DIR__ . '/../funcionesTesting.php');

$eCon=null;
try{
$conexion = connect(True);
ejecutarSQL('validarSesion.sql',$conexion);
} 
catch (Exception $eCon) {
    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
}

if ($eCon==null){
	//PRUEBA 1
	try {
		$datosUsuario=validarSesion($conexion,1);
	} catch (Exception $e1){}

	echo ">> <strong>ValidarSesion</strong> </br>";
	if ($e1->getMessage()==sesionVencida)
		echo "Prueba1 OK </br>";
	else
		echo "Prueba1 FAIL </br>";

	//PRUEBA 2
	try {
		$datosUsuario=validarSesion($conexion,2);
	} catch (Exception $e2){}

	if ( ($datosUsuario['usuario']=='gaston.bodeman@comunidad.ub.edu.ar') and
		 ($datosUsuario['tabla']=='Alumnos') ) 
		echo "Prueba2 OK </br>";
	else
		echo "Prueba2 FAIL </br>";

	//PRUEBA 2
	try {
		$datosUsuario=validarSesion($conexion,3);
	} catch (Exception $e3){}

	if ( ($datosUsuario['usuario']=='andres.didier@comunidad.ub.edu.ar') and
		 ($datosUsuario['tabla']=='Profesores') ) 
		echo "Prueba3 OK </br>";
	else
		echo "Prueba3 FAIL </br>";
}
?>