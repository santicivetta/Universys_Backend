<?php
define('TESTING', 'true');
include_once (__DIR__ . '/../funcionesTesting.php');
include_once (__DIR__ . '/../../funcionesGenerales.php');
include_once (__DIR__ . '/../../defines.php');
include_once ('../../ABMCatedras.php');

$eCon=null;
try{
$conexion = connect(True);
ejecutarSQL('alta.sql',$conexion);
} 
catch (Exception $eCon) {
    echo 'Excepción capturada: ',  $eCon->getMessage(), "\n";
}
if($eCon==null){
  echo ">> <strong>Alta Catedras</strong> </br>";

  //PRUEBA 1: doy de alta una catedra inexistente
  $datos= doABMCatedras(["apiVer" => apiVersionActual, "idSesion" => "1", "operacion" => "alta", "idMateria" => "1", "catedra" => "Mendez", "titularDeCatedra" => "Jose Mendez", "horasCatedra" => "10", "idCatedra" => ""]);
  $datosParseados= json_decode($datos, true);
  if($datosParseados["errorId"]==salidaExitosa){
    echo "Prueba1 OK </br>";
  }
  else{
    echo "Prueba1 FAIL </br>";
  }

//PRUEBA2: intento dar de alta la catedra recien insertada
  $datos= doABMCatedras(["apiVer" => apiVersionActual, "idSesion" => "1", "operacion" => "alta", "idMateria" => "1", "catedra" => "Mendez", "titularDeCatedra" => "Jose Mendez", "horasCatedra" => "10", "idCatedra" => ""]);
  $datosParseados= json_decode($datos, true);
  if($datosParseados["errorId"]==catedraDuplicada){
    echo "Prueba2 OK </br>";
  }
  else{
    echo "Prueba2 FAIL </br>";
  }

  //PRUEBA 3: doy de alta una catedra que figura como dada de baja
  $datos= doABMCatedras(["apiVer" => apiVersionActual, "idSesion" => "1", "operacion" => "alta","idMateria" => "1", "catedra" => "Marmol", "titularDeCatedra" => "Jose Marmol", "horasCatedra" => "10", "idCatedra" => ""]);
  $datosParseados= json_decode($datos, true);
  if($datosParseados["errorId"]==salidaExitosa){
    echo "Prueba3 OK </br>";
  }
  else{
    echo "Prueba3 FAIL </br>";
  }
  
}
mysqli_close($conexion);

?>








