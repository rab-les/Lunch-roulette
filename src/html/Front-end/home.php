<?php
require '../connection_vars.php';
$charset = "utf8mb4"; //crea il db con collazione utf8mb4_bin

try {
	$dsn = "mysql:host=$host;charset=$charset";
  $options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
  ];
	$dbh = new PDO($dsn, $user, $password);

  $stmt = $dbh->query("select count(*) from INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$db'");
  $test = (bool) $stmt->fetchColumn();
  if (!$test) {
    include_once '../generator_queries.php';
  }
} catch (\PDOException $e) {
	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Lunch Roulette service homepage</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="../../css/homepage.css" rel="stylesheet" type="text/css"/>
</head>
<body>

  <div class="functional">
    <h1>Benvenuto<br>
    in Lunch Roulette!</h1>
    <button type="button" id="signup" onclick="location.href='./signup.php'">Iscriviti</button>
    <button type="button" id="signin" onclick="location.href='./signin.php'">Accedi</button>
  </div>

</body>
</html>
