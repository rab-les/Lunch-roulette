<?php
$servername = "localhost";
$username = "root";
$password = "654321";
$database = "lunch_roulette";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
	die("Connection failed: " .$conn->connect_error);
}

session_start();
set_time_limit(60);
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
 	<link href="../css/login.css" rel="stylesheet" type="text/css"/>
 	<script src="../js/login_utilities.js" type="text/javascript"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>

<div id="log_form" class="modal">

  <form class="modal-content animate" id="accesso" method="post">
    <div class="container">
      <label for="uname"><b>Nickname</b></label>
      <input type="text" placeholder="Inserisci il tuo nickname" name="uname" id="uname" required>

      <label for="pwd"><b>Password</b></label>
      <input type="password" placeholder="Inserisci la password" name="pwd" id="pwd" required>

			<div id="login_actions">
      	<button type="submit" id="accedi">Accedi</button>
				<button type="reset" onclick="window.location.href='home.php'" id="quit1">Annulla</button>
    </div>
  </form>

</div>

<?php
		if (isset($_REQUEST["uname"]) && isset($_REQUEST["pwd"])) {
			$user = $_REQUEST["uname"];
			$sql = "SELECT username, password, salt
				FROM utente
				WHERE username = '$user'";
			$result = $conn->query($sql) or trigger_error($conn->error."[$sql]");
			$conn->close();
			$row = $result->fetch_assoc();
			$pwd = $_REQUEST["pwd"].$row["salt"];
			$pwd = substr(sha1($pwd),0,32);
			if ($row["username"] === $user && $row["password"] === $pwd) {
				$_SESSION["username"] = $user;
				$_SESSION["password"] = $row["password"];
				if (isset($_REQUEST['rememberme'])) {
					$_SESSION["rememberme"] = true;
				} else {
					$_SESSION["rememberme"] = false;
				}
			} else {
				$message = "Non hai inserito correttamente i tuoi dati.";
				echo "<script>alert('$message');</script>";
			}
		}
	?>

</body>
</html>
