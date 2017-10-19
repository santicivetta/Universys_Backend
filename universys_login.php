//que hacemos con varios errores?

/*
id sesion
user agent
usuario
*/
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
				“fNac” : “3/8/01”
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

 public static function encode($value, $options = 0) {
	$result = json_encode($value, $options);

	if($result)  {
		return $result;
	}

	throw new Exception('800');;
	}

public static function decode($json, $assoc = false) {
    $result = json_decode($json, $assoc);

    if($result) {
        return $result;
    }

    throw new Exception('800');
}

public static function connect(){
	$connection = new mysqli('universys.site', 'apholos_dba', 'dbainub', 'apholos_ligaub');

	if ($mysqli->connect_errno) {
	    throw new Exception('802');
	}

	return $connection;
}

public static function chequeoVersion($connection, $versionAChequear){

}

public static function validoCredenciales($conexion, $mail, $password){
	$query = "SELECT * FROM usuarios WHERE mail='$mail' and password='$password'";
	$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
	$count = mysqli_num_rows($result);

}

public static function traigoDatos($conexion, $idUsuario){
	if ($result = $mysqli->query("SELECT * FROM phase1")) {

		while($row = $result->fetch_array(MYSQL_ASSOC)) {
			$myArray[] = $row;
		}
}



try {
	
	//recibo json
	$json_params = file_get_contents("php://input");

	//decodifico
	$miJson = decode($json_params);

	//chequeo id sesion
	if (isset($request["sesion"])) {
		throw new Exception("799");	
	}

	//chequeo user agent ver si sirve http://mobiledetect.net

	//conecto a la base
	$conexion = connect();

	//chequeo version
	chequeoVersion($conexion, $request["apiVer"]);

	//valido credenciales
	$idUsuario = validoCredenciales($conexion, $request["mail"], $request["password"]);

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