<?php

session_name("blog");
session_start();

if ( isset( $_SESSION["usr_id"] ) ) {
    header("Location: portfolio.php");
}


/*********************************************************************************************************************************************/
/* Configuartion *****************************************************************************************************************************/
/*********************************************************************************************************************************************/
require_once("include/config.inc.php");
require_once("include/form.inc.php");
require_once("include/db.inc.php");
require_once("include/dateTime.inc.php");

/*********************************************************************************************************************************************/
/* Variablen Initialisieren ******************************************************************************************************************/
/*********************************************************************************************************************************************/

$dbMessage 		= NULL;
$errorEmail 	= NULL;
$errorPassword	= NULL;

/*********************************************************************************************************************************************/
/* Login *************************************************************************************************************************************/
/*********************************************************************************************************************************************/

/* Formularverarbeitung **********************************************************************************************************************/

// Schritt 1 FORM: Prüfen, ob Formular abgeschickt wurde
if( isset($_POST["formsentLogin"]) ) {
    if(DEBUG)			echo "<p class='debug'>[LOGIN: Start] Loginformular wurde abgeschickt.</p>";

    // Schritt 2 FORM: Werte auslesen, entschärfen, DEBUG-Ausgabe
    $email		= cleanString($_POST["email"]);
    $password	= cleanString($_POST["password"]);
    if(DEBUG)			echo "<p class='debug'>[LOGIN: Werte] \$email : $email</p>";
    if(DEBUG)			echo "<p class='debug'>[LOGIN: Werte] \$password : $password</p>";

    // Schritt 3 FORM: Optional: Werte validieren
    $errorEmail 	= checkEmail($email);
    $errorPassword 	= checkInputstring($password, 4);

    // Abschließende Formularprüfung (ist das Formular fehlerfrei?)
    if( $errorEmail || $errorPassword ) {
        // Fehlerfall
        if(DEBUG)				echo "<p class='debug'>[FEHLER] E-Mail oder Passwort war nicht korrekt.</p>";
        $dbMessage	= "<p class='error'>E-Mail oder Passwort war nicht korrekt.</p>";
    } else {
        // Erfolgsfall
        if(DEBUG)				echo "<p class='debug'>[LOGIN: Prüfung] Loginformular wurde fehlerfrei ausgefüllt.</p>";

        // Schritt 4 FORM: Daten Weiterverarbeiten

        /* Datenbankoperation ************************************************************************************************************************/

        $pdo = dbConnect();
        // Schritt 2 DB: SQL-Statement vorbereiten
        $statement = $pdo->prepare("
								SELECT usr_id, usr_firstname, usr_lastname, usr_company, usr_email, usr_password
								FROM users
								WHERE usr_email = :ph_usr_email
							");

        // Schritt 3 DB: SQL-Satement ausführen und ggf. Platzhalter füllen
        $statement->execute( array( "ph_usr_email" => $email ) ) OR DIE($statement-errorInfo()[2]);

        // Schritt 4 DB: Daten weiterverarbeiten
        $row = $statement->fetch();

        // Bei einem Erfolgreichen Login liefert $row genau einen Datensatz zurück
        if( !$row ) {
            // Fehlerfall
            if(DEBUG)						echo "<p class='debug'>[FEHLER] Keinen Datensatz zu der E-Mail ($email) gefunden.</p>";
            $dbMessage = "<p class='error'>E-Mail oder Passwort war nicht korrekt.</p>";
        } else {
            // Erfolgsfall
            if(DEBUG)						echo "<p class='debug'>[LOGIN DB] Datensatz von der Datenbank erhalten.</p>";

            // Prüfung, ob das eingegebene Passwort mit dem aus der Datenbank übereinstimmt
            if( !password_verify($password, $row["usr_password"]) ) {
                // Fehlerfall
                if(DEBUG)							echo "<p class='debug'>[FEHLER] Das eingegebene Passwort stimmt nicht mit dem in der Datenbank überein.</p>";
                $dbMessage = "<p class='error'>E-Mail oder Passwort war nicht korrekt.</p>";
            } else {
                // Erfolgsfall
                if(DEBUG)							echo "<p class='debug'>[LOGIN PW] Das eingegebene Passwort stimmt mit dem in der Datenbank überein.</p>";


                /* TimeStamp erstellen ************************************************************************************************************/

                $statement = $pdo->prepare("
                    UPDATE users
                    SET usr_lastlogin = NOW()
                    WHERE usr_id = :ph_usr_id
                ");
                $statement->execute( array( "ph_usr_id" => $row["usr_id"])) OR DIE($statement-errorInfo()[2]);

                /* Session erstellen und befüllen ************************************************************************************************************/

                // usr_id = als Bestätigung für die nachfolgende Seite
                // usr_firstname, usr_lastname = als Begrüßung für die nachfolgende Seite

                $_SESSION["usr_id"] = $row["usr_id"];
                $_SESSION["usr_firstname"] = $row["usr_firstname"];
                $_SESSION["usr_lastname"] = $row["usr_lastname"];
                if(DEBUG)							echo "<p class='debug'>[Session] Session wurde erstellt und befüllt. </p>";

                /* Infomail */
                $to 	 = "mail@mflach.de";
                $subject = "neuer login";
                $from	 = "FROM: mflach.de <donotreply@mflach.de>\r\n";
                $from	.= "Content-Type: text/html\r\n";
                $dateTime = convertIsotoEuDateTime(time());
                $content = "Neuer Login von: ".$row["usr_firstname"]." ".$row["usr_lastname"]." am ".$dateTime['date']." um ".$dateTime['time'];

                // Weiterleiten auf Portfolio
                header("Location: portfolio.php");

            } // ENDE Login, Passwortprüfung und schreiben der Session
        } // ENDE Login, Datenbank abfrage auf gültige E-Mail war erfolgreich
    } // ENDE Login Erfolgreiche Prüfung der Formulareingabe
} // ENDE Login Formularverarbeitung


/*********************************************************************************************************************************************/

?>

<!doctype html>
<html lang="de">
<head>
    <title>Portfolio Michael Flach</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="css/portfolio.css" type="text/css" media="screen"/>
</head>
<body class="max-view">
<div id="top"></div>
<header class="small-content">
    <div class="logo">
        <span><a href="index.php">Zur Startseite</a></span>
    </div>
</header>
<main class="small-content">
    <article class="login">
        <form action="" method="post">
            <label for="email" class="inp">
                <input type="text" name="email" id="email" placeholder="&nbsp;">
                <span class="label">E-Mail:</span>
            </label>

            <label for="password" class="inp">
                <input type="password" name="password" id="password" placeholder="&nbsp;">
                <span class="label">Passwort:</span>
            </label>

            <input type="submit" name="formsentLogin" value="Login">
        </form>

    </article>
</main>
<footer class="small-content">
    <ul>
        <li><a href="registration.php">Anmelden</a></li>
        <li><a href="passwordForgotten.php">Passwort vergessen</a></li>
        <li><a href="impressum.html">Impressum</a></li>
    </ul>
</footer>
<script src="scripts/script.js"></script>
</body>
</html>
