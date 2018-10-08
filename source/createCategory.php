<?php

session_name("blog");
session_start();

if ( !isset( $_SESSION["usr_id"] ) ) {
    header("Location: login.php");
}


/*********************************************************************************************************************************************/
/* Configuartion *****************************************************************************************************************************/
/*********************************************************************************************************************************************/
require_once("include/config.inc.php");
require_once("include/form.inc.php");
require_once("include/db.inc.php");
require_once("include/dateTime.inc.php");

$pdo = dbConnect();

/*********************************************************************************************************************************************/
/* Variablen Initialisieren ******************************************************************************************************************/
/*********************************************************************************************************************************************/

$newCat				= NULL;

$dbMessage          = NULL;

/*********************************************************************************************************************************************/
/* URL-Parameterverarbeitung *****************************************************************************************************************/
/*********************************************************************************************************************************************/

// Schritt 1 URL: Prüfen, ob ein URL-Parameter übergeben wurde
if( isset($_GET["action"]) ) {
    if (DEBUG) echo "<p class='debug'>URL-Parameter 'action' wurde übergeben.</p>";

// Schritt 2 URL: Werte auslesen, entschärfen, DEBUG-Ausgabe
    $action = cleanString($_GET["action"]);
    if (DEBUG) echo "<p class='debug'>\$action: $action";

// Schritt 3 URL: i.d.R. Verzweigen


    /* Verzweigung Logout */
    /*********************************************************************************************************************************************/

    if ($action == "logout") {
        // Session löschen
        session_destroy();
        // Umleiten auf Index.php
        header("Location: login.php");
        exit;
        // ENDE Verzweigung Logout
    }
}

/*********************************************************************************************************************************************/
/* Formularverarbeitung: Neue Kategorie anlegen **********************************************************************************************/
/*********************************************************************************************************************************************/

// Schritt 1 FORM: Prüfen, ob Formular abgeschickt wurde
if( isset($_POST["formsentNewCat"]) ) {
if(DEBUG)	echo "<p class='debug'>[POST-Categorie] Formular wurde abgeschickt für eine neue Kategorie.</p>";

    // Schritt 2 FORM: Werte auslesen, entschärfen, DEBUG-Ausgabe
    // Werte auslesen und entschärfen
    $newCat		= cleanString ($_POST["newCat"] );

    // DEBUG-Ausgabe
if(DEBUG)	echo "<p class='debug'>[POST-Categorie] \$newCat : $newCat</p>";

    // Schritt 3 FORM: Optional: Werte validieren
    $errorNewCategory = checkInputString($newCat);

    // Abschließende Formularprüfung (ist das Formular insgesamt fehlerfrei?)
    if( $errorNewCategory ){
        // Fehlerfall
if(DEBUG)	echo "<p class='debug'>[POST-Categorie] Im Formular ist ein Fehler aufgetreten.</p>";
        $dbMessageCat = "<p class='error'>Ein Fehler ist aufgetreten. Bitte überprüfen Sie Ihre Eingabe.</p>";
    } else {
        // Erfolgsfall
if(DEBUG)	echo "<p class='debug'>[POST-Categorie] Formular ist fehlerfrei.</p>";


        // Schritt 4 FORM: Daten Weiterverarbeiten

        /* Datenbankoperation Neue Kategorie anlegen *************************************************************************************************/

        // Prüfen, ob die Kategorie bereits vorhanden ist
        // Schritt 2 DB: SQL-Statement vorbereiten
        $statement = $pdo->prepare("
							SELECT cat_name
							FROM categories
							WHERE cat_name = :ph_cat_name
						");
        // Schritt 3 DB: SQL-Satement ausführen und ggf. Platzhalter füllen
        $statement->execute(array(
            "ph_cat_name"=> $newCat
        )) OR DIE($statement->errorInfo()[2]);
        // Schritt 4 DB: Daten weiterverarbeiten
        $row = $statement->fetch();

        if( $row ) {
            // Fehlerfall
if(DEBUG)	echo "<p class='debug'>[POST-Categorie] Die Kategorie '$row[cat_name]' befindet sich bereits in der Datenbank.</p>";
            $dbMessageCat = "<p class='error'>Fehler: Die Kategorie '$row[cat_name]' ist bereits vorhanden.<p>";

        } else {
            // Erfolgsfall
if(DEBUG)	echo "<p class='debug'>[POST-Categorie] Die neue Kategorie ist noch nicht in der Datenbank.</p>";

            // Kategorie in die Datenbank schreiben
            // Schritt 2 DB: SQL-Statement vorbereiten
            $statement = $pdo->prepare("
								INSERT 
								INTO categories (cat_name)
								VALUES (:ph_cat_name)
							");

            // Schritt 3 DB: SQL-Satement ausführen und ggf. Platzhalter füllen
            $statement->execute(array("ph_cat_name" => $newCat)) OR DIE($statement->errorInfo()[2]);

            // Schritt 4 DB: Daten weiterverarbeiten
            $succsesCat = $pdo->lastInsertId();

            if( !$succsesCat ) {
                // Fehlerfall
if(DEBUG)		echo "<p class='debug'>[POST-Categorie] Fehler beim Schreiben in die Datenbank.</p>";
                $dbMessageCat = "<h3 class='error'>Fehler beim Schreiben in die Datenbank. Bitte versuchen Sie es später erneut.<h3>";
            } else {
                // Erfolgsfall
if(DEBUG)		echo "<p class='debug'>[POST-Categorie] Neue Kategorie erfolgreich in die Datenbank geschrieben.</p>";
                $dbMessage = "<h3 class='success'>$newCat wurde erfolgreich angelegt.<h3>";
                $newCat = NULL;
            } // ENDE Datenbankoperation
        } // ENDE Prüfung, auf vorhandene Kategorie
    } // ENDE Abschließende Formularprüfung
} // ENDE Formularverarbeitung Neue Kategorie anlegen

/*********************************************************************************************************************************************/
/* Datenbank auselesen ***********************************************************************************************************************/
/*********************************************************************************************************************************************/

// Kategorien auslesen
            // Schritt 2 DB: SQL-Statement vorbereiten
            $statement = $pdo->prepare("
                                        SELECT * FROM categories
                                        ORDER BY cat_name
                                       ");

            // Schritt 3 DB: SQL-Satement ausführen und ggf. Platzhalter füllen
            $statement->execute() OR DIE($statement-errorInfo()[2]);

            // Schritt 4 DB: Daten weiterverarbeiten
            $catList = $statement->fetchAll();
            if( $catList ){
if(DEBUG)	    echo "<p class='debug'>Kategorien wurden ausgelesen.</p>";
}


?>


<!doctype html>
<html lang="de">
<head>
    <title>Portfolio Michael Flach</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="css/portfolio.css" type="text/css" media="screen"/>
</head>
<body>

<header>
    <div class="logo">
        <span><a href="index.php">mflach.de</a></span>
    </div>
    <a class="menu-trigger"><i class="fas fa-times"></i> &equiv;</a>
    <nav>
        <ul>
            <li><a href="index.php">Startseite</a></li>
            <?php if( $_SESSION["usr_id"] == 1 ): ?>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="createPost.php">Neuer Beitrag</a></li>
            <?php endif ?>
            <li><a href="?action=logout">Logout</a></li>
        </ul>
    </nav>
</header>
<main class="max-view">
    <aside>
        <h3>Suchen</h3>
        <input type="text">
        <h3 class="desktop">Kategorien</h3>
        <ul class="desktop">
            <?php foreach( $catList AS $key=>$value ): ?>
                <li><a href="?action=sort&byCat=<?= $value["cat_id"] ?>"><?= $value["cat_name"] ?></a></li>
            <?php endforeach ?>
        </ul>
    </aside>
    <article class="editor">
        <h3>Kategorie erstellen</h3>
        <?= $dbMessage ?>
        <form action="" method="POST">
            <input type="hidden" name="formsentNewCat">

            <input type="text" value="<?= $newCat ?>" name="newCat" placeholder="Name der neuen Kategorie"><br>
            <span class="error"><?= $errorNewCategory ?></span>
            <input type="submit" value="Kategorie anlegen">
        </form>

    </article>

</main>

<script src="scripts/portfolio.js"></script>
</body>
</html>