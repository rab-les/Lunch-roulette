LUNCH ROULETTE SERVICE - by Filippo Savini

Questo progetto è stato sviluppato interamente senza l'utilizzo di framework.
Si raccomanda di seguire attentamente la procedura riportata in questo documento,
altrimenti non sarà possibile testare il funzionamento dell'applicazione.

1) L'applicazione è stata sviluppata in ambiente Microsoft (Windows 10) e
avvalendosi di gmail come server di posta elettronica. Tutti gli accorgimenti
presi in fase di realizzazione sono calibrati all necessità di questi due;

2) Una volta effettuato il download della repository GitHub contenente
l'applicazione, scaricare ed installare Xampp per Windows (ultima versione:
8.0.12) sulla macchina dove si procederà al test. La nuova cartella nella quale
sarà contenuta l'applicazione deve essere all'interno del disco C: e chiamarsi
semplicemente 'Xampp' (quindi C:\Xampp);

3) Collocare la repository Lunch-roulette-app all'interno della cartella
C:\Xampp\htdocs, assicurandosi che appena si entra nella cartella, siano
immediatamente visibili le sottocartelle img, doc, src e questo file readme;

4) Se Composer (A Dependency Manager for PHP) non è già installato sulla
macchina, scaricarlo e installarlo in un'apposita cartella C:\Xampp\composer. Se
è già presente, reinstallarlo nella summenzionata cartella;

5) Installare PHPMailer utilizzando composer digitando da linea di comando:
"composer require phpmailer/phpmailer";

6) Controllare che una nuova cartella "phpmailer" contenente la summenzionata
classe sia stata correttamente creata all'interno di C:\Xampp\composer\vendor;

7) Modificare il file PHP.ini all'interno della cartella C:\Xampp\php. Trovare
la sezione intitolata [mail function] ed editare in modo che le righe successive
(quelle non commentate, almeno) abbiano questo aspetto:
SMTP=smtp.gmail.com
smtp_port=587
sendmail_from = noreply.lunchroulette@gmail.com
sendmail_path = "\"C:\Xampp\sendmail\sendmail.exe\" -t"

8) Modificare ora il file sendmail.ini che si trova all'interno della cartella
C:\Xampp\sendmail. Nella sezione intitolata [sendmail] rimpiazzare le righe di
codice esistenti con quelle riportate qui sotto:
smtp_server=mail.gmail.com
smtp_port=587
error_logfile=error.log
debug_logfile=debug.log
auth_username=noreply.lunchroulette@gmail.com
auth_password=504o7Z7@
force_sender=noreply.lunchroulette@gmail.com

9) NB: "noreply.lunchroulette@gmail.com" è l'account gmail che ho creato
esclusivamente per testare quest'applicazione. Verrà molto probabilmente
cancellato nel prossimo futuro;

10) Avviare Xampp (Apache e phpmyadmin) ed effettuare il primo accesso a
localhost/phpmyadmin. Come password per accedere al servizio, scegliere "toor"
(username: "root");

11) Tutto è pronto per utilizzare la parte front-end dell'applicazione. Aprire
su localhost il file C:\Xampp\htdocs\Lunch-roulette-app\src\html\Front-end\home.
Alla prima apertura lo script php all'interno del file genererà automaticamente
il database dell'applicazione;

12) Utilizzare le funzionalità offerte da Lunch Roulette per popolare il
database. Effettuare primi accessi con credenziali diverse per popolare la
tabella UTENTE (fornendo indirizzi e-mail sempre diversi ma possibilmente
esistenti) e, all'interno del servizio, inserire nuovi posti dove consumare un
pasto per popolare la tabella RISTORANTE. Quando si riterrà di aver popolato il
db a sufficienza, uscire dall'applicazione;

13) All'interno della cartella C:\Xampp\htdocs\Lunch-roulette-app\doc è presente
il file variables.env contenente tutte le variabili d'ambiente non hard-coded
come richiesto dalla consegna dell'esercitazione. Modificarle a piacere,
mantenendo però la formattazione di quelle che ho fornito di default;

14) Si può già fare un esperimento per testare le funzionalità non accessibili
agli utenti aprendo su localhost il file C:\Xampp\htdocs\Lunch-roulette-app\src\
html\Back-end\setLunchAndInvitations.php. Questo script, aiutato dalle funzioni
e classi contenute negli altri file della cartella Back-end, crea in automatico
ogni volta che viene eseguito una nuova riga nella tabella PRANZO, genera nuove
righe della tabella INVITO relative a questo pranzo e spedisce per posta
elettronica gli inviti agli iscritti al servizio, scelti casualmente tra quelli
registrati nella tabella UTENTE;

15) NB: L'applicazione è tarata per generare e spedire gli inviti ogni domenica,
quindi se il giorno in cui la si sperimenta non fosse domenica, il giorno del
pranzo potrebbe non corrispondere ai valori della variabile d'ambiente GIORNI in
variables.env;

15) Per istituire un servizio settimanale che in automatico stabilisca i pranzi
tra colleghi e spedisca gli inviti, in ambiente Linx si potrebbe utilizzare un
cronjob. In ambiente Microsoft, bisogna utilizzare il Task Scheduler di Windows
("utilità di pianificazione" in italiano) oppure un applicazione sviluppata da
terzi come Z-Cron per fissare un'esecuzione dello script setLunchAndInvitations
ogni domenica.
