<?php

function getDateTimeString(string $day, string $time) :string
{
  switch ($day) {
    case "lunedì":
      $shift = "+1 day";
      break;
    case "martedì":
      $shift = "+2 days";
      break;
    case "mercoledì":
      $shift = "+3 days";
      break;
    case "giovedì":
      $shift = "+4 days";
      break;
    case "venerdì":
      $shift = "+5 days";
      break;
    case "sabato":
      $shift = "+6 days";
      break;
    case "domenica":
      $shift = "+7 days";
      break;
    default:
      echo "Error! La variabile GIORNI nel file .env non è impostata correttamente.";
      die();
  }
  /*Divido ore e minuti, separati dai due punti*/
  $timeComponents = explode(':', $time, 2);
  /*Ottengo una stringa contenente la data di oggi*/
  $today = date('Y-m-d');
  /*Creo un nuovo oggetto DateTime passando come argomento la stringa appena creata*/
  $date = new DateTime($today);
  /*Setto il fuso orario a quello italiano*/
  $date->setTimeZone(new DateTimeZone("Europe/Rome"));
  /*Modifico la data in modo che si adegui al giorno della settimana in cui si
    svolgerà il pranzo tra colleghi*/
  $date->modify($shift);
  /*Imposto l'orario di inizio/fine del pranzo*/
  $date->setTime($timeComponents[0], $timeComponents[1]);
  /*Restituisco l'oggetto DateTime correttamente impostato*/
  return $date->format('Y-m-d H:i');
}
?>
