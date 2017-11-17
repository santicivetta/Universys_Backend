<?php
define('TESTING', 'true');
include_once (__DIR__ . '/../funcionesTesting.php');
include_once (__DIR__ . '/../../funcionesGenerales.php');
include_once (__DIR__ . '/../../defines.php');
include_once ('../../ABMUsuarios.php');

$eCon=null;
try{
$conexion = connect(True);
ejecutarSQL('modificacion.sql',$conexion);
} 
catch (Exception $eCon) {
    echo 'Excepción capturada: ',  $eCon->getMessage(), "\n";
}
if($eCon==null){
  echo ">> <strong>Modificacion Usuario</strong> </br>";

  //PRUEBA 1
  $datos= doABMUsuarios(["apiVer" => apiVersionActual, "idSesion" => "1", "operacion" => "modificacion", "nombre" => "Gaston", "apellido" => "Bodeman","documento" => "37213234", "fnac" => "1994-09-20","genero" => "Masculino","domicilio" => "Blanco Encalada 4892","telefono" => "45228786","mail" => "gaston.bodeman@comunidad.ub.edu.ar","contraseña" => "contraseñagaston"]);
  $datosParseados= json_decode($datos, true);
  if($datosParseados["errorId"]==usuarioInexistente){
    echo "Prueba1 OK </br>";
  }
  else{
    echo "Prueba1 FAIL </br>";
  }

  //PRUEBA 2
  $datos= doABMUsuarios(["apiVer" => apiVersionActual, "idSesion" => "1", "operacion" => "modificacion", "nombre" => "Santi", "apellido" => "Cive","documento" => "12345678", "fnac" => "1994-09-10","genero" => "Masculino","domicilio" => "Cabildo 321","telefono" => "123456789","mail" => "santiago.civetta@comunidad.ub.edu.ar","contraseña" => "contraseñanueva"]);
  $datosParseados= json_decode($datos, true);
  if($datosParseados["errorId"]==salidaExitosa){
    echo "Prueba2 OK </br>";
  }
  else{
    echo "Prueba2 FAIL </br>";
  }

  //PRUEBA 3
  $datos= doABMUsuarios(["apiVer" => apiVersionActual, "idSesion" => "1", "operacion" => "modificacion", "nombre" => "Andi", "apellido" => "Didi","documento" => "123", "fnac" => "2004-09-10","genero" => "Indefinido","domicilio" => "Pampa y la via","telefono" => "987654321","mail" => "andres.didier@comunidad.ub.edu.ar","contraseña" => "contraseñanueva"]);
  $datosParseados= json_decode($datos, true);
  if($datosParseados["errorId"]==salidaExitosa){
    echo "Prueba3 OK </br>";
  }
  else{
    echo "Prueba3 FAIL </br>";
  }

  //PRUEBA 4
  $datos= doABMUsuarios(["apiVer" => apiVersionActual, "idSesion" => "2", "operacion" => "modificacion", "nombre" => "Andi", "apellido" => "Didi","documento" => "123", "fnac" => "2004-09-10","genero" => "Indefinido","domicilio" => "Pampa y la via","telefono" => "987654321","mail" => "andres.didier@comunidad.ub.edu.ar","contraseña" => "contraseñanueva"]);
  $datosParseados= json_decode($datos, true);
  if($datosParseados["errorId"]==permisosErroneos){
    echo "Prueba4 OK </br>";
  }
  else{
    echo "Prueba4 FAIL </br>";
  }
  
}
mysqli_close($conexion);

?>