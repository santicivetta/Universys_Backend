

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

try {

	if (!(isset($_POST))) {
		throw new Exception("800");	
	}

	//chequeo id sesion
	if (isset($_POST["idSesion"])) {
		throw new Exception("799");	
	}

	//conecto a la base
	$conexion = connect();

	//chequeo version
	chequeoVersion($conexion, $_POST["apiVer"]);

	//valido credenciales
	$idUsuario = validoCredenciales($conexion, $_POST["mail"], $_POST["password"]);

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