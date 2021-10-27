<?php
  session_start();

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
  $days = explode($u_days, '/', $separators + 1);
  $hours = explode($hour, '-', 2);
  /*Rimescolo i valori dell'array con shuffle() ed estraggo il primo elemento
    con array_pop(), ottenendo un giorno a caso tra quelli a disposizione*/
  shuffle($days);
  $lunch_day = array_pop($days);
  $dateStart = getDateTimeString($lunch_day, $hours[0]);
  $dateEnd = getDateTimeString($lunch_day, $hours[1]);
  /*Seleziono un ristorante a caso dalla relativa tabella*/
  $sql1 = "SELECT nomeRistorante, indirizzo
    FROM ristorante
    ORDER BY rand()
    LIMIT 1";
  $del = $dbh->prepare($sql1);
  $del->execute();
  $placename = $del->fetchColumn();
  $address = $del->fetchColumn(1);
  $placedata = $placename . ", " . $address;
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
  $sql4 = "SELECT COUNT(*) FROM utente";
  $del = $dbh->prepare($sql2);
  $del->execute();
  $numRows = $del->fetchColumn();

  if (!empty($placedata) && $numRows >= $invitations) {
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
    $id_lunch = $del->fetch(PDO::FETCH_ASSOC);
    /*Seleziono nickname e indirizzi di un numero di invitati pari al valore
      della variabile d'ambiente pescando a caso nella tabella UTENTE*/
    $sql5 = "SELECT username, email
      FROM utente
      ORDER BY RAND()
      LIMIT ?";
    $del = $dbh->prepare($sql5);
    $del->execute($invitations);
    $guests = $del->fetchColumn();
    $addresses = $del->fetchColumn(1);
    /*Preparo la query per inserire gli inviti nel db fuori dal ciclo, tenendo
      come "incognita" solo il nickname dell'utente.*/
    $sql6 = "INSERT INTO invito(id_pranzo, nomeRistorante, username)
      VALUES ('$id_lunch', '$placename', ?)";
    /*Preparo il messaggio di posta elettronica di cui invierò una copia a tutti
      gli invitati al pranzo*/
    $mail = new PHPMailer;

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;

    $mail->Username = "enakridarbuk@gmail.com";
    $mail->Password = "bukkin88";

    $mail->setFrom('no-reply@lunchroulette.com', 'Lunch Roulette Service');
    $mail->isHTML(true);

    $ical = $ics->to_string();
    $mail->addStringAttachment("$ical", "invito.ics", "base64", "text/calendar;
      charset=utf-8; method=REQUEST");

    $mail->Subject = "Invito a pranzo - Lunch Roulette";

    for ($i = 0; $i < $invitations; $i++) {
      $del = $dbh->prepare($sql6);
      $del->execute($guests[$i]);

      $mail->addAddress($addresses[$i], $guests[$i]);
      $mail->Body = "<b>" . $guests[i] . "</b>, hai ricevuto un invito a pranzo!";
      $mail->AltBody = "Hai ricevuto un invito a pranzo!";

      try {
        $mail->send();
        echo "Message has been sent successfully";
      } catch (Exception $e) {
        echo "Mailer error: " . $mail->ErrorInfo;
        die();
      }
    }
  } else {
    print "Errore!: La ricerca di un ristorante non ha restituito nomi validi
      e/o non ci sono abbastanza iscritti al serivzio per organizzare un pranzo.";
  	die();
  }
?>