<?php
	session_start();

  $res_name = $_SESSION["res_name"];
  $res_address = $_SESSION["res_address"];
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <title>Ricapitolando...</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="../css/personalAccount.css" rel="stylesheet" type="text/css"/>
</head>
<body>

  <div class="presentation">

    <h2>Complimenti!<br>
    <?php echo $res_name; ?> <br>
    in <br>
    <?php echo $res_address; ?> <br>
    è stato aggiunto alla lista<br>
    dei locali di Lunch Roulette!</h2>

		<button type="button" id="return" onclick="location.href='./personalAccount.php'">Torna all'homepage</button>

  </div>

</body>
</html>
