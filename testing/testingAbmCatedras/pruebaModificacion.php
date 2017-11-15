<?php
define('TESTING', 'true');
include_once (__DIR__ . '/../funcionesTesting.php');
include_once (__DIR__ . '/../../funcionesGenerales.php');
include_once (__DIR__ . '/../../defines.php');
include_once ('../../ABMCatedras.php');

function setTimezone($default) {
    $timezone = "";
    
    // On many systems (Mac, for instance) "/etc/localtime" is a symlink
    // to the file with the timezone info
    if (is_link("/etc/localtime")) {
        
        // If it is, that file's name is actually the "Olsen" format timezone
        $filename = readlink("/etc/localtime");
        
        $pos = strpos($filename, "zoneinfo");
        if ($pos) {
            // When it is, it's in the "/usr/share/zoneinfo/" folder
            $timezone = substr($filename, $pos + strlen("zoneinfo/"));
        } else {
            // If not, bail
            $timezone = $default;
        }
    }
    else {
        // On other systems, like Ubuntu, there's file with the Olsen time
        // right inside it.
        $timezone = file_get_contents("/etc/timezone");
        if (!strlen($timezone)) {
            $timezone = $default;
        }
    }
    date_default_timezone_set($timezone);
}

$eCon=null;
try{
$conexion = connect(True);
ejecutarSQL('modificacion.sql',$conexion);
} 
catch (Exception $eCon) {
    echo 'Excepción capturada: ',  $eCon->getMessage(), "\n";
}
if($eCon==null){
  setTimezone(date_default_timezone_get());
  echo ">> <strong>Modificacion Catedras</strong> </br>";

  //PRUEBA 1: intento modificar sin idCatedra
  $datos= doABMCatedras(["apiVer" => apiVersionActual, "idSesion" => "1", "operacion" => "modificacion", "idMateria" => "1", "catedra" => "Marmol", "titularDeCatedra" => "Jose Marmol", "horasCatedra" => "10", "idCatedra" => ""]);
  $datosParseados= json_decode($datos, true);
  if($datosParseados["errorId"]==errorEnJson){
    echo "Prueba1 OK </br>";
  }
  else{
    echo "Prueba1 FAIL </br>";
  }

//PRUEBA2: intento modificar catedra inexistente
  $datos= doABMCatedras(["apiVer" => apiVersionActual, "idSesion" => "1", "operacion" => "modificacion", "idMateria" => "1", "catedra" => "Mendez", "titularDeCatedra" => "Jose Mendez", "horasCatedra" => "10", "idCatedra" => "3"]);
  $datosParseados= json_decode($datos, true);
  if($datosParseados["errorId"]==catedraInexistente){
    echo "Prueba2 OK </br>";
  }
  else{
    echo "Prueba2 FAIL </br>";
  }

  //PRUEBA 3: doy de alta una catedra que figura como dada de baja
  $datos= doABMCatedras(["apiVer" => apiVersionActual, "idSesion" => "1", "operacion" => "modificacion","idMateria" => "1", "catedra" => "Marmol", "titularDeCatedra" => "Pedro Picapiedra", "horasCatedra" => "10", "idCatedra" => "1"]);
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