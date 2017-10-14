
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







$json_params = file_get_contents("php://input");

$request = decode($json_params);

$mail = $request["mail"];
$password = $request["password"];

$query = "SELECT * FROM usuarios WHERE mail='$mail' and password='$password'";
$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
$count = mysqli_num_rows($result);

if ($count == 1){
	if ($result = $mysqli->query("SELECT * FROM phase1")) {

		while($row = $result->fetch_array(MYSQL_ASSOC)) {
			$myArray[] = $row;
		}
		echo json_encode($myArray);
	}
}else{

	echo "<script language='javascript'>
	alert('ERROR: Credenciales invalidas');
	window.location.href = 'mail.html';
	</script>";
}
}
?>

/*pasar todo lo de arriba a objetos*/
<?php



/* check connection */

/* Create table doesn't return a resultset */
if ($mysqli->query("CREATE TEMPORARY TABLE myCity LIKE City") === TRUE) {
    printf("Table myCity successfully created.\n");
}

/* Select queries return a resultset */
if ($result = $mysqli->query("SELECT Name FROM City LIMIT 10")) {
    printf("Select returned %d rows.\n", $result->num_rows);

    /* free result set */
    $result->close();
}

/* If we have to retrieve large amount of data we use MYSQLI_USE_RESULT */
if ($result = $mysqli->query("SELECT * FROM City", MYSQLI_USE_RESULT)) {

    /* Note, that we can't execute any functions which interact with the
       server until result set was closed. All calls will return an
       'out of sync' error */
    if (!$mysqli->query("SET @a:='this will not work'")) {
        printf("Error: %s\n", $mysqli->error);
    }
    $result->close();
}

$mysqli->close();
?>