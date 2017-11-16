<?php
define('TESTING', 'true');
include_once (__DIR__ . '/../funcionesTesting.php');
include_once (__DIR__ . '/../../funcionesGenerales.php');
include_once (__DIR__ . '/../../defines.php');
include_once ('../../ABMUsuarios.php');

$eCon=null;
try{
$conexion = connect(True);
ejecutarSQL('alta.sql',$conexion);
} 
catch (Exception $eCon) {
    echo 'Excepción capturada: ',  $eCon->getMessage(), "\n";
}
if($eCon==null){
  echo ">> <strong>Alta Materia</strong> </br>";

  //PRUEBA 1
  $datos= doABMUsuarios(["apiVer" => apiVersionActual, "idSesion" => "1", "operacion" => "alta", "nombre" => "Gaston", "apellido" => "Bodeman","documento" => "37213234", "fnac" => "1994-09-20","genero" => "Masculino","domicilio" => "Blanco Encalada 4892","telefono" => "45228786","identificador" => "3140","mail" => "gaston.bodeman@comunidad.ub.edu.ar","contraseña" => "contraseñagaston","rol" => "Alumno"]);
  $datosParseados= json_decode($datos, true);
  if($datosParseados["errorId"]=='200'){
    echo "Prueba1 OK </br>";
  }
  else{
    echo "Prueba1 FAIL </br>";
  }

  //PRUEBA 2
  $datos= doABMUsuarios(["apiVer" => apiVersionActual, "idSesion" => "1", "operacion" => "alta", "nombre" => "Gaston", "apellido" => "Bodeman","documento" => "37213234", "fnac" => "1994-09-20","genero" => "Masculino","domicilio" => "Blanco Encalada 4892","telefono" => "45228786","identificador" => "3140","mail" => "gaston.bodeman@comunidad.ub.edu.ar","contraseña" => "contraseñagaston","rol" => "Alumno"]);
  $datosParseados= json_decode($datos, true);
  if($datosParseados["errorId"]==usuarioDuplicado){
    echo "Prueba2 OK </br>";
  }
  else{
    echo "Prueba2 FAIL </br>";
  }

  //PRUEBA 3
  $datos= doABMUsuarios(["apiVer" => apiVersionActual, "idSesion" => "1", "operacion" => "alta", "nombre" => "Santi", "apellido" => "Cive","documento" => "12345678", "fnac" => "1994-09-10","genero" => "Masculino","domicilio" => "Cabildo 321","telefono" => "123456789","identificador" => "3141","mail" => "santiago.civetta@comunidad.ub.edu.ar","contraseña" => "contraseñanueva","rol" => "Alumno"]);
  $datosParseados= json_decode($datos, true);
  if($datosParseados["errorId"]=='200'){
    echo "Prueba3 OK </br>";
  }
  else{
    echo "Prueba3 FAIL </br>";
  }

  //PRUEBA 4
  $datos= doABMUsuarios(["apiVer" => apiVersionActual, "idSesion" => "2", "operacion" => "alta", "nombre" => "Santi", "apellido" => "Cive","documento" => "12345678", "fnac" => "1994-09-10","genero" => "Masculino","domicilio" => "Cabildo 321","telefono" => "123456789","identificador" => "3141","mail" => "santiago.civetta@comunidad.ub.edu.ar","contraseña" => "contraseñanueva","rol" => "Alumno"]);
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








