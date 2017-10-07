<?php  

function isValidJSON($str) {
	json_decode($str);
	return json_last_error() == JSON_ERROR_NONE;
}

$connection = new mysqli('universys.site', 'apholos_dba', 'dbainub', 'apholos_ligaub');


if (mysqli_connect_errno()) {
	die("Database connection failed: " .
		mysqli_connect_error() .
		" (" . mysqli_connect_errno() . ")"
	);
}

$json_params = file_get_contents("php://input");

if (strlen($json_params) > 0 && isValidJSON($json_params)){

	$decoded_json = json_decode($json_params);
	$mail = $decoded_json["mail"];
	$password = $decoded_json["password"];

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
$mysqli = new mysqli("localhost", "my_user", "my_password", "world");

/* check connection */
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

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