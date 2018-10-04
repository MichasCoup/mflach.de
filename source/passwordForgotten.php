<?php
	/*************************************/
	/*********** CONFIGURATION ***********/
	/*************************************/

	// include(Pfad zur Datei): Bei Fehler wird das Skript weiter ausgeführt. Problem mit doppelter Einbindung derselben Datei
	// require(Pfad zur Datei): Bei Fehler wird das Skript gestoppt. Problem mit doppelter Einbindung derselben Datei
	// include_once(Pfad zur Datei): Bei Fehler wird das Skript weiter ausgeführt. Kein Problem mit doppelter Einbindung derselben Datei
	// require_once(Pfad zur Datei): Bei Fehler wird das Skript gestoppt. Kein Problem mit doppelter Einbindung derselben Datei

	//include("../include/functions.inc.php");
	require_once("include/config.inc.php");			
	require_once("include/form.inc.php");
	require_once("include/db.inc.php");

/************************************************************************************************************************************/
			/*******************************************************/
			/************** Variablen Initialisieren ***************/
			/*******************************************************/
			$email = NULL;
			$errorEmail = NULL;
			$mailMessage = NULL;
			$showForm = false;

/* Formularverarbeitung *************************************************************************************************************/
				// Schritt 1: Prüfen ob Form abgeschickt wurde
				if( isset($_POST["formsentPassword"]) ) {
if(DEBUG)			echo "<p class='debug'>[START] Formular abgeschickt ...</p>";

				// Schritt 2: Werte auslesen, entschärfen und DEBUG-Ausgabe
					
					$email = cleanString($_POST["email"]);			// Werte entschärften
if(DEBUG)			echo "<p class='debug'>\$email : $email</p>";	// Werte auslesen

				// Schritt 3: Werte validieren
					$errorEmail = "<p class='error'>".checkEmail($email)."</p>";
				// Abschließende Formularprüfung
					if( !$errorEmail ) {
						// Fehlerfall
if(DEBUG)				echo "<p class='debug'>[1] Formularprüfung <b>nicht</b> bestanden.</p>";

					} else {
						// Erfolgsfall
if(DEBUG)				echo "<p class='debug'>[1] Formularprüfung bestanden.</p>";
				// Schritt 4: Daten weiterverarbeiten

			/************** Prüfen, ob E-Mail in der Datenbank gespeichert ist *********************/

					// Schritt 1: DB-Verbindung herstellen
						$pdo = dbConnect();

					// Schritt 2: DB- Statement vorbereiten
						$statement = $pdo->prepare("
							SELECT usr_id, usr_firstname, usr_lastname, acc_name 
							FROM users 
							INNER JOIN accounts 
							USING(usr_id)
							WHERE usr_email = :ph_usr_email
						");

					// Schritt 3: SQL-Statemnent ausführen und ggf. Platzhalter füllen
						$statement->execute(array(
							"ph_usr_email" => $email
						)) OR DIE ($statement->errorInfo()[2]);

					// Schritt 4: Daten weiterverarbeiten
						$row = $statement->fetch();


					// Wenn E-Mail nicht in der DB gespeichert ist, wird an dieser Stelle kein Datensatz geliefert
						if( !$row ) {
							// Fehlerfall
if(DEBUG)					echo "<p class='debug'>[2] Email '$email' nicht in der Datenbank gefunden.</p>";
							$errorEmail = "<p class='error'>Diese E-Mail ist uns nicht bekannt</p>";

						} else {
							// Erfolgsfall
if(DEBUG)					echo "<p class='debug'>[2] Email '$email' wurde in der Datenbank gefunden.</p>";

							// Profildaten aus Datensatz auslesen
							$usr_id 		= $row["usr_id"];
							$usr_firstname 		= $row["usr_firstname"];
							$usr_lastname 		= $row["usr_lastname"];
							$accountname 	= $row["acc_name"];

if(DEBUG)					echo "<p class='debug'>\$usr_id : $usr_id</p>";	
if(DEBUG)					echo "<p class='debug'>\$firstname : $usr_firstname</p>";	
if(DEBUG)					echo "<p class='debug'>\$lastname : $usr_lastname</p>";	
if(DEBUG)					echo "<p class='debug'>\$accountname : $accountname</p>";	
							

							/***************** SICHERHEIT: HASH UND TIMESTAMP IN DB SCHREIBEN *****************/
							$pwdHash = sha1(rand(1,1000)."_".$accountname."_".time());
if(DEBUG)					echo "<p class='debug'>\$pwdHash : $pwdHash</p>";

							// Sicherheitshash und Timestamp in die DB schreiben

							// Schritt 2: SQL -Statement vorbereiten
							$statement = $pdo->prepare("
								UPDATE accounts
								SET 
								acc_pwdhash = :ph_acc_pwdhash,
								acc_pwdtimestamp = NOW()
								WHERE usr_id = :ph_usr_id
							");
							// Schritt 3: Statement ausführen
							$statement->execute(array(
								"ph_acc_pwdhash" => $pwdHash,
								"ph_usr_id" => $usr_id
							)) OR DIE ($statement->errorInfo()[2]);
							
							// schritt 4: Daten weiterverarbeiten
							// Prüfen, ob Schreibvorgang erfolgreich war

							$effectedRows = $statement->rowCount();

							if( !$effectedRows ) {
								// Fehlerfall
if(DEBUG)						echo "<p class='debug'>[3] FEHLER beim Schreiben des Hashes und des TimeStamps.</p>";
								$mailMessage = "<h3 class='error'>Es ist ein Fehler aufgetreten. Bitte versuchen Sie es noch einmal.</h3>";
							} else {
								// Erfolgsfall
if(DEBUG)						echo "<p class='debug'>[3] Erfolgreiches Schreiben des Hashes und des TimeStamps in die Datenbank.</p>";

						/********************* Bestätigungsmail generieren ***********************/

								// Bestätigungslink generieren
								$link = "http://localhost/benutzerverwaltung/passwordreset.php?id=$usr_id&hash=$pwdHash";
if(DEBUG)						echo "<p class='debug'>[4a] \$link = '<a href='http://localhost/benutzerverwaltung/passwordreset.php?id=$usr_id&hash=$pwdHash'>http://localhost/benutzerverwaltung/passwordreset.php?id=$usr_id&hash=$pwdHash'</a></p>";

								// Email mit Link zum Zurücksetzen des Passwortes generieren
								// PHP-Funktion zum Erzeugen und Versenden einer Email:
								// mail( String Empfängeradresse, String Betreff, String Inhalt, String Absenderadresse )

								$to 	 = $email;
								$subject = "Neues Passwort für Ihren Account auf www.meineseite.de";
								$from	 = "FROM: www.meineseite.de <donotreply@meineseite.de>\r\n";// PFLICHT
								$from	.= "Reply-to: customerservice@meineseite.de\r\n";			// optional: Adresse für "Antworten"-Button
								$from	.= "Content-Type: text/html\r\n";							// optional: Email in HTML-Format

								$content = "<h4>Hallo $usr_firstname $usr_lastname,</h4>
											<p>Sie haben am " . date("d.m.Y") . " um " . date("H:i") . " Uhr 
											ein neues Passwort angefordert.</p>
											<p>Um Ihr Passwort zurückzusetzen, rufen Sie einfach innerhalb der 
											nächsten 24 Stunden folgende URL auf:</p>
											<br>
											<p><a href='$link'>$link</a></p>
											<br>
											<p>Zur Erinnerung: Ihr Login-Name lautet <b>$accountname</b>.</p>
											<p>Sollten Sie keine Rücksetzung Ihres Passwortes angefordert haben, 
											oder ist Ihnen Ihr Passwort wieder eingefallen, können Sie diese Email 
											einfach ignorieren. Es werden ohne Ihr Zutun keine Änderungen an 
											Ihrem Passwort vorgenommen.</p>
											<br>
											<p>Viele Grüße<br>
											Ihr www.meineseite.de-Team</p>";
// Vorschau Email
/*
if(DEBUG)						 echo "<hr>";
if(DEBUG)						 echo "<p>From: $from</p>";
if(DEBUG)						 echo "<p>To: $to</p>";
if(DEBUG)						 echo "<p>Subject: $subject</p>";
if(DEBUG)						 echo "$content";
if(DEBUG)						 echo "<hr>";
*/
							/********************* Bestätigungsmail versenden ***********************/
							/* 
							Damit die folgende Fehlerabfrage funktioniert, muss in XAMPP
							vorher der Mercury Email-Server konfiguriert und gestartet werden.
							*/
							// Ein @ vor einem Funktionsaufruf unterdrückt die von dieser 
							// Funktion geworfene Fehlermeldung im Frontend

								if ( !mail($to, $subject, $content, $from) ) {
									// Fehlerfall
if(DEBUG)							echo "<p class='debug'>[4b] FEHLER beim Versenden der Email an <b>$email</b></p>";
									$mailMessage = "<h3 class='error'>Es ist ein Fehler aufgetreten. Bitte versuchen Sie es noch einmal.</h3>";

								} else {
									// Erfolgsfall
if(DEBUG)							echo "<p class='debug'>[4b] Email wurde erfolgreich an <b>$email</b> versendet.</p>";
									$mailMessage = "<h3 class='success'>Hallo $usr_firstname $usr_lastname,<br>Sie erhalten in Kürze eine Email mit einem Link, über den Sie ein neues Passwort setzen können.</h3>
									<br><p><a href='index.php'>Zurück zur Startseite</a></p>";
									$showForm = true;
if(DEBUG)							echo "<p class='debug'>[Ende] Nutzerausgabe erzeugt.</p>";
								} // Ende Bestätigungsmail versenden
							} // Ende Bestätigungsmail generieren
						} // Ende Prüfung, ob E-Mail in der Datenbank gefunden wurde
					} // Ende Formularprüfung
				} // Ende Formularverarbeitung
					
			

/************************************************************************************************************************************/
?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Passwort zurücksetzen</title>
		<link rel="stylesheet" href="../css/main.css">
	</head>

	<body>
		<h1>Passwort zurücksetzen</h1>
<hr>
		<?= $mailMessage ?>
		<?php if( !$showForm): ?>
		<p>
			Geben Sie hier Ihre Email-Adresse ein, mit der Sie sich auf dieser Seite registriert haben.<br><br>
			Wir werden Ihnen dann per Email einen Link zusenden, über den Sie Ihr Passwort neu setzen können.
		</p>
		<form action="" method="POST">
			<input type="hidden" name="formsentPassword">
			<?= $errorEmail ?> 
			<input type="text" name="email" placeholder="Hier bitte Ihre E-Mail Adresse eingeben." value="<?= $email ?>" require><span class="marker">*</span>
			<input type="submit" value="Passwort zurücksetzen und E-Mail senden">
		</form>
		<?php endif ?>
	</body>
</html>