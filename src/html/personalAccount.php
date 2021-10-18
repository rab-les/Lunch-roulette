<?php
	session_start();
	ob_start();
	ob_end_flush();

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
<html lang="it">
<head>
  <title>Home Utente</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="../css/personalAccount.css" rel="stylesheet" type="text/css"/>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>

  <?php $nickname = $_SESSION['username']; ?>

  <div class="presentation">

		<script>
			$("document").ready(function(){
				$("#newRestaurant").click(function(){
					$("#form_container").show();
				});
				$("#hide_form").click(function(){
					$("#form_container").hide();
				});
				$("#post_res_data").click(function(){
					$("#newRestaurant").prop("disabled", true);
					$("#newRestaurant").unbind('mouseenter mouseleave');
					$("#greetings").hide();
					$("#form_container").hide();
					$("#text_container").show();
				});
			});
		</script>

		<div id="greetings">
			<h1>Benvenuto/a, <?php echo $nickname; ?><br>
			Cosa vorresti fare?<h1>
		</div>

		<div id="form_container">

		    <div id="proposal_actions">
					<button type="submit" id="post_res_data">Aggiungi</button>
						<form class="modal-content" id="proposta" method="post" autocomplete="off">
							<div id="restaurant_data">
								<label for="res_name">Nome ristorante</label>
								<input type="text" placeholder="Inserisci il nome del locale" name="res_name" id="res_name" required>

								<label for="res_address">Indirizzo</label>
								<input type="text" placeholder="Inserisci l'indirizzo del locale" name="res_address" id="res_address" required>
							</div>
						</form>
		      <button type="reset" id="hide_form">Torna indietro</button>
		    </div>

		</div>

		<div id="text_container">

			<h3>Complimenti!<br>
			Il ristorante da te scelto è stato<br>
			aggiunto alla lista dei locali di<br>
			Lunch Roulette!</h3>
			<h5>Potrai aggiungerne un altro<br>
			alla tua prossima visita</h5>

		</div>

    <button type="button" id="newRestaurant">Proponi locale</button>
    <button type="button" id="signout" onclick="location.href='./signout.php'">Esci dal servizio</button>
  </div>

<?php
		if (isset($_REQUEST["res_name"]) && isset($_REQUEST["res_address"])) {

			$name = $_REQUEST["res_name"];
			$address = $_REQUEST["res_address"];

			$sql1 = "SELECT *
				FROM ristorante
				WHERE nomeRistorante = '$name'";
			$del = $dbh->prepare($sql1);
			$del->execute();

			if($del->rowCount() === 0) {
				$proposer = $_SESSION["username"];
				$sql2 = "INSERT INTO ristorante(nomeRistorante, indirizzo, username)
					VALUES ('$name', '$address', '$proposer')";
				$del = $dbh->prepare($sql2);
				$del->execute();
			} else {
				$message = "Questo ristorante è già presente nel sistema di Lunch Roulette.";
				echo "<script>alert('$message');</script>";
			}
		}
	?>

	</body>
	</html>
