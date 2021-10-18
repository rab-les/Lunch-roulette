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
<title>Iscriviti a Lunch Roulette</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
 	<link href="../css/login.css" rel="stylesheet" type="text/css"/>
</head>
<body>

<div id="log_form" class="login">

  <form class="modal-content" id="registrazione" method="post" autocomplete="off">
    <div id="registration_data">
			<label for="new_mail">E-mail</label>
			<input type="email" placeholder="Inserisci la tua e-mail lavorativa" name="new_mail" id="new_mail" required>

			<label for="new_uname">Nickname</label>
			<input type="text" placeholder="Scegli il tuo nickname" name="new_uname" id="new_uname" required>

			<label for="new_pwd">Password</label>
			<input type="password" placeholder="Inserisci la password" name="new_pwd" id="new_pwd" required>

			<label for="confirm_new_pwd">Conferma password</label>
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
				$del = $dbh->prepare($sql1);
				$del->execute();

				if($del->rowCount() === 0) {
					$pwd = $_REQUEST["new_pwd"];
					$hashed_password = password_hash($pwd, PASSWORD_DEFAULT);

					$sql2 = "INSERT INTO utente(username, email, pwd)
						VALUES ('$username', '$email', '$hashed_password')";
					$del = $dbh->prepare($sql2);
					$del->execute();
					$_SESSION["username"] = $username;
					$_SESSION["password"] = $pwd;
					header('Location: ./personalAccount.php');
					exit();
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
