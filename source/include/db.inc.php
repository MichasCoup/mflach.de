<?php
/******************************************************************************************************/


				/**
				*
				*	Stellt eine Verbindung zu einer Datenbank mittels PDO her
				*
				*	@param [String $dbname		Name der zu verbindenden Datenbank]
				*
				*	@return Object					DB-Verbindungsobjekt
				*
				*/
				function dbConnect($dbname=DB_NAME) {
if(DEBUG_DB)		echo "<p class='debugDb'>Versuche mit der DB <b>$dbname</b> zu verbinden...</p>";					
					
					// EXCEPTION-HANDLING (Umgang mit Fehlern)
					// Versuche, eine DB-Verbindung aufzubauen
					try {
						// wirft, falls fehlgeschlagen, eine Fehlermeldung "in den leeren Raum"
						
						// $pdo = new PDO("mysql:host=localhost; dbname=market; charset=utf8", "root", "");
						$pdo = new PDO(DB_SYSTEM . ":host=" . DB_HOST . "; dbname=$dbname; charset=utf8", DB_USER, DB_PWD);
					
					// falls eine Fehlermeldung geworfen wurde, wird sie hier aufgefangen					
					} catch(PDOException $error) {
						// Ausgabe der Fehlermeldung
if(DEBUG_DB)			echo "<p class='error'><i>FEHLER: " . $error->GetMessage() . " </i></p>";
						// Skript abbrechen
						exit;
					}
					// Falls das Skript nicht abgebrochen wurde (kein Fehler), geht es hier weiter
if(DEBUG_DB)		echo "<p class='debugDb'>Erfolgreich mit der DB <b>$dbname</b> verbunden.</p>";

					// DB-Verbindungsobjekt zurückgeben
					return $pdo;
				}
				
				
/******************************************************************************************************/
?>