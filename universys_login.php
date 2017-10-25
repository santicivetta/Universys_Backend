

-----------------Login:
Request de Login:
Dirección URL del Servidor: http://universys.site/login
Ejemplo de JSON:
{
	“apiVer” : ”1.0”,
	“idSesion” : “32695”
	“mail” : ”pepito@gmail.com”,
	“password” : “pepitokpo” 
}

Respuesta del Login:
Ejemplo de JSON:
{
	“direccionServidor” : “x”
	“apiVer” : ”1.0”,
	“errorId” : “404”,
	“usuario” : { 
	“nombre” : ”Diego”,
	“apellido” : “Maradona”,
	“fNac” : “3/8/01”,
	“tipo” : “alumno”
}
}

Codigo de error: 200:”Página funcionando correctamente”.
Codigo de error: 680:”Usuario o mail incorrecto”
Codigo de error: 777:”Contraseña incorrecta”
Codigo de error: 799:”Error: Sesión duplicada”
Codigo de error: 800:”Error: Unexpected error”
Codigo de error: 801:”Invalid JSON request”
Codigo de error: 802:”Unable to connect to database”

<?php  

function traerJson(){
	
	//traigo request
	$json_params = file_get_contents("php://input");

	//decodifico
	$result = json_decode($json_params);

    //si no hay errores lo devuelvo
	if($result) {
		return $result;
	}

	throw new Exception('800');
}

function encode($value, $options = 0) {
	$result = json_encode($value, $options);

	if($result)  {
		return $result;
	}

	throw new Exception('800');

}

function connect(){
	$connection = new mysqli('universys.site', 'apholos_dba', 'dbainub', 'apholos_universys');

	if ($connection->connect_errno) {
		throw new Exception('802');
	}

	return $connection;
}

function chequeoVersion($conexion, $versionAChequear){

	$result = $conexion->query("SELECT version FROM api_version WHERE fecha_hasta IS NULL");

	if ($conexion->connect_errno) {
		throw new Exception('802');
	}

	if ($result->num_rows == 1) {
		$row = $result->fetch_array(MYSQLI_ASSOC);
		return ($row["version"] == $versionAChequear);
	} else {
		throw new Exception('1500');
	}

}

function validoCredenciales($conexion, $usuario, $contraseña){
	
	
	$query = "SELECT * FROM Usuarios WHERE usuario = '" . $usuario . "' and contraseña = '" . $contraseña ."'";
	
	$result = $conexion->query($query);

	if ($conexion->connect_errno) {
		throw new Exception('802');
	}
	var_dump($result);
	if ($result->num_rows == 1) {
		$row = $result->fetch_array(MYSQLI_ASSOC);
		var_dump($row);
		return $row["usuario"];
	} else {
		throw new Exception('680');
	}

}

function traigoDatos($conexion, $idUsuario){
	$result = $conexion->query("SELECT * FROM Usuarios where usuario='" . $idUsuario."'");

	if ($conexion->connect_errno) {
		throw new Exception('802');
	}

	while($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$myArray[] = $row;
	}

	return $myArray;

}

function armarSalida($misDatos, $codigoSalida){

}


try {
	
	//recibo json
	$miJson = traerJson();

	//chequeo id sesion
	if (isset($miJson["idSesion"])) {
		throw new Exception("799");	
	}

	//conecto a la base
	$conexion = connect();

	//chequeo version
	chequeoVersion($conexion, $miJson["apiVer"]);

	//valido credenciales
	$idUsuario = validoCredenciales($conexion, $miJson["mail"], md5($miJson["password"]);

	$datosUsuario = traigoDatos($conexion, $idUsuario);

	$arraySalida = armarSalida($datosUsuario, "200");

	$mysqli->close();

	echo json_encode($arraySalida);

} catch (Exception $e) {

	$arraySalida = armarSalida(null, $e->getMessage());

	$mysqli->close();

	echo json_encode($arraySalida);

}

?>