<?php
define('TESTING', 'true');
include_once (__DIR__ . '/../funcionesTesting.php');
include_once (__DIR__ . '/../../funcionesGenerales.php');
include_once (__DIR__ . '/../../defines.php');
include_once ('../../ABMCarreras.php');

$eCon=null;
try{
$conexion = connect(True);
ejecutarSQL('alta.sql',$conexion);
} 
catch (Exception $eCon) {
    echo 'Excepción capturada: ',  $eCon->getMessage(), "\n";
}
if($eCon==null){
  echo ">> <strong>Alta Carreras</strong> </br>";

  //PRUEBA 1
  $datos= doABMCarreras(["apiVer" => apiVersionActual, "idSesion" => "1", "operacion" => "alta", "carrera" => "Tecnicatura en RRHH"]);
  $datosParseados= json_decode($datos, true);
  if($datosParseados["errorId"]=='200'){
    echo "Prueba1 OK </br>";
  }
  else{
    echo "Prueba1 FAIL </br>";
  }

  //PRUEBA 2
  $datos= doABMCarreras(["apiVer" => apiVersionActual, "idSesion" => "1", "operacion" => "alta", "carrera" => "Tecnicatura en Programacion"]);
  $datosParseados= json_decode($datos, true);
  if($datosParseados["errorId"]=='200'){
    echo "Prueba2 OK </br>";
  }
  else{
    echo "Prueba2 FAIL </br>";
  }

  //PRUEBA 3
  $datos= doABMCarreras(["apiVer" => apiVersionActual, "idSesion" => "1", "operacion" => "alta", "carrera" => "Tecnicatura en Programacion"]);
  $datosParseados= json_decode($datos, true);
  if($datosParseados["errorId"]==carreraDuplicada){
    echo "Prueba3 OK </br>";
  }
  else{
    echo "Prueba3 FAIL </br>";
  }
  
}
mysqli_close($conexion);

?>








