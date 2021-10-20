<?php
	session_start();
	ob_start();
	ob_end_flush();
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <title>Home Utente</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="../css/personalAccount.css" rel="stylesheet" type="text/css"/>
</head>
<body>

  <?php $nickname = $_SESSION['username']; ?>

  <div class="presentation">

		<div id="greetings">
			<h1>Benvenuto/a, <?php echo $nickname; ?><br>
			Cosa vorresti fare?<h1>
		</div>

    <button type="button" id="newRestaurant" onclick="location.href='./registration.php'">Proponi locale</button>
    <button type="button" id="signout" onclick="location.href='./signout.php'">Esci dal servizio</button>
  </div>

</body>
</html>
