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
            <li><a href="index.php">Kontakt</a></li>
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
            <li><a href="portfolio.html">Alles zeigen</a></li>
            <li>Kategorie 1</li>
            <li>Kategorie 2</li>
            <li>Kategorie 3</li>
            <li>Kategorie 4</li>
        </ul>
    </aside>
    <article class="portfolio">
        <!-- START Beitrag -->
        <div class='post'>
            <figure>
                <img src="https://images.pexels.com/photos/1092671/pexels-photo-1092671.jpeg?auto=compress&cs=tinysrgb&h=750&w=1260" alt="#Beitragsbild">
                <figcaption>Beitrag 1</figcaption>
            </figure>
            <h4 class="disabled">Kategorie 1 | Kunde/Projekt</h4>
            <p class="disabled">Überall dieselbe alte Leier. Das Layout ist fertig, der Text lässt auf sich warten. Damit das Layout nun nicht nackt im Raume steht und sich klein und leer vorkommt, springe ich ein: der Blindtext.
            Überall dieselbe alte Leier. Das Layout ist fertig, der Text lässt auf sich warten. Damit das Layout nun nicht nackt im Raume steht und sich klein und leer vorkommt, springe ich ein: der Blindtext.</p>
        </div>
        <!-- ENDE Beitrag -->
        <div class='post'>
            <figure>
                <img src="https://images.pexels.com/photos/1092671/pexels-photo-1092671.jpeg?auto=compress&cs=tinysrgb&h=750&w=1260" alt="#Beitragsbild">
                <figcaption>Beitrag 1</figcaption>
            </figure>
            <h4 class="disabled">Kategorie 1 | Kunde/Projekt</h4>
            <p class="disabled">Überall dieselbe alte Leier. Das Layout ist fertig, der Text lässt auf sich warten. Damit das Layout nun nicht nackt im Raume steht und sich klein und leer vorkommt, springe ich ein: der Blindtext.            </p>
        </div>
        <div class='post big'>
            <figure>
                <!-- Bild --><img src="https://images.pexels.com/photos/1092671/pexels-photo-1092671.jpeg?auto=compress&cs=tinysrgb&h=750&w=1260" alt="#Beitragsbild">
                <!-- Beitragstitel -->	<figcaption>Beitrag 1</figcaption>
            </figure>
            <!-- Kategorie --><h4 class="disabled">Kategorie 1 | Kunde/Projekt</h4>
            <!-- Copytext --><p class="disabled">Überall dieselbe alte Leier. Das Layout ist fertig, der Text lässt auf sich warten. Damit das Layout nun nicht nackt im Raume steht und sich klein und leer vorkommt, springe ich ein: der Blindtext.            </p>
        </div>
        <div class='post'>
            <figure>
                <!-- Bild --><img src="https://images.pexels.com/photos/1092671/pexels-photo-1092671.jpeg?auto=compress&cs=tinysrgb&h=750&w=1260" alt="#Beitragsbild">
                <!-- Beitragstitel -->	<figcaption>Beitrag 1</figcaption>
            </figure>
            <!-- Kategorie --><h4 class="disabled">Kategorie 1 | Kunde/Projekt</h4>
            <!-- Copytext --><p class="disabled">Überall dieselbe alte Leier. Das Layout ist fertig, der Text lässt auf sich warten. Damit das Layout nun nicht nackt im Raume steht und sich klein und leer vorkommt, springe ich ein: der Blindtext.            </p>
        </div>
        <div class='post'>
            <figure>
                <!-- Bild --><img src="https://images.pexels.com/photos/1092671/pexels-photo-1092671.jpeg?auto=compress&cs=tinysrgb&h=750&w=1260" alt="#Beitragsbild">
                <!-- Beitragstitel -->	<figcaption>Beitrag 1</figcaption>
            </figure>
            <!-- Kategorie --><h4 class="disabled">Kategorie 1 | Kunde/Projekt</h4>
            <!-- Copytext --><p class="disabled">Überall dieselbe alte Leier. Das Layout ist fertig, der Text lässt auf sich warten. Damit das Layout nun nicht nackt im Raume steht und sich klein und leer vorkommt, springe ich ein: der Blindtext.            </p>
        </div>
        <div class='post'>
            <figure>
                <!-- Bild --><img src="https://images.pexels.com/photos/1092671/pexels-photo-1092671.jpeg?auto=compress&cs=tinysrgb&h=750&w=1260" alt="#Beitragsbild">
                <!-- Beitragstitel -->	<figcaption>Beitrag 1</figcaption>
            </figure>
            <!-- Kategorie --><h4 class="disabled">Kategorie 1 | Kunde/Projekt</h4>
            <!-- Copytext --><p class="disabled">Überall dieselbe alte Leier. Das Layout ist fertig, der Text lässt auf sich warten. Damit das Layout nun nicht nackt im Raume steht und sich klein und leer vorkommt, springe ich ein: der Blindtext.            </p>
        </div>
        <div class='post big'>
            <figure>
                <!-- Bild --><img src="https://images.pexels.com/photos/1092671/pexels-photo-1092671.jpeg?auto=compress&cs=tinysrgb&h=750&w=1260" alt="#Beitragsbild">
                <!-- Beitragstitel -->	<figcaption>Beitrag 1</figcaption>
            </figure>
            <!-- Kategorie --><h4 class="disabled">Kategorie 1 | Kunde/Projekt</h4>
            <!-- Copytext --><p class="disabled">Überall dieselbe alte Leier. Das Layout ist fertig, der Text lässt auf sich warten. Damit das Layout nun nicht nackt im Raume steht und sich klein und leer vorkommt, springe ich ein: der Blindtext.            </p>
        </div>
        <div class='post'>
            <figure>
                <!-- Bild --><img src="https://images.pexels.com/photos/1092671/pexels-photo-1092671.jpeg?auto=compress&cs=tinysrgb&h=750&w=1260" alt="#Beitragsbild">
                <!-- Beitragstitel -->	<figcaption>Beitrag 1</figcaption>
            </figure>
            <!-- Kategorie --><h4 class="disabled">Kategorie 1 | Kunde/Projekt</h4>
            <!-- Copytext --><p class="disabled">Überall dieselbe alte Leier. Das Layout ist fertig, der Text lässt auf sich warten. Damit das Layout nun nicht nackt im Raume steht und sich klein und leer vorkommt, springe ich ein: der Blindtext.            </p>
        </div>
        <div class='post'>
            <figure>
                <!-- Bild --><img src="https://images.pexels.com/photos/1092671/pexels-photo-1092671.jpeg?auto=compress&cs=tinysrgb&h=750&w=1260" alt="#Beitragsbild">
                <!-- Beitragstitel -->	<figcaption>Beitrag 1</figcaption>
            </figure>
            <!-- Kategorie --><h4 class="disabled">Kategorie 1 | Kunde/Projekt</h4>
            <!-- Copytext --><p class="disabled">Überall dieselbe alte Leier. Das Layout ist fertig, der Text lässt auf sich warten. Damit das Layout nun nicht nackt im Raume steht und sich klein und leer vorkommt, springe ich ein: der Blindtext.            </p>
        </div>
        <div class='post'>
            <figure>
                <!-- Bild --><img src="https://images.pexels.com/photos/1092671/pexels-photo-1092671.jpeg?auto=compress&cs=tinysrgb&h=750&w=1260" alt="#Beitragsbild">
                <!-- Beitragstitel -->	<figcaption>Beitrag 1</figcaption>
            </figure>
            <!-- Kategorie --><h4 class="disabled">Kategorie 1 | Kunde/Projekt</h4>
            <!-- Copytext --><p class="disabled">Überall dieselbe alte Leier. Das Layout ist fertig, der Text lässt auf sich warten. Damit das Layout nun nicht nackt im Raume steht und sich klein und leer vorkommt, springe ich ein: der Blindtext.            </p>
        </div>
    </article>

</main>

<script src="scripts/portfolio.js"></script>
</body>
</html>