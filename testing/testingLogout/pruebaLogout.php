<?php
define('TESTING', 'true');
include_once (__DIR__ . '/../funcionesTesting.php');
include_once (__DIR__ . '/../../funcionesGenerales.php');
include_once (__DIR__ . '/../../defines.php');
include_once ('../../logout.php');

$eCon=null;
try{
$conexion = connect(True);
ejecutarSQL('logout.sql',$conexion);
} 
catch (Exception $eCon) {
    echo 'Excepción capturada: ',  $eCon->getMessage(), "\n";
}
if($eCon==null){
  echo ">> <strong>Logout</strong> </br>";

  //PRUEBA 1
  $datos= doLogout(["apiVer" => apiVersionActual, "idSesion" => "1"]);
  $datosParseados= json_decode($datos, true);
  if($datosParseados["errorId"]==salidaExitosa){
    echo "Prueba1 OK </br>";
  }
  else{
    echo "Prueba1 FAIL </br>";
  }
  
  //PRUEBA 2
  $datos= doLogout(["apiVer" => apiVersionActual, "idSesion" => "10"]);
  $datosParseados= json_decode($datos, true);
  if($datosParseados["errorId"]==sesionInexistente){
    echo "Prueba2 OK </br>";
  }
  else{
    echo "Prueba2 FAIL </br>";
  }
}

mysqli_close($conexion);

?>