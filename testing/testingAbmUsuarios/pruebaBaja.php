<?php
define('TESTING', 'true');
include_once (__DIR__ . '/../funcionesTesting.php');
include_once (__DIR__ . '/../../funcionesGenerales.php');
include_once (__DIR__ . '/../../defines.php');
include_once ('../../ABMUsuarios.php');

$eCon=null;
try{
  $conexion = connect(True);
  ejecutarSQL('baja.sql',$conexion);
} 
catch (Exception $eCon) {
    echo 'Excepción capturada: ',  $eCon->getMessage(), "\n";
}

if($eCon==null){
  echo ">> <strong>Baja Usuario</strong> </br>";

  //PRUEBA 1
  $datos= doABMUsuarios(["apiVer" => apiVersionActual, "idSesion" => "1", "operacion" => "baja", "mail" => "gaston.bodeman@comunidad.ub.edu.ar"]);
  $datosParseados= json_decode($datos, true);
  if($datosParseados["errorId"]==usuarioInexistente){
    echo "Prueba1 OK </br>";
  }
  else{
    echo "Prueba1 FAIL </br>";
  }

  //PRUEBA 2
  $datos= doABMUsuarios(["apiVer" => apiVersionActual, "idSesion" => "1", "operacion" => "baja", "mail" => "andres.didier@comunidad.ub.edu.ar"]);
  $datosParseados= json_decode($datos, true);
  if($datosParseados["errorId"]==salidaExitosa){
    echo "Prueba2 OK </br>";
  }
  else{
    echo "Prueba2 FAIL </br>";
  }

  //PRUEBA 3
  $datos= doABMUsuarios(["apiVer" => apiVersionActual, "idSesion" => "2", "operacion" => "baja", "mail" => "sarasa@comunidad.ub.edu.ar"]);
  $datosParseados= json_decode($datos, true);
  if($datosParseados["errorId"]==permisosErroneos){
    echo "Prueba3 OK </br>";
  }
  else{
    echo "Prueba3 FAIL </br>";
  }

}
mysqli_close($conexion);

?>