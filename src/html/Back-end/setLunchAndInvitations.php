<?php
  session_start();
  ob_start();
	ob_end_flush();

  include 'dotEnv.php';
  include 'getDateTime.php';
  include 'ICS.php';
  require '../connection_vars.php';
  require_once '../../../../../composer/vendor/autoload.php';

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  try {
  	$dsn = "mysql:host=$host;dbname=$db";
  	$dbh = new PDO($dsn, $user, $password);
  } catch (\PDOException $e) {
  	print "Error!: " . $e->getMessage() . "<br/>";
  	die();
  }

  (new DotEnv('../../../doc/variables.env'))->load();

  $u_days = getenv('GIORNI');
  $hour = getenv('ORARIO');
  $invitations = getenv('NUMERO');

  $separators = substr_count($u_days, '/');
  if ($separators > 0) {
    $days = explode('/', $u_days, $separators + 1);
    /*Rimescolo i valori dell'array con shuffle() ed estraggo il primo elemento
      con array_pop(), ottenendo un giorno a caso tra quelli a disposizione*/
    shuffle($days);
    $lunch_day = array_pop($days);
  } else {
    $lunch_day = $u_days;
  }
  $separators = substr_count($hour, '-');
  if ($separators == 1) {
    $hours = explode('-', $hour, $separators + 1);
  } else {
    echo "Error! La variabile d'ambiente ORARIO non è impostata correttamente.<br>
      Formato corretto: hh:mm-hh:mm";
    die();
  }

  $dateStart = getDateTimeString($lunch_day, $hours[0]);
  $dateEnd = getDateTimeString($lunch_day, $hours[1]);
  /*Seleziono un ristorante a caso dalla relativa tabella*/
  $sql1 = "SELECT nomeRistorante, indirizzo
    FROM ristorante
    ORDER BY rand()
    LIMIT 1";
  $del = $dbh->prepare($sql1);
  $del->execute();
  $columns = $del->fetch(PDO::FETCH_ASSOC);
  if ($columns && isset($columns["nomeRistorante"]) && isset($columns["indirizzo"])) {
    $placename = $columns["nomeRistorante"];
    $placedata = $columns["nomeRistorante"] . ", " . $columns["indirizzo"];
  } else {
    echo "Error! Non ci sono ristoranti registrati nel database dell'applicazione.";
    die();
  }
  /*Ora che ho i dati necessari, inizializzo il file .ics da spedire tramite
    messaggio di posta elettronica agli invitati*/
  $ics = new ICS(array(
    'location' => $placedata,
    'description' => "Scegli se partecipare o meno",
    'dtstart' => $dateStart,
    'dtend' => $dateEnd,
    'summary' => "Pranzo con i colleghi organizzato da Lunch Roulette",
    'url' => "http://lunchroulette.com"
  ));
  /*Per precauzione, controllo che il numero delle righe nella tabella UTENTE
    non sia minore del numero di inviti che devo inviare*/
  $sql2 = "SELECT COUNT(*) FROM utente";
  $del = $dbh->prepare($sql2);
  $del->execute();
  $numRows = $del->fetchColumn();

  if ($numRows >= $invitations) {
    /*Non inserisco un codice identificativo perché la colonna della tabella
      è dotata della proprietà auto_increment che ci pensa al posto mio*/
    $sql3 = "INSERT INTO pranzo(nomeRistorante, numPartecipanti, giorno, orario)
      VALUES ('$placename', '$invitations', '$lunch_day', '$hour')";
    $del = $dbh->prepare($sql3);
    $del->execute();
    /*Per proseguire devo però conoscere l'id del pranzo appena creato e per
      farlo utilizzo una funzione di mySQL che mi permette di eseguire la query
      in una sola linea di codice*/
    $sql4 = "SELECT LAST_INSERT_ID()";
    $del = $dbh->prepare($sql4);
    $del->execute();
    $array_fetched = $del->fetch(PDO::FETCH_ASSOC);
    $id_lunch = array_shift($array_fetched);
    /*Seleziono i nickname di tutti i membri registrati nella tabella UTENTE,
      rimescolo l'array risultante e conservo solo un numero di valori pari
      alla quantità di inviti che devo spedire*/
    $sql5 = "SELECT username, email
      FROM utente
      ORDER BY RAND()
      LIMIT ?";
    $del = $dbh->prepare($sql5);
    $del->bindValue(1, intval($invitations), PDO::PARAM_INT);
    $del->execute();
    $guests = $del->fetchAll(PDO::FETCH_KEY_PAIR);
    /*Preparo la query per inserire gli inviti nel db fuori dal ciclo, tenendo
      come "incognita" solo il nickname dell'utente.*/
    $sql6 = "INSERT INTO invito(id_pranzo, nomeRistorante, username)
      VALUES ('$id_lunch', '$placename', ?)";

    foreach ($guests as $nick => $address) {
      $del = $dbh->prepare($sql6);
      $del->bindValue(1, $nick, PDO::PARAM_STR);
      $del->execute();

      /*Inizializzo il messaggio di posta elettronica di cui invierò una copia a
        tutti gli invitati al pranzo*/
      $mail = new PHPMailer;
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->Port = 587;
      $mail->SMTPSecure = 'tls';
      $mail->SMTPAuth = true;

      $mail->Username = "noreply.lunchroulette@gmail.com";
      $mail->Password = "504o7Z7@";

      $mail->setFrom('no-reply@lunchroulette.com', 'Lunch Roulette Service');
      $mail->isHTML(true);

      $ical = $ics->to_string();
      $mail->addStringAttachment("$ical", "invito.ics", "base64", "text/calendar;
        charset=utf-8; method=REQUEST");

      $mail->Subject = "Invito a pranzo - Lunch Roulette";
      $mail->addAddress($address, $nick);
      $mail->Body = "<b>" . $nick . "</b>, hai ricevuto un invito a pranzo!";
      $mail->AltBody = "Hai ricevuto un invito a pranzo!";

      try {
        $mail->send();
        echo "Il messaggio è stato inviato correttamente.<br>";
      } catch (Exception $e) {
        echo "Mailer error: " . $mail->ErrorInfo;
        die();
      }
    }
  } else {
    print "Errore!: La ricerca di un ristorante non ha restituito nomi validi
      e/o non ci sono abbastanza iscritti al servizio per organizzare un pranzo.";
  	die();
  }

	session_destroy();
	exit();
?>
