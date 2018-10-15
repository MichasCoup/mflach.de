<?php

session_name("blog");
session_start();

if ( !( $_SESSION["usr_id"] == 1 ) ) {
    header("Location: portfolio.php");
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

$editPost = false;
$deletePost = false;

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
    } elseif ($action == "edit") {
        $editPost = true;
    }

}

/*********************************************************************************************************************************************/
/* Datenbank auselesen ***********************************************************************************************************************/
/*********************************************************************************************************************************************/

/* Kategorien auslesen ************************************************************************************************************/
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
if(DEBUG)			    echo "<p class='debug'>Kategorien wurden ausgelesen.</p>";
}

/* Beiträge auslesen ************************************************************************************************************/

                    // SQL-Statement welches alle Blogeinträge sucht
                    $sql = "SELECT blog_id, blog_headline, blog_image, blog_size, blog_content, blog_company, cat_name
                            FROM blogs 
                            INNER JOIN categories USING(cat_id) ";

                    $params = NULL;

                    $sql .= " ORDER BY blogs.blog_date DESC";

                    // Schritt 2 DB: SQL-Statment vorbereiten, Nutzung der Variable $sql
                    $statement = $pdo->prepare($sql);

                    // Schritt 3 DB: SQL-Satement ausführen, mit der Variable $params. Wenn keine Kategorie gesucht wird, ist Params=Null
                    $statement->execute($params) OR DIE($statement-errorInfo()[2]);

                    // Schritt 4 DB: Daten weiterverarbeiten, alle Beiträge werden in ein mehrdimensionales Array gespeichert

                    if( $deletePost == true ) {
                        $deletePost = $statement->rowCount();
if(DEBUG)			    echo "<p class='debug'> Betrag wurden gelöscht.</p>";
                        $dbMessageBlog = "<h3 class='success'> $deletePost Beitrag wurde gelöscht.<h3>";
                    } else {
                        $blogList = $statement->fetchAll();
if(DEBUG)			    echo "<p class='debug'> Beträge wurden ausgelesen.</p>";
                    }

if(DEBUG)   echo "<pre class='debug'>";
if(DEBUG)   print_r($blogList);
if(DEBUG)   echo "</pre>";
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
            <li><a href="portfolio.php">Portfolio</a></li>
            <li><a href="createPost.php">Neuer Beitrag</a></li>
            <li><a href="createCategory.php">Neue Kategorie</a></li>
            <li><a href="?action=logout">Logout</a></li>
        </ul>
    </nav>
</header>
<main>
    <aside>
        <h3>Suchen</h3>
        <input type="text" >
        <h3 class="desktop">Kategorien</h3>
        <ul class="desktop">
            <li><a href="portfolio.php">Alle</a></li>
            <?php foreach( $catList AS $key=>$value ): ?>
                <li><a href="?action=sort&byCat=<?= $value["cat_id"] ?>"><?= $value["cat_name"] ?></a></li>
            <?php endforeach ?>
        </ul>
    </aside>
    <article class="portfolio">
        <?php foreach ($blogList AS $key=>$value): ?>
                <div class='post <?= $value["blog_size"] ?>'>
                    <figure>
                        <img src="<?= $value["blog_image"] ?>" alt="">
                        <figcaption><?= $value["blog_headline"] ?></figcaption>
                    </figure>
                    <h4 class="disabled"><?= $value["cat_name"] ?> | <?= $value["blog_company"] ?></h4>
                    <p class="disabled"><?= nl2br($value["blog_content"]) ?>
                    <br><br><a href="createPost.php?action=edit&id=<?= $value["blog_id"]?>">Bearbeiten</a> | <a href="?action=delete&id=<?= $value["blog_id"]?>">Löschen</a></p>
                </div>
        <?php endforeach ?>
    </article>

</main>

<script src="scripts/portfolio.js"></script>
</body>
</html>