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

$dbMessage          = NULL;

$editPost           = false;

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
    } elseif ( $action == "edit") {
        $editPost = true;
        $post = cleanString($_GET["id"]);
    }
}



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

// Kategorien auslesen
            if($editPost){

                $statement = $pdo->prepare("
                    SELECT blog_headline, 
                           blog_image, 
                           blog_size, 
                           blog_content, 
                           blog_company, 
                           cat_name,
                           cat_id
                    FROM blogs 
                    INNER JOIN categories USING(cat_id) 
                    WHERE blog_id = :ph_blog_id
                ");

                $statement->execute(array(
                    "ph_blog_id" => $post
                )) OR DIE($statement-errorInfo()[2]);

                $editPost = $statement->fetch();
            }

/*********************************************************************************************************************************************/
/* Formularverarbeitung: Neuen Beitrag anlegen ***********************************************************************************************/
/*********************************************************************************************************************************************/

if( isset($_POST["formsentNewPost"]) ){
    if(DEBUG)			echo "<p class='debug'>[POST-Blog] Formular wurde abgeschickt für einen neuen Beitrag.</p>";

    // Schritt 2 FORM: Werte auslesen, entschärfen, DEBUG-Ausgabe
    $headline		= cleanString ($_POST["headline"] );
    $content		= cleanString ($_POST["content"] );
    $size		    = cleanString ($_POST["size"] );
    $company	    = cleanString ($_POST["company"] );
    $category		= cleanString ($_POST["category"] );
    if(DEBUG)			echo "<p class='debug'>[POST-Blog] \$headline : $headline</p>";
    if(DEBUG)			echo "<p class='debug'>[POST-Blog] \$content : $content</p>";
    if(DEBUG)			echo "<p class='debug'>[POST-Blog] \$size : $size</p>";
    if(DEBUG)			echo "<p class='debug'>[POST-Blog] \$category : $category</p>";
    if(DEBUG)			echo "<p class='debug'>[POST-Blog] \$company : $company</p>";

    // Schritt 3 FORM: Optional: Werte validieren
    $errorHeadline 		= checkInputString($headline, 6);
    $errorContent 		= checkInputString($content, 8,5000);
    $errorCategory 		= checkInputString($category, 1);
    $errorCompany 		= checkInputString($company, 2);
    $errorSize	        = checkInputString($size, 3, 5);


    // Abschließende Formularprüfung (ist das Formular insgesamt fehlerfrei?)
    if( $errorHeadline || $errorContent || $errorCategory || $errorCompany ) {
        // Fehlerfall
    } else {
        // Erfolgsfall
if(DEBUG)	echo "<p class='debug'>[POST-Blog] Formular ist fehlerfrei.</p>";

/* Bild Upload *******************************************************************************************************************************/

        //Beitrag wird editiert.
        if ($editPost) {
            if ( !$_FILES["image"]["tmp_name"]){
if(DEBUG)	echo "<p class='debug'>[POST-Blog] Bild wird nicht geändert.</p>";

            }else {
                // Erfolgsfall Bilddatei gefunden

                if(DEBUG)	echo "<p class='debug'>[POST-Blog] Bild für Upload-gefunden.</p>";
                $imageUploadReturnArray = imageUpload($_FILES["image"]);

                if( $imageUploadReturnArray["imageError"] ) {
                    // Fehler beim Upload

                    if(DEBUG)		echo "<p class='debug'>[FEHLER] $imageUploadReturnArray[imageError]</p>";
                    $errorImageUpload = $imageUploadReturnArray["imageError"];

                } else {
                    // Erfolgsfall

                    if(DEBUG)		echo "<p class='debug'>Bild wurde erfolgreich auf dem Server geladen.</p>";
                    // neunen Bildpfad speichern
                    $imagePath = $imageUploadReturnArray["imagePath"];
                    $editImage = true;
                } // ENDE Bildpfad speichern
            } // ENDE Bilddatei wurde ausgewählt

        } else {
            //Neuer Beitrag wird erstellt

            if (!$_FILES["image"]["tmp_name"]) {

                if (DEBUG) echo "<p class='debug'>[POST-Blog] Es wurde kein Bild für den Upload gefunden.</p>";
                $errorImageUpload = "Es wurde kein Bild für den Upload gefunden.";

            } else {
                // Erfolgsfall Bilddatei gefunden

                if (DEBUG) echo "<p class='debug'>[POST-Blog] Bild für Upload-gefunden.</p>";
                $imageUploadReturnArray = imageUpload($_FILES["image"]);

                if ($imageUploadReturnArray["imageError"]) {
                    // Fehler beim Upload

                    if (DEBUG) echo "<p class='debug'>[FEHLER] $imageUploadReturnArray[imageError]</p>";
                    $errorImageUpload = $imageUploadReturnArray["imageError"];

                } else {
                    // Erfolgsfall

                    if (DEBUG) echo "<p class='debug'>Bild wurde erfolgreich auf dem Server geladen.</p>";
                    // neunen Bildpfad speichern
                    $imagePath = $imageUploadReturnArray["imagePath"];
                } // ENDE Bildpfad speichern
            } // ENDE Bilddatei wurde ausgewählt
        }
        // Schritt 4 FORM: Daten Weiterverarbeiten


/* Datenbankoperation  Beitrag schreiben*/
/*********************************************************************************************************************************************/


        // Schreiben in die Datenbank ...
        if ( $errorImageUpload )  {
            // Fehlerfall beim ImageUpload, Datenbankschreiben abgebrochen
if(DEBUG)	echo "<p class='debug'>[FEHLER] Es ist ein Fehler beim wurde ein Upload aufgetreten.</p>";
            $errorImageUpload = "Bitte das Bild erneut auswählen und 'speichern' drücken.";

        } else {
            // Erfolgsfall

            // Schritt 2 DB: SQL-Statement vorbereiten

            $formSql = "INSERT 
                        INTO blogs ( blog_headline, blog_content, blog_image, blog_size, blog_company, cat_id) 
                        VALUES (:ph_blog_headline, :ph_blog_content, :ph_blog_image, :ph_blog_size, :ph_blog_company, :ph_cat_id)";

            if( $editPost ) {
                $formSql = "UPDATE blogs
							SET blog_headline	= :ph_blog_headline, 
								blog_content	= :ph_blog_content, 
								blog_size		= :ph_blog_size, 
								blog_company    = :ph_blog_company,
								cat_id			= :ph_cat_id
							WHERE blog_id = :ph_blog_id";
            };
            if( $editImage ) {
                $formSql = "UPDATE blogs
							SET blog_headline	= :ph_blog_headline, 
								blog_content	= :ph_blog_content, 
								blog_image		= :ph_blog_image, 
								blog_size		= :ph_blog_size, 
								blog_company    = :ph_blog_company,
								cat_id			= :ph_cat_id
							WHERE blog_id = :ph_blog_id";
            };

            $statement = $pdo->prepare($formSql);

            // Schritt 3 DB: SQL-Satement ausführen und ggf. Platzhalter füllen
            $formParams = array("ph_blog_headline" => $headline,
                                "ph_blog_content" => $content,
                                "ph_blog_image" => $imagePath,
                                "ph_blog_size" => $size,
                                "ph_cat_id" => $category,
                                "ph_blog_company" => $company
            );

            if( $editPost ) {
                $formParams = array("ph_blog_headline" => $headline,
                                    "ph_blog_content" => $content,
                                    "ph_blog_size" => $size,
                                    "ph_cat_id" => $category,
                                    "ph_blog_company" => $company,
                                    "ph_blog_id" => $post
                );
            };
            if ($editImage){
                $formParams = array("ph_blog_headline" => $headline,
                    "ph_blog_content" => $content,
                    "ph_blog_size" => $size,
                    "ph_blog_image" => $imagePath,
                    "ph_cat_id" => $category,
                    "ph_blog_company" => $company,
                    "ph_blog_id" => $post
                );
            };

            $statement->execute($formParams) OR DIE($statement->errorInfo()[2]);

            // Schritt 4 DB: Daten weiterverarbeiten
            $successBlog = $pdo->lastInsertID();
            if( $editPost ) $successBlog = $statement->rowCount();

            if( !$successBlog){
                // Fehlerfall
if(DEBUG)		echo "<p class='debug'>[POST-Blog] Fehler beim Schreiben in die Datenbank.</p>";
                $dbMessage = "<h3 class='error'>Fehler beim Schreiben in die Datenbank. Bitte versuchen Sie es erneut.<h3>";
            } else {
                // Erfolgsfall
if(DEBUG)		echo "<p class='debug'>[POST-Blog] Ein Beitrag wurde in die Datenbank geschrieben.</p>";
                $dbMessage = "<h3 class='success'>Beitrag: <q>$headline</q> wurde erfolgreich gespeichert.<h3>";
                if( $action == "edit")	$dbMessage = "<h3 class='success'>Beitrag: <q>$headline</q> wurde bearbeitet.<h3>";

                // Formular leeren
                $headline = NULL;
                $content = NULL;
                $company = NULL;
                $size = NULL;
                $category = NULL;
            } // ENDE Rückmeldung Datenbankoperation
        } // ENDE Datenbank schreiben
    } // ENDE Abschließende Prüfung
} // ENDE Formularverarbeitung Neuen Beitrag anlegen



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
                <li><a href="createCategory.php">Neue Kategorie</a></li>
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
        <?php if($editPost): ?>
            <h3>Beitrag bearbeiten</h3>
            <?php else: ?>
        <h3>Beitrag erstellen</h3>
        <?php endif ?>

        <?= $dbMessage ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="formsentNewPost">

            <input type="text"
                   <?php if($editPost) ?> value="<?= $editPost["blog_headline"] ?>"
                   name="headline" placeholder="Titel des Beitrages ..."><br>
            <span class="error"><?= $errorHeadline ?></span>

            <textarea name="content" placeholder="Inhalt des Beitrages ..."><?php if($editPost): ?><?= $editPost["blog_content"] ?> <?php endif ?>
            </textarea><br>
            <span class="error"><?= $errorContent ?></span>


            <select name="category" title="category">

                <option value=''>Kategorie wählen</option>
                <option value='' disabled>- - -</option>

                <?php foreach( $catList AS $key=>$value ): ?>
                    <?php if( $editPost ): ?>

                        <?php if( $catList[array_search($editPost[0]["cat_id"], array_column($catList, "cat_id"))]["cat_id"] == $value["cat_id"] ) {$selcted = "selected";}else {$selcted = NULL;} ?>
                        <option value="<?= $value["cat_id"] ?>"
                            <?= $selcted ?>><?= $value["cat_name"] ?></option>

                    <?php elseif( !$editPost ): ?>

                        <?php if( $category && $catList[array_search($category, array_column($catList, "cat_id"))]["cat_name"] == $value["cat_name"] ) {$selcted = "selected";}else {$selcted = NULL;} ?>
                        <option value="<?= $value["cat_id"] ?>"
                            <?= $selcted ?>><?= $value["cat_name"] ?></option>

                    <?php endif ?>
                <?php endforeach ?>

            </select><br>
            <span class="error"><?= $errorCategory ?></span>

            <input type="text"
                <?php if($editPost) ?> value="<?= $editPost["blog_company"] ?>"
                name="company" placeholder="Kunde/Firma ..."><br>
            <span class="error"><?= $errorCompany ?></span>


            <input type="file" name="image"><br>
            <span class="error"><?= $errorImageUpload ?></span>

            <select name="size" title="size">
                <option value="small" <?php if( $editPost && $editPost["blog_size"] == "small" ) echo "selected" ?>>Kleiner Beitrag</option>
                <option value="big" <?php if( $editPost && $editPost["blog_size"] == "big" ) echo "selected" ?>>Großer Beitrag</option>
            </select><br>
            <span class="error"><?= $errorSize ?></span>

            <input type="submit" value="Beitrag speichern">
        </form>

    </article>

</main>

<script src="scripts/portfolio.js"></script>
</body>
</html>