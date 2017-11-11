<?php
define('TESTING', 'true');
include_once (__DIR__ . '/../funcionesTesting.php');
include_once (__DIR__ . '/../../funcionesGenerales.php');
include_once (__DIR__ . '/../../defines.php');
include_once ('../../universys_login.php');

$eCon=null;
try{
$conexion = connect(True);
ejecutarSQL('login.sql',$conexion);
} 
catch (Exception $eCon) {
    echo 'Excepción capturada: ',  $eCon->getMessage(), "\n";
}
if($eCon==null){
  echo ">> <strong>Login</strong> </br>";

  //PRUEBA 1
  $datos= doLogin(["apiVer" => apiVersionActual, "idSesion" => "", "mail" => "santiago.civetta@comunidad.ub.edu.ar", "password" => "contraseñasantiago"]);
  $datosParseados= json_decode($datos, true);
  if($datosParseados["errorId"]=='200' and $datosParseados["usuario"]["idSesion"]>'0'){
    echo "Prueba1 OK </br>";
  }
  else{
    echo "Prueba1 FAIL </br>";
  }
  
  //PRUEBA 2
  $datos= doLogin(["apiVer" => apiVersionActual, "idSesion" => "", "mail" => "mariano.martin@comunidad.ub.edu.ar", "password" => "contraseñasarasa"]);
  $datosParseados= json_decode($datos, true);
  if($datosParseados["errorId"]==credencialesIncorrectas){
    echo "Prueba2 OK </br>";
  }
  else{
    echo "Prueba2 FAIL </br>";
  }

  //PRUEBA 3
  $datos= doLogin(["apiVer" => apiVersionActual, "idSesion" => "", "mail" => "gaston.bodeman@comunidad.ub.edu.ar", "password" => "contraseñagaston"]);
  $datosParseados= json_decode($datos, true);
  if($datosParseados["errorId"]==personaInexistente){
    echo "Prueba3 OK </br>";
  }
  else{
    echo "Prueba3 FAIL </br>";
  }

  //PRUEBA 4
  $datos= doLogin(["apiVer" => apiVersionActual, "idSesion" => "", "mail" => "andres.didier@comunidad.ub.edu.ar", "password" => "contraseñaandres"]);
  $datosParseados= json_decode($datos, true);
  if($datosParseados["errorId"]=='200' and $datosParseados["usuario"]["idSesion"]>'0'){
    echo "Prueba4 OK </br>";
  }
  else{
    echo "Prueba4 FAIL </br>";
  }
}
mysqli_close($conexion);

?>








