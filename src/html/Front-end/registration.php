<?php
	session_start();

	require '../connection_vars.php';

  try {
  	$dsn = "mysql:host=$host;dbname=$db";
  	$dbh = new PDO($dsn, $user, $password);
  } catch (PDOException $e) {
  	print "Error!: " . $e->getMessage() . "<br/>";
  	die();
  }

  $nickname = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <title>Nuovo locale</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="../../css/personalAccount.css" rel="stylesheet" type="text/css"/>
</head>
<body>

  <div class="presentation">
    <p class="form_title">Dati del locale<p>
    <form class="modal-content" id="proposta" method="post" autocomplete="off">
      <div id="restaurant_data">
        <label for="res_name">Denominazione</label>
        <input type="text" placeholder="Inserisci il nome del locale" name="res_name" id="res_name" required>

        <label for="res_address">Indirizzo</label>
        <input type="text" placeholder="Inserisci l'indirizzo del locale" name="res_address" id="res_address" required>
      </div>
      <div id="proposal_actions">
      	<button type="submit" id="post_res_data">Aggiungi</button>
	    	<button type="reset" id="back_to_home" onclick="location.href='./personalAccount.php'">Torna indietro</button>
    	</div>
  	</form>
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
        $sql2 = "INSERT INTO ristorante(nomeRistorante, indirizzo, username)
          VALUES ('$name', '$address', '$nickname')";
        $del = $dbh->prepare($sql2);
        $del->execute();

        $_SESSION["res_name"] = $name;
        $_SESSION["res_address"] = $address;
        header('Location: ./summary.php');
      } else {
        $message = "Questo ristorante è già presente nel sistema di Lunch Roulette.";
        echo "<script>alert('$message');</script>";
      }
    }
  ?>

</body>
</html>
