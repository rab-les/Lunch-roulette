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

  <form class="modal-content animate" id="registrazione" method="post">
    <div id="registration_data">
			<label for="new_mail"><b>E-mail</b></label>
			<input type="email" placeholder="Inserisci la tua e-mail lavorativa" name="new_mail" id="new_mail" required>

			<label for="new_uname"><b>Nickname</b></label>
			<input type="text" placeholder="Scegli il tuo nickname" name="new_uname" id="new_uname" required>

			<label for="new_pwd"><b>Password</b></label>
			<input type="password" placeholder="Inserisci la password" name="new_pwd" id="new_pwd" required>

			<label for="confirm_new_pwd"><b>Conferma password</b></label>
			<input type="password" placeholder="Conferma la password" name="confirm_new_pwd" id="confirm_new_pwd" required>
    </div>

    <div id="registration_actions">
			<button type="submit" id="registrati">Registrati</button>
      <button type="reset" onclick="window.location.href='home.php'" id="quit2">Annulla</button>
    </div>
  </form>
</div>

<?php
		if (isset($_REQUEST["new_mail"]) && isset($_REQUEST["new_uname"])
			&& isset($_REQUEST["new_pwd"]) && isset($_REQUEST["confirm_new_pwd"])) {

			if ($_REQUEST["new_pwd"] === $_REQUEST["confirm_new_pwd"]) {
				$username = $_REQUEST["new_uname"];
				$email = $_REQUEST["new_mail"];

				$sql1 = "SELECT *
					FROM utente
					WHERE username = '$username'
					OR email = '$email'";
				$result = $conn->query($sql1) or trigger_error($conn->error."[$sql1]");

				if($result->num_rows === 0) {
					$salt = substr(md5(microtime()),rand(0,26),32);
					$password = $_REQUEST["new_pwd"].$salt;
					$password = sha1($password);

					$sql2 = "INSERT INTO utente(username, email, password, salt)
						VALUES ('$username', '$email', '$password', '$salt')";
					$conn->query($sql2) or trigger_error($conn->error."[$sql2]");
					$conn->close();
					header('Location: ./signup.php');
				} else {
					$message = "Esiste già un utente con stesso username o email.";
					echo "<script>alert('$message');</script>";
				}
			} else {
				$message = "Non hai confermato correttamente la tua password.";
				echo "<script>alert('$message');</script>";
			}
		}
	?>

</body>
</html>
