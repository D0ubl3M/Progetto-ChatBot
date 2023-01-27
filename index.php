<?php
/*
  https://api.telegram.org/bot5609730330:AAFtgTbZrGhg7WnX4zD4MzXNKQfni5-aC4g/setWebHook?url=https://doublem.altervista.org/TelegramBot/index.php

  https://api.telegram.org/bot5609730330:AAFtgTbZrGhg7WnX4zD4MzXNKQfni5-aC4g/setWebHook?url=https://de3c-151-51-189-114.eu.ngrok.io/php/TelegramBot/
  https://api.telegram.org/bot5609730330:AAFtgTbZrGhg7WnX4zD4MzXNKQfni5-aC4g/getWebhookInfo
*/

$botToken = "5609730330:AAFtgTbZrGhg7WnX4zD4MzXNKQfni5-aC4g";
$website = "https://api.telegram.org/bot".$botToken;
$importanza = -1;
for ($i = 0; $i < 16; $i++) {
  $importanze[$i] = array();
}
//GetUpdate
$update = file_get_contents('php://input');
$updateraw = $update;


$update = json_decode($update, TRUE);

$chatID = $update["message"]["from"]["id"];
$message = $update["message"]["text"];
$message_id = $update["message"]["message_id"];
$nome = $update["message"]["chat"]["first_name"];
$cognome = $update["message"]["chat"]["last_name"];
$username = $update["message"]["chat"]["username"];

/*
$myfile = fopen("lastupdate.txt", "w") or die("Unable to open file!");
fwrite($myfile, $updateraw);
fclose($myfile);
*/
//$message = "ciao.? /mi &^@chiamo michele?!?!?!?!";
echo "$message <br>";

$frase = rimuoviCaratteriNonAlfanumeri ($message);
echo "$frase <br>";

$parole = explode(" ", $frase);
print_r($parole);

associaImportanzaParole($parole);

$messaggio = scegliMessaggio();
InviaMessaggio($chatID, $messaggio);

function scegliMessaggio () {
  global $importanze;
    $paroleDisponibili = $importanze[$importanza];
    $parola = array_rand($paroleDisponibili, 1);
    switch ($parola) {
      case 'ciao':
          return "ciao";

      default:
        return "Non capisco quello che stai cercando di dirmi, prova ad esprimerti meglio";
    }
    //return "null";
}

function associaImportanzaParole ($parole) {
  foreach ($parole as $parola) {
    if ($parola != " ") {
      switch ($parola) {
          case 'ciao':
            inserisciParole('ciao', 1);
          break;

          default:
            inserisciParole('', 0);
          break;
      }
    }
  }

}

function inserisciParole ($parola, $importanzaParola) {
  global $importanza;
  global $importanze;
  if ($importanza < $importanzaParola) {
    $importanza = $importanzaParola;
  }
    $importanze[$importanzaParola] = array_push($parola);
}

function rimuoviCaratteriNonAlfanumeri ($frase) {
  return preg_replace("/[^a-zA-Z0-9]/", " ", $frase);
}

/*function rimuoviSpaziBianchi ($arrayParole) {
  for ($i = 0; $i < count($arrayParole)-1; $i++) {
      if (isEmpty($arrayParole[$i])) {
        $arrayParole[$i] = $arrayParole[$i+1];
      }
  }
}*/


function InviaMessaggio($chatID,$messaggio) {
    $url = "$GLOBALS[website]/sendMessage?chat_id=$chatID&parse_mode=HTML&text=".urlencode($messaggio);
    file_get_contents($url);
}
?>
