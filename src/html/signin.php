<?php
require 'connection_vars.php';

try {
	$dsn = "mysql:host=$host;dbname=$db";
	$dbh = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
 	<link href="../css/login.css" rel="stylesheet" type="text/css"/>
 	<script src="../js/login_utilities.js" type="text/javascript"></script>
</head>
<body>

<div id="log_form" class="login">

  <form id="accesso" method="post">
    <div class="container">
      <label for="uname">Nickname</label>
      <input type="text" placeholder="Inserisci il tuo nickname" name="uname" id="uname" required>

      <label for="pwd">Password</label>
      <input type="password" placeholder="Inserisci la password" name="pwd" id="pwd" required>

			<div id="login_actions">
      	<button type="submit" id="accedi">Accedi</button>
				<button type="reset" onclick="window.location.href='home.php'" id="quit1">Annulla</button>
			</div>
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
