<?php
	/*************************************/
	/*********** CONFIGURATION ***********/
	/*************************************/

	// include(Pfad zur Datei): Bei Fehler wird das Skript weiter ausgeführt. Problem mit doppelter Einbindung derselben Datei
	// require(Pfad zur Datei): Bei Fehler wird das Skript gestoppt. Problem mit doppelter Einbindung derselben Datei
	// include_once(Pfad zur Datei): Bei Fehler wird das Skript weiter ausgeführt. Kein Problem mit doppelter Einbindung derselben Datei
	// require_once(Pfad zur Datei): Bei Fehler wird das Skript gestoppt. Kein Problem mit doppelter Einbindung derselben Datei

	require_once("include/config.inc.php");			
	require_once("include/form.inc.php");
	require_once("include/db.inc.php");

/* Variablen initialisieren *********************************************************************************************************/

			$errorPassword = NULL;
			$dbMessage = NULL;
			$showForm = true;


/* URL-Parameterverarbeitung ********************************************************************************************************/

				// Schritt 1 URL: Prüfen ob URL-Parameter übergeben wurden
				if( !isset($_GET["id"]) || !isset($_GET["hash"]) ) {
					// Zugriff verboten
if(DEBUG)			echo "<p class='debug'>[0] Seitenzugriff verboten.</p>";
					DIE("<h3 class='error'>Sie sind nicht berechtigt, diese Seite aufzurufen!<br>
					Wenn Sie Ihr Passwort zurücksetzen wollen, klicken Sie <a href='passwordForgotten.php'>hier</a>.");
				} else {
					// Seitenzugriff erlaubt
if(DEBUG)			echo "<p class='debug'>[START] Seitenzugriff erlaubt.</p>";

				// Schritt 2 URL: Werte auslesen, entschärfen und DEBUG-Ausgabe
					$usr_id = cleanString("$_GET[id]");
					$acc_pwdhash = cleanString("$_GET[hash]");
if(DEBUG)			echo "<p class='debug'>\$usr_id : $usr_id</p>";
if(DEBUG)			echo "<p class='debug'>\$acc_pwdhash : $acc_pwdhash</p>";

				// Schritt 3 URL: AUSNAHME: Werte validieren DB-Operation
					
		/*********** Accountdaten aus DB auslesen ***********/

				// Schritt 1 DB-Verbindung herstellen
					$pdo = dbConnect();

				// Schritt 2 SQL-Statement vorbereiten
					$statement = $pdo->prepare("
						SELECT acc_pwdhash, acc_pwdtimestamp
						FROM accounts
						WHERE usr_id = :ph_usr_id
					");

				// Schritt 3 SQL-Statement ausführen
					$statement->execute(array( "ph_usr_id" => $_GET["id"] )) OR DIE($statement->errorInfo()[2]);

				// Schritt 4 Weiterverarbeiten der Daten aus der DB-Abfrage/ bei Schreibvorgang Erfolg prüfen
					$row = $statement->fetch();

		/* Sicherheit 1: Prüfen, ob ID in der DB existiert ************************************************************/

					if( !$row ) {
						// Fehlerfall
if(DEBUG)				echo "<p class='debug'>[FEHLER] USR_ID wurde nicht in der Datenbank gefunden.</p>";
						DIE("<h3 class='error'>Der übergebene Link ist ungültig!<br> 
						Überprüfen Sie ggf. den korrekten Link aus der E-Mail.");
					} else {
						// Erfolgsfall
if(DEBUG)				echo "<p class='debug'>[Sicherheit 1] USR_ID wurde in der Datenbank gefunden.</p>";

		/* Sicherheit 2: Prüfen, ob zu der User_ID ein Hashwert existiert. *********************************************/

						if( !$row["acc_pwdhash"] ){
							// Fehlerfall
if(DEBUG)					echo "<p class='debug'>[FEHLER] Kein Hash in der DB gefunden!</p>";
							DIE("<h3 class='error'>Der übergebene Link ist ungültig!<br> 
							Überprüfen Sie ggf. den korrekten Link aus der E-Mail.");
						} else {
							// Erfolgsfall
if(DEBUG)					echo "<p class='debug'>[Sicherheit 2] Hash in der DB gefunden!</p>";

		/* Sicherheit 3: Prüfen, ob Hashwert übereinstimmen. ***********************************************************/

							if( $row["acc_pwdhash"] != $acc_pwdhash ) {
								// Fehlerfall
if(DEBUG)						echo "<p class='debug'>[FEHLER] URL-Hash stimmt nicht mit dem Hash aus der DB überein!</p>";
								DIE("<h3 class='error'>Der übergebene Link ist ungültig!<br> 
								Überprüfen Sie ggf. den korrekten Link aus der E-Mail.");
							} else {
								// Erfolgsfall
if(DEBUG)						echo "<p class='debug'>[Sicherheit 3] URL-Hash und DB-Hash stimmen überein.</p>";

		/* Sicherheit 4: Prüfen, ob der Timestamp bereits abgelaufen ist ***********************************************/
							// Timestamp aus DB auslesen und in UNIX-Format umwandeln
								$timestampDB = strtotime($row["acc_pwdtimestamp"]);
//if(DEBUG)						echo "<p class='debug'>\$timestampDB : $timestampDB</p>";

								if( $timestampDB < time()-60*60*24 ) {
									// Fehlerfall
if(DEBUG)							echo "<p class='debug'>[FEHLER] Timestamp ist bereits abgelaufen</p>";
									DIE("<h3 class='error'>Der übergebene Link ist nicht mehr gültig!<br> 
									Bitte fordern Sie einen neuen Link zum Zurücksetzen des Passworts an.");

								} else {
									// Erfolgsfall
if(DEBUG)							echo "<p class='debug'>[Sicherheit 4] Timestamp ist gültig. [$timestampDB < ". $now = time()-60*60*24 ."]</p>";

									// Da durch die DIE()-Aufrufe das Script bei jedem Fehler komplet abbricht,
									// muss an dieser Stelle (wenn alles erfolgreich geprüft wurde) nichts weiter unternommen werden.

								} // ENDE Timestamp Prüfen
							} // ENDE Hash prüfen
						} // ENDE Existenz prüfen
					} // ENDE USR_ID prüfen
				} // ENDE URL-Parameterverarbeitung


/* Formularverarbeitung *************************************************************************************************************/

					// Schritt 1 Form: Prüfen, ob Formular abgeschickt wurde
				if( isset($_POST["formsentNewPassword"]) ){
if(DEBUG)			echo "<hr><p class='debug'>[1] Formular wurde abgeschickt</p>";
					
					// Schritt 2 Form: Werte auslesen, entschärfen und DEBUG-Ausgabe
					$password 		= cleanString($_POST["password"]);
					$passwordCheck	= cleanString($_POST["passwordCheck"]);

if(DEBUG)			echo "<p class='debug'>\$password : $password</p>";
if(DEBUG)			echo "<p class='debug'>\$passwordCheck : $passwordCheck</p>";

					// Schritt 3 Form: Werte validieren
					$errorPassword = checkInputString($password, 8);

					if( $password != $passwordCheck ) {
						// Fehlerfall
if(DEBUG)				echo "<p class='debug'>[FEHLER] Passworte stimmen nicht überein!</p>";
					} else {
						// Erfolgsfall
						if( $errorPassword ){ 
							// Fehlerfall
if(DEBUG)					echo "<p class='debug'>[FEHLER] Passworte stimmen nicht überein!</p>";
						} else {
if(DEBUG)					echo "<p class='debug'>[2] Formular ist korrekt.</p>";

					
					// Passwort neu verschlüsseln
							$passwordHash = password_hash($password, PASSWORD_DEFAULT);
if(DEBUG)					echo "<p class='debug'>\$passwordHash : $passwordHash</p>";

	/* Datenbankoperation ***********************************************************************************************************/

						// Schritt 2 DB: SQL-Statement vorbereiten
							$statement = $pdo->prepare("
								UPDATE accounts
								SET 
								acc_password = :ph_acc_password,
								acc_pwdtimestamp = :ph_acc_pwdtimestamp,
								acc_pwdhash = :ph_acc_pwdhash
								WHERE usr_id = :ph_usr_id
							");

						// Schritt 3 DB: SQL-Statement ausführen
							$statement->execute(array(
								"ph_acc_password" => $passwordHash,
								"ph_acc_pwdtimestamp" => NULL,
								"ph_acc_pwdhash" => NULL,
								"ph_usr_id" => $_GET["id"]
							)) OR DIE ($statement->errorInfo()[2]);

						// Schritt 4 DB: Daten Weiterverarbeiten
							$affectedRows = $statement->rowCount();
							if( !$affectedRows ) {
								// Fehlerfall
if(DEBUG)						echo "<p class='debug'>[FEHLER] Fehler beim schreiben in die Datenbank!</p>";
								$dbMessage = "<h3 class='error'>Es ist ein Fehler aufgetreten. <br>
												Bitte versuchen Sie es später noch einmal.</h3>";
							} else {
								// Erfolgsfall
if(DEBUG)						echo "<p class='debug'>[3] Datensatz wurde aktualisiert</p>";

								header( "Location: index.php?action=passwordChanged" );
								exit;
								$dbMessage = "<h3 class='success'>Ihr Passwort wurde erfolgreich geändert!</h3>
												<p><a href='index.php'>Zurück zum Login</a></p>";
								$showForm = false;
							} // ENDE Daten weiterverarbeiten
						} // ENDE Passwort ist fehlerfrei
					} // ENDE Passworte sind gleich
				} // ENDE Formularverarbeitung


/************************************************************************************************************************************/


?>

<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Benutzerverwaltung - Passwort neu setzen</title>
		<link rel="stylesheet" href="../css/main.css">
	</head>
	<body>

	<h1>Benutzerverwaltung - Passwort neu setzen</h1>
	<?= $dbMessage ?>
	<?php if( $showForm ) : ?>
	<form action="" method="POST">
		<input type="hidden" name="formsentNewPassword">
		<span class="error"><?= $errorPassword ?></span>
		<input type="password" name="password" placeholder="Bitte geben Sie hier ihr neues Passwort ein"><span class="marker">*</span><br>
		<input type="password" name="passwordCheck" placeholder="Bitte wiederholen Sie hier ihr neues Passwort ein"><span class="marker">*</span><br>
		<input type="submit" value="Neues Passwort speichern">
	</form>
	<?php endif ?>

	</body>
</html>