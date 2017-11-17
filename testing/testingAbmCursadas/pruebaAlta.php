<?php
define('TESTING', 'true');
include_once (__DIR__ . '/../funcionesTesting.php');
include_once (__DIR__ . '/../../funcionesGenerales.php');
include_once (__DIR__ . '/../../defines.php');
include_once ('../../ABMCursadas.php');

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

/*
{
ABM  “operacion”: “”,
BM  “idCursada”: “”,
A  “idCatedra”: “”,
AM  “cuatrimestre”: “”,
AM  “año”: “”,
AM  “horario”: “<codigo hexa>”,
AM  “fechaParcial”:”dd-mm-aaaa”,
AM  “fechaRecuperatorio1”:”dd-mm-aaaa”,
AM  “fechaRecupeartorio2”:”dd-mm-aaaa”
}
*/

$eCon=null;
try{
$conexion = connect(True);
ejecutarSQL('alta.sql',$conexion);
} 
catch (Exception $eCon) {
    echo 'Excepción capturada: ',  $eCon->getMessage(), "\n";
}
if($eCon==null){
  setTimezone(date_default_timezone_get());
  echo ">> <strong>Alta Cursadas</strong> </br>";

  //PRUEBA 1: doy de alta una catedra inexistente
  $datos= doABMCursadas(["apiVer" => apiVersionActual, "idSesion" => "1", "operacion" => "alta", "idCursada" => "", "idCatedra" => "1", "cuatrimestre" => "segundo", "año" => "2017", "horario" => "abc123", "fechaParcial" => "12122017", "fechaRecuperatorio1" => "12122017", "fechaRecuperatorio2" => "12122017"]);
  $datosParseados= json_decode($datos, true);
  if($datosParseados["errorId"]==salidaExitosa){
    echo "Prueba1 OK </br>";
  }
  else{
    echo "Prueba1 FAIL </br>";
  }

//PRUEBA2: intento dar de alta la catedra recien insertada
 $datos= doABMCursadas(["apiVer" => apiVersionActual, "idSesion" => "1", "operacion" => "alta", "idCursada" => "", "idCatedra" => "1", "cuatrimestre" => "segundo", "año" => "2017", "horario" => "abc123", "fechaParcial" => "12122017", "fechaRecuperatorio1" => "12122017", "fechaRecuperatorio2" => "12122017"]);
  $datosParseados= json_decode($datos, true);
  if($datosParseados["errorId"]==cursadaDuplicada){
    echo "Prueba2 OK </br>";
  }
  else{
    echo "Prueba2 FAIL </br>";
  }

  //PRUEBA 3: doy de alta una catedra que figura como dada de baja
 $datos= doABMCursadas(["apiVer" => apiVersionActual, "idSesion" => "1", "operacion" => "alta", "idCursada" => "", "idCatedra" => "1", "cuatrimestre" => "primer", "año" => "2017", "horario" => "abc123", "fechaParcial" => "12122017", "fechaRecuperatorio1" => "12122017", "fechaRecuperatorio2" => "12122017"]);
  $datosParseados= json_decode($datos, true);
  if($datosParseados["errorId"]==salidaExitosa){
    echo "Prueba3 OK </br>";
  }
  else{
    echo "Prueba3 FAIL </br>";
    var_dump($datosParseados);
  }
  
}
mysqli_close($conexion);

?>








