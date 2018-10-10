<?php

session_name("blog");
session_start();


/*********************************************************************************************************************************************/
/* Configuartion *****************************************************************************************************************************/
/*********************************************************************************************************************************************/

require_once("include/config.inc.php");
require_once("include/form.inc.php");
require_once("include/db.inc.php");

/*********************************************************************************************************************************************/
/* Variablen Initialisieren ******************************************************************************************************************/
/*********************************************************************************************************************************************/

    $gender     = NULL;
    $firstname  = NULL;
    $lastname   = NULL;
    $email      = NULL;
    $phone      = NULL;
    $message    = NULL;
    $true       = NULL;

    $errorGender    = NULL;
    $errorFirstname = NULL;
    $errorLastname  = NULL;
    $errorEmail     = NULL;
    $errorPhone     = NULL;
    $errorMessage   = NULL;
    $errorTrue      = NULL;


    $dbMessage = NULL;
    $hideForm  = false;

/*********************************************************************************************************************************************/
/* Formularverarbeitung: Kontaktformular **********************************************************************************************/
/*********************************************************************************************************************************************/

if( isset( $_POST["formsent"])) {
if(DEBUG)   echo "<p class='debug'> Kontaktformular wurde abgeschickt</p>";

            $gender     = cleanString( $_POST["gender"]);
            $firstname  = cleanString( $_POST["firstname"]);
            $lastname   = cleanString( $_POST["lastname"]);
            $email      = cleanString( $_POST["email"]);
            $phone      = cleanString( $_POST["phone"]);
            $message    = cleanString( $_POST["message"]);
            $true       = cleanString( $_POST["true"]);

            $errorGender = checkInputString($gender);
            $errorFirstname = checkInputString($firstname);
            $errorLastname = checkInputString($lastname);
            $errorEmail = checkEmail($email);
            $errorPhone = checkInputString($phone, 6, 15);
            $errorMessage = checkInputString($message, 50, 5000);
            $errorTrue = checkInputString($true);

            if( $errorEmail || $errorFirstname || $errorLastname || $errorMessage || $errorPhone || $errorTrue ){
                // Fehlerfall
if(DEBUG)       echo "<p class='debug'> Kontaktformular wurde fehlerhaft ausgefüllt</p>";
            } else {
                // Erfolgsfall
if(DEBUG)       echo "<p class='debug'> Kontaktformular wurde korrekt abgeschickt</p>";



                // Mail to me
                $to = "mail@mflach.de";
                $subject = "Neue Nachricht an mflach.de";
                $from = "From: $firstname $lastname <$email>\r\n";
                $from .= "Reply-To: $email\r\n";
                $from .= "Content-Type: text/html\r\n";
                $text = "   <h3>$gender $firstname $lastname hat geschrieben:</h3><p>".nl2br($message)."</p><br><p>Email: $email <br> Telefon: $phone</p>";

                if( mail($to, $subject, $text, $from) ){
                    $dbMessage = "<h3 class='success'>Ihre Nachricht wurde verschickt!</h3>";
                    $hideform = true;
                    // Mail to contact
                    $to = "$email";
                    $subject = "Ihre Nachricht an mflach.de";
                    $from = "From: Kontakt mflach.de <no-reply@mflach.de>\r\n";
                    $from .= "Reply-To: no-reply@mflach.de\r\n";
                    $from .= "Content-Type: text/html\r\n";
                    $text = "<h2>Ihre Nachricht an mflach.de:</h2><br><q>".nl2br($message)."</q><br><br><p>Vielen Dank! Sobald es möglich ist, werden wir uns bei Ihnen melden.</p>";

                    mail($to, $subject, $text, $from);

                } else {
                    $dbMessage = "<h3 class='error'>Fehler beim verschicken.</h3>";
                }


            }

}
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
        header("Location: index.php");
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
<link rel="stylesheet" href="css/style.css" type="text/css" media="screen"/>
</head>
<body>
 <header>
  <div class="logo">
   <span>mflach.de</span>
  </div>
  <a class="menu-trigger"><i class="fas fa-times"></i> &equiv;</a>
  <nav>
   <ul>
    <li class="start">Start</a></li>
    <li class="design">Design</a></li>
    <li class="coding">Coding</a></li>
    <li class="about">About</a></li>
    <li class="contact">Kontakt</a></li>
       <?php if( isset($_SESSION["usr_id"]) ): ?>
           <li><a href="portfolio.php">Portfolio</a></li>
           <li><a href="?action=logout">Logout</a></li>
       <?php endif ?>
   </ul>
  </nav>
 </header>
 <main>
  <article>
    <div id="start" class="slider active">
     <div class="img-holder"><img src="https://images.pexels.com/photos/1356304/pexels-photo-1356304.jpeg?auto=compress&cs=tinysrgb&h=750&w=1260" alt="#"></div>
     <div class="title">
      <h1>Michael Flach<br> Medienproduktion</h1>
     </div>
     <div class="description">
      <p>
          Guten Tag, mein Name ist Michael Flach. Unabhängig von den Umständen, welche Sie zu meiner Website geführt haben, können Sie hier meine Fähigkeiten zu Marketing, Entwicklung und Produktion entdecken.
      </p>
      <p>
          Eine Marke zu repräsentieren, gehört zu meiner Leidenschaft. Neben den Produkten und Dienstleistungen einer Marke ist ein definierter, kundennaher Auftritt erforderlich. Mit einem klar abgestimmten Konzept zur Kundenansprache abseits des eigenen Vertriebes verbessert sich die Kundenzufriedenheit und der Umsatz.
      </p>
      <p>
          Die Erstellung von klaren Konzeptionen mit eindeutigen Zielen ist für mich wichtiger Gegenstand des Produktmarketings. Selbstverständlich arbeite ich an der Zielerreichung ganzheitlich mit.
      </p>
      <p>
       Mein Leistungen setzen sich somit wie folgt zusammen:
         <ul>
             <li>Definierte Ziele und Erwartungen</li>
             <li>Abgestimmte Konzepte</li>
             <li>Umfassende Projektbetreuung</li>
         </ul>
      </p>
      <p>
          Nehmen Sie sich gerne einen Moment Zeit und informieren Sie sich über meine Arbeit.
      </p>
      <p>Mit freundlichen Grüßen<br>Michael Flach</p>
     </div>
    </div>
    <div id="design" class="slider disabled">
     <div class="img-holder"><img src="https://images.pexels.com/photos/1372103/pexels-photo-1372103.jpeg?auto=compress&cs=tinysrgb&dpr=3&h=750&w=1260" alt="#"></div>
     <div class="title">
      <h1>Grafik & Design</h1>
     </div>
     <div class="description">
         <h3>Corporate Design</h3>
         <p>
             Ein Schwerpunkt meiner Arbeit ist die Entwicklung von Designrichtlinien und ihre Anwendung auf unterschiedliche Formate. Durch eine optimale Passung auf die gegebenen Anforderungen wirkt ein Markenauftritt stimmig und behält seine Botschaft bei. Dank eines gut geplanten Corporate Designs können immer neue kreative Ideen entstehen und verwirklicht werden.
         </p>
         <h3>Markenadaption</h3>
         <p>
             Ob ein 18/1-Großplakat oder eine Visitenkarte, der ganzheitliche Markenauftritt kann nur unter Berücksichtigung von Corporate-Design-Richtlinien gelingen. Designelemente und Layouts müssen an unterschiedliche Formate und Funktionen angepasst werden. Das Einhalten einer klaren Linie ermöglicht den Erfolg einer Marke und ihre Wiedererkennbarkeit.
         </p>
         <h3>Werbemittel</h3>
         <p>
             in riesiges Plakat oder doch einen Flyer? Vielleicht auch eine Kombination! Marken wollen ein Maximum an potenziellen Kunden ansprechen. Um dies zu ermöglichen, braucht es ein Konzept und den richtigen Umgang mit Werbemitteln.
         </p>
         <h3>Satz- Reinzeichnung</h3>
          <p>
              Jedes Produkt, vom Flyer bis zum Geschäftsbericht, braucht einen logischen Aufbau. Um diesen Aufbau zu garantieren, wird eine klare und durchdachte Reinzeichnung benötigt. Der Entwurf einer solchen Reinzeichnung führt das Beherrschen eines Handwerks mit sich, dem jobübergreifend alle Teammitglieder vertrauen können. Das Arbeiten an alten oder fremden Dateien wird dadurch zusätzlich einfach und verständlich.
          </p>
         <h3>Bildbearbeitung</h3>
         <p>
             Jedes Bild besitzt eine Geschichte, nur erkennt nicht jeder Betrachter diese. Eine Bildbearbeitung muss sowohl den Charakter des Bildes verstärken als auch Kundenwünsche berücksichtigen. Farbklimata erzeugende Farben müssen in einigen Bildern nachbearbeitet werden, um eine Brillanz zu schaffen, die fasziniert. Unreinheiten oder Makel müssen retuschiert werden, damit sie nicht stören. Eine Bildmontage erlaubt uns, zu träumen.
         </p>
     </div>
    </div>
    <div id="coding" class="slider disabled">
     <div class="img-holder"><img src="https://images.pexels.com/photos/1366901/pexels-photo-1366901.jpeg?auto=compress&cs=tinysrgb&dpr=3&h=750&w=1260" alt="#"></div>
     <div class="title">
      <h1>Coding &<br>Web-Development </h1>
     </div>
     <div class="description">
         <h3>Frontend Webentwicklung</h3>
         <p>mit HTML, CSS und JavaScript</p>
         <h3>Backend Entwicklung</h3>
         <p>mit PHP oder node.js und Datenbanken</p>
         <h3>Web Applications</h3>
         <p>mit Angular.js und React.js</p>
     </div>
    </div>
    <div id="about" class="slider disabled">
     <div class="img-holder"><img src="https://images.pexels.com/photos/1002106/pexels-photo-1002106.jpeg?auto=compress&cs=tinysrgb&dpr=3&h=750&w=1260" alt="#"></div>
     <div class="title">
      <h1>About: mflach.de</h1>
     </div>
     <div class="description">
         <h2><b>Kurz</b></h2>
         <br>
         <h4><b>Name:</b> Michael Flach</h4>
         <h4><b>Geboren am:</b> 17.04.1990</h4>
         <h4><b>Wohnhaft in:</b> Berlin</h4>
         <br>
         <h2><b>Lang</b></h2>
         <br>
         <h4>Am Anfang …</h4>
         <p>… bestand der Wunsch nach einem Beruf, in dem ich kreative Lösungen finden kann. Ich habe mich für eine Ausbildung zum Mediengestalter für Gestaltung und Technik entschieden und diese erfolgreich in der Markenagentur MetaDesign abgeschlossen. Dank der Einführung in die Welt der Marken habe ich die Grundlagen von Corporate Design und der Corporate Identity gelernt und perfektioniert. In der Ausbildung wurde mir von erfahrenen Mitarbeitern nicht nur die saubere technische Umsetzung inklusive historischer Bedeutung von CD und CI beigebracht, sondern auch die Prinzipien der Gestaltung und deren Grundlage. Ich durfte Projekte vom Datenblatt inklusive Erstentwurf über die Produktion bis hin zur vollständigen Umsetzung selbstständig bearbeiten, begleiten und mein organisatorisches Talent beweisen.</p>
         <h4>Neben der Einführung …</h4>
         <p>… in die Produktion und Kundenbetreuung wollte ich es genauer wissen. Mein Weg führte mich in die Welt der Druckereien, wo ich mein Wissen vertiefen konnte. Auf der Seite der Druckdienstleister konnte ich neue Erfahrungen zur Kundenführung gewinnen und mein Wissen über die Techniken der Branche weiter vertiefen. Das Erstellen von Angebotsanfragen fällt mir seither leicht und mein sicherer Umgang mit externen Dienstleistern ist bestimmend und zielführend.</p>
         <h4>Als Möglichkeit, …</h4>
         <p>… mein Wissen und meine Fähigkeiten einzusetzen, suche ich Firmen, die auf gutes Marketing setzen und intelligente Lösungen schätzen. Mich interessiert die Kommunikation einer Firma nach innen und außen. Die Erstellung von internen Gestaltungen, Firmenpräsentationen oder Mitarbeitergeschenken fällt mir gleichermaßen leicht wie Anzeigen, Messestände oder Werbemittel. Abrundend dazu suche ich nach Lösungen, die Prozesse optimieren und neues Potential freisetzen. </p>
         <h4>Viel Spaß …</h4>
         <p>… habe ich vor allem bei der Erstellung von Lösungen im Bereich User Experience und User Interface. Obgleich Formularmasken oder Lehrmittelherstellung, ich bediene mich sowohl klarer Gestaltungen zur optimalen Zielführung als auch des gewissen Maßes an Detail für eine hohe Wiedererkennung des Corporate Designs.</p>
         <h4>Noch Ergänzend …</h4>
         <p>… ist hinzuzufügen, dass ich mich schon viele Jahre für Webentwicklung interessiere und private Projekte verfolge. Um dies zu untermalen, habe ich mir meine Fähigkeiten zertifizieren lassen und bin nicht abgeneigt diese auch im Beruf anzuwenden.</p>
     </div>
    </div>
    <div id="contact" class="slider disabled">
     <div class="img-holder"><img src="https://images.pexels.com/photos/325185/pexels-photo-325185.jpeg?auto=compress&cs=tinysrgb&dpr=3&h=750&w=1260" alt="#"></div>
     <div class="title">
      <h1>Jetzt kontaktieren</h1>
     </div>
    <div class="description">
    <?= $dbMessage ?>
    <?php if(!$hideform): ?>
      <form action="" method="post" novalidate>

       <span><input type="radio" name="gender" value="male" id="male" <?php if($gender == "male") echo "checked" ?>><label class="genders" for="male">Herr</label></span>
       <span><input type="radio" name="gender" value="female" id="female" <?php if($gender == "female") echo "checked" ?>><label class="genders" for="female">Frau</label></span>
       <span><input type="radio" name="gender" value="other" id="other" <?php if($gender == "other") echo "checked" ?>><label class="genders" for="other">Andere</label></span>
          <br>

          <label for="firstname" class="inp">
              <input type="text" name="firstname" id="firstname" value="<?= $firstname ?>" placeholder="&nbsp;" >
              <span class="label">Vorname:</span><span class="error"><?= $errorFirstname ?></span><br>
          </label>

          <label for="lastname" class="inp">
              <input type="text" name="lastname" id="lastname" value="<?= $lastname ?>" placeholder="&nbsp;" >
              <span class="label">Nachname:</span><span class="error"><?= $errorLastname ?></span><br>
          </label>

          <label for="email" class="inp">
              <input type="text" name="email" id="email" value="<?= $email ?>" placeholder="&nbsp;" >
              <span class="label">E&#x2011;Mail:</span><span class="error"><?= $errorEmail ?></span><br>
          </label>

          <label for="phone" class="inp">
              <input type="text" name="phone" id="phone" value="<?= $phone ?>" placeholder="&nbsp;" >
              <span class="label">Telefon:</span><span class="error"><?= $errorPhone ?></span><br>
          </label>

          <label for="message" class="inp">
              <textarea name="message" id="message" placeholder="Ihre Nachricht" rows="4" required><?= $message ?></textarea>
          </label>
          <span class="error"><?= $errorMessage ?></span>
          <br>

          <input type="submit" name="formsent" value="Nachricht abschicken">
          <input type="checkbox" id="true" name="true" value="true" <?php if($true == "true") echo "checked" ?> >
          <label for="true">Ich stimme der <a href="datenschutz.html">Datenschutzreglung</a> hiermit zu. </label><span class="error"><?= $errorTrue ?></span>
      </form>
    <?php endif ?>
     </div>
    </div>
  </article>
 </main>
 <footer>
  <ul>
   <li><a href="impressum.html">Impressum</a></li>
   <li><a href="login.php">Portfolio</a></li>
  </ul>
 </footer>
<script src="scripts/script.js"></script>
</body>
</html>
