<?php

function ejecutarSQL($rutaArchivo, $conexion)
{
  $queries = explode(';', file_get_contents($rutaArchivo));
  foreach($queries as $query)
  {
    if($query != '')
    {
      	$conexion->query($query);
    }
  }
}

?>