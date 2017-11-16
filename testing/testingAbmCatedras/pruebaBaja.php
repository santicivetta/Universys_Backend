<?php
define('TESTING', 'true');
include_once (__DIR__ . '/../funcionesTesting.php');
include_once (__DIR__ . '/../../funcionesGenerales.php');
include_once (__DIR__ . '/../../defines.php');
include_once ('../../ABMCatedras.php');

$eCon=null;
try{
$conexion = connect(True);
ejecutarSQL('baja.sql',$conexion);
} 
catch (Exception $eCon) {
    echo 'Excepción capturada: ',  $eCon->getMessage(), "\n";
}
if($eCon==null){
  echo ">> <strong>Baja Catedras</strong> </br>";

  //PRUEBA 1: intento modificar sin idCatedra
  $datos= doABMCatedras(["apiVer" => apiVersionActual, "idSesion" => "1", "operacion" => "baja", "idMateria" => "", "catedra" => "", "titularDeCatedra" => "", "horasCatedra" => "", "idCatedra" => ""]);
  $datosParseados= json_decode($datos, true);
  if($datosParseados["errorId"]==errorEnJson){
    echo "Prueba1 OK </br>";
  }
  else{
    echo "Prueba1 FAIL </br>";
  }

//PRUEBA2: intento modificar catedra inexistente
  $datos= doABMCatedras(["apiVer" => apiVersionActual, "idSesion" => "1", "operacion" => "baja", "idMateria" => "", "catedra" => "", "titularDeCatedra" => "", "horasCatedra" => "", "idCatedra" => "123"]);
  $datosParseados= json_decode($datos, true);
  if($datosParseados["errorId"]==catedraInexistente){
    echo "Prueba2 OK </br>";
  }
  else{
    echo "Prueba2 FAIL </br>";
  }

  //PRUEBA 3: doy de alta una catedra que figura como dada de baja
  $datos= doABMCatedras(["apiVer" => apiVersionActual, "idSesion" => "1", "operacion" => "baja", "idMateria" => "", "catedra" => "", "titularDeCatedra" => "", "horasCatedra" => "", "idCatedra" => "1"]);
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