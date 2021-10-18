<?php
session_start();
set_time_limit(60);

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
<title>Accedi a Lunch Roulette</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
 	<link href="../css/login.css" rel="stylesheet" type="text/css"/>
 	<script src="../js/login_utilities.js" type="text/javascript"></script>
</head>
<body>

<div id="log_form" class="login">

  <form class="modal-content" id="accesso" method="post" autocomplete="off">
    <div class="container">
      <label for="uname">Nickname</label>
      <input type="text" placeholder="Inserisci il tuo nickname" name="uname" id="uname" required>

      <label for="pwd">Password</label>
      <input type="password" placeholder="Inserisci la password" name="pwd" id="pwd" required>

			<label for="check">
	    	<input type="checkbox" checked="checked" id="check">Remember me
	    </label>

			<div id="login_actions">
      	<button type="submit" id="accedi">Accedi</button>
				<button type="reset" onclick="window.location.href='home.php'" id="quit1">Annulla</button>
			</div>
    </div>
  </form>

</div>

<?php
		if (isset($_REQUEST["uname"]) && isset($_REQUEST["pwd"])) {
			$uname = $_REQUEST["uname"];
			$pwd = $_REQUEST["pwd"];
			$sql = "SELECT username, pwd
				FROM utente
				WHERE username = '$uname'";

			$del = $dbh->prepare($sql);
			$del->execute();
			$row = $del->fetch(PDO::FETCH_ASSOC);

			if ($row["username"] === $uname && password_verify($pwd, $row["pwd"])) {
				$_SESSION["username"] = $uname;
				$_SESSION["password"] = $row["pwd"];
				if (isset($_REQUEST["rememberme"])) {
					$_SESSION["rememberme"] = true;
				} else {
					$_SESSION["rememberme"] = false;
				}
				header('Location: ./personalAccount.php');
				exit();
			} else {
				$message = "Non hai inserito correttamente i tuoi dati.";
				echo "<script>alert('$message');</script>";
			}
		}
	?>

</body>
</html>
