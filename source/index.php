<?php

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

            if( $errorGender || $errorEmail || $errorFirstname || $errorLastname ||$errormessage || $errorPhone || $errorTrue ){
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
                    $text = "<h2>Ihre Nachricht an mflach.de:</h2><br><p>".nl2br($message)."</p><br><br><p>Vielen Dank! Sobald es möglich ist, werden wir uns bei Ihnen melden.</p>";

                    mail($to, $subject, $text, $from);

                } else {
                    $dbMessage = "<h3 class='error'>Fehler beim verschicken.</h3>";
                }


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
<div <!--id="top"--></div>
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
   </ul>
  </nav>
 </header>
 <main>
  <article>
    <div id="start" class="slider disabled">
     <div class="img-holder"><img src="https://images.pexels.com/photos/1356304/pexels-photo-1356304.jpeg?auto=compress&cs=tinysrgb&h=750&w=1260" alt="#"></div>
     <div class="title">
      <h1>Michael Flach<br> Medienproduktion</h1>
     </div>
     <div class="description">
      <p>
       Er hörte leise Schritte hinter sich. Das bedeutete nichts Gutes. Wer würde ihm schon folgen, spät
       in der Nacht und dazu noch in dieser engen Gasse mitten im übel beleumundeten Hafenviertel? Gerade
       jetzt, wo er das Ding seines Lebens gedreht hatte und mit der Beute verschwinden wollte! Hatte einer
       seiner zahllosen Kollegen dieselbe Idee gehabt, ihn beobachtet und abgewartet, um ihn nun um die
       Früchte seiner Arbeit zu erleichtern? Oder gehörten die Schritte hinter ihm zu einem der unzähligen
       Gesetzeshüter dieser Stadt, und die stählerne Acht um seine Handgelenke würde gleich zuschnappen?
       Er konnte die Aufforderung stehen zu bleiben schon hören. Gehetzt sah er sich um. Plötzlich erblickte
       er den schmalen Durchgang.
      </p>
      <p>
       Blitzartig drehte er sich nach rechts und verschwand zwischen den beiden Gebäuden. Beinahe wäre er
       dabei über den umgestürzten Mülleimer gefallen, der mitten im Weg lag. Er versuchte, sich in der
       Dunkelheit seinen Weg zu ertasten und erstarrte: Anscheinend gab es keinen anderen Ausweg aus diesem
       kleinen Hof als den Durchgang, durch den er gekommen war. Die Schritte wurden lauter und lauter, er
       sah eine dunkle Gestalt um die Ecke biegen. Fieberhaft irrten seine Augen durch die nächtliche Dunkelheit
       und suchten einen Ausweg. War jetzt wirklich alles vorbei, waren alle Mühe und alle Vorbereitungen umsonst?
       Er presste sich ganz eng an die Wand hinter ihm und hoffte, der Verfolger würde ihn übersehen, als
       plötzlich neben ihm mit kaum wahrnehmbarem Quietschen eine Tür im nächtlichen Wind hin und her schwang.
      </p>
      <p>
       Könnte dieses der flehentlich herbeigesehnte Ausweg aus seinem Dilemma sein? Langsam bewegte er sich
       auf die offene Tür zu, immer dicht an die Mauer gepresst. Würde diese Tür seine Rettung werden?
       Er hörte leise Schritte hinter sich. Das bedeutete nichts Gutes. Wer würde ihm schon folgen, spät in
       der Nacht und dazu noch in dieser engen Gasse mitten im übel beleumundeten Hafenviertel? Gerade jetzt,
       wo er das Ding seines Lebens gedreht hatte und mit der Beute verschwinden wollte! Hatte einer seiner
       zahllosen Kollegen dieselbe Idee gehabt, ihn beobachtet und abgewartet, um ihn nun um die Früchte seiner
       Arbeit zu erleichtern? Oder gehörten die Schritte hinter ihm zu einem der unzähligen Gesetzeshüter
       dieser Stadt, und die stählerne Acht um seine Handgelenke würde gleich zuschnappen?
      </p>
     </div>
    </div>
    <div id="design" class="slider disabled">
     <div class="img-holder"><img src="https://images.pexels.com/photos/1372103/pexels-photo-1372103.jpeg?auto=compress&cs=tinysrgb&dpr=3&h=750&w=1260" alt="#"></div>
     <div class="title">
      <h1>Design<br> Medienproduktion</h1>
     </div>
     <div class="description">
      <p>
       Er hörte leise Schritte hinter sich. Das bedeutete nichts Gutes. Wer würde ihm schon folgen, spät
       in der Nacht und dazu noch in dieser engen Gasse mitten im übel beleumundeten Hafenviertel? Gerade
       jetzt, wo er das Ding seines Lebens gedreht hatte und mit der Beute verschwinden wollte! Hatte einer
       seiner zahllosen Kollegen dieselbe Idee gehabt, ihn beobachtet und abgewartet, um ihn nun um die
       Früchte seiner Arbeit zu erleichtern? Oder gehörten die Schritte hinter ihm zu einem der unzähligen
       Gesetzeshüter dieser Stadt, und die stählerne Acht um seine Handgelenke würde gleich zuschnappen?
       Er konnte die Aufforderung stehen zu bleiben schon hören. Gehetzt sah er sich um. Plötzlich erblickte
       er den schmalen Durchgang.
      </p>
      <p>
       Blitzartig drehte er sich nach rechts und verschwand zwischen den beiden Gebäuden. Beinahe wäre er
       dabei über den umgestürzten Mülleimer gefallen, der mitten im Weg lag. Er versuchte, sich in der
       Dunkelheit seinen Weg zu ertasten und erstarrte: Anscheinend gab es keinen anderen Ausweg aus diesem
       kleinen Hof als den Durchgang, durch den er gekommen war. Die Schritte wurden lauter und lauter, er
       sah eine dunkle Gestalt um die Ecke biegen. Fieberhaft irrten seine Augen durch die nächtliche Dunkelheit
       und suchten einen Ausweg. War jetzt wirklich alles vorbei, waren alle Mühe und alle Vorbereitungen umsonst?
       Er presste sich ganz eng an die Wand hinter ihm und hoffte, der Verfolger würde ihn übersehen, als
       plötzlich neben ihm mit kaum wahrnehmbarem Quietschen eine Tür im nächtlichen Wind hin und her schwang.
      </p>
      <p>
       Könnte dieses der flehentlich herbeigesehnte Ausweg aus seinem Dilemma sein? Langsam bewegte er sich
       auf die offene Tür zu, immer dicht an die Mauer gepresst. Würde diese Tür seine Rettung werden?
       Er hörte leise Schritte hinter sich. Das bedeutete nichts Gutes. Wer würde ihm schon folgen, spät in
       der Nacht und dazu noch in dieser engen Gasse mitten im übel beleumundeten Hafenviertel? Gerade jetzt,
       wo er das Ding seines Lebens gedreht hatte und mit der Beute verschwinden wollte! Hatte einer seiner
       zahllosen Kollegen dieselbe Idee gehabt, ihn beobachtet und abgewartet, um ihn nun um die Früchte seiner
       Arbeit zu erleichtern? Oder gehörten die Schritte hinter ihm zu einem der unzähligen Gesetzeshüter
       dieser Stadt, und die stählerne Acht um seine Handgelenke würde gleich zuschnappen?
      </p>
     </div>
    </div>
    <div id="coding" class="slider disabled">
     <div class="img-holder"><img src="https://images.pexels.com/photos/1366901/pexels-photo-1366901.jpeg?auto=compress&cs=tinysrgb&dpr=3&h=750&w=1260" alt="#"></div>
     <div class="title">
      <h1>Coding &<br>Web-Development </h1>
     </div>
     <div class="description">
      <p>
       Er hörte leise Schritte hinter sich. Das bedeutete nichts Gutes. Wer würde ihm schon folgen, spät
       in der Nacht und dazu noch in dieser engen Gasse mitten im übel beleumundeten Hafenviertel? Gerade
       jetzt, wo er das Ding seines Lebens gedreht hatte und mit der Beute verschwinden wollte! Hatte einer
       seiner zahllosen Kollegen dieselbe Idee gehabt, ihn beobachtet und abgewartet, um ihn nun um die
       Früchte seiner Arbeit zu erleichtern? Oder gehörten die Schritte hinter ihm zu einem der unzähligen
       Gesetzeshüter dieser Stadt, und die stählerne Acht um seine Handgelenke würde gleich zuschnappen?
       Er konnte die Aufforderung stehen zu bleiben schon hören. Gehetzt sah er sich um. Plötzlich erblickte
       er den schmalen Durchgang.
      </p>
      <p>
       Blitzartig drehte er sich nach rechts und verschwand zwischen den beiden Gebäuden. Beinahe wäre er
       dabei über den umgestürzten Mülleimer gefallen, der mitten im Weg lag. Er versuchte, sich in der
       Dunkelheit seinen Weg zu ertasten und erstarrte: Anscheinend gab es keinen anderen Ausweg aus diesem
       kleinen Hof als den Durchgang, durch den er gekommen war. Die Schritte wurden lauter und lauter, er
       sah eine dunkle Gestalt um die Ecke biegen. Fieberhaft irrten seine Augen durch die nächtliche Dunkelheit
       und suchten einen Ausweg. War jetzt wirklich alles vorbei, waren alle Mühe und alle Vorbereitungen umsonst?
       Er presste sich ganz eng an die Wand hinter ihm und hoffte, der Verfolger würde ihn übersehen, als
       plötzlich neben ihm mit kaum wahrnehmbarem Quietschen eine Tür im nächtlichen Wind hin und her schwang.
      </p>
      <p>
       Könnte dieses der flehentlich herbeigesehnte Ausweg aus seinem Dilemma sein? Langsam bewegte er sich
       auf die offene Tür zu, immer dicht an die Mauer gepresst. Würde diese Tür seine Rettung werden?
       Er hörte leise Schritte hinter sich. Das bedeutete nichts Gutes. Wer würde ihm schon folgen, spät in
       der Nacht und dazu noch in dieser engen Gasse mitten im übel beleumundeten Hafenviertel? Gerade jetzt,
       wo er das Ding seines Lebens gedreht hatte und mit der Beute verschwinden wollte! Hatte einer seiner
       zahllosen Kollegen dieselbe Idee gehabt, ihn beobachtet und abgewartet, um ihn nun um die Früchte seiner
       Arbeit zu erleichtern? Oder gehörten die Schritte hinter ihm zu einem der unzähligen Gesetzeshüter
       dieser Stadt, und die stählerne Acht um seine Handgelenke würde gleich zuschnappen?
      </p>
     </div>
    </div>
    <div id="about" class="slider disabled">
     <div class="img-holder"><img src="https://images.pexels.com/photos/1002106/pexels-photo-1002106.jpeg?auto=compress&cs=tinysrgb&dpr=3&h=750&w=1260" alt="#"></div>
     <div class="title">
      <h1>About: mflach.de</h1>
     </div>
     <div class="description">
      <p>
       Er hörte leise Schritte hinter sich. Das bedeutete nichts Gutes. Wer würde ihm schon folgen, spät
       in der Nacht und dazu noch in dieser engen Gasse mitten im übel beleumundeten Hafenviertel? Gerade
       jetzt, wo er das Ding seines Lebens gedreht hatte und mit der Beute verschwinden wollte! Hatte einer
       seiner zahllosen Kollegen dieselbe Idee gehabt, ihn beobachtet und abgewartet, um ihn nun um die
       Früchte seiner Arbeit zu erleichtern? Oder gehörten die Schritte hinter ihm zu einem der unzähligen
       Gesetzeshüter dieser Stadt, und die stählerne Acht um seine Handgelenke würde gleich zuschnappen?
       Er konnte die Aufforderung stehen zu bleiben schon hören. Gehetzt sah er sich um. Plötzlich erblickte
       er den schmalen Durchgang.
      </p>
      <p>
       Blitzartig drehte er sich nach rechts und verschwand zwischen den beiden Gebäuden. Beinahe wäre er
       dabei über den umgestürzten Mülleimer gefallen, der mitten im Weg lag. Er versuchte, sich in der
       Dunkelheit seinen Weg zu ertasten und erstarrte: Anscheinend gab es keinen anderen Ausweg aus diesem
       kleinen Hof als den Durchgang, durch den er gekommen war. Die Schritte wurden lauter und lauter, er
       sah eine dunkle Gestalt um die Ecke biegen. Fieberhaft irrten seine Augen durch die nächtliche Dunkelheit
       und suchten einen Ausweg. War jetzt wirklich alles vorbei, waren alle Mühe und alle Vorbereitungen umsonst?
       Er presste sich ganz eng an die Wand hinter ihm und hoffte, der Verfolger würde ihn übersehen, als
       plötzlich neben ihm mit kaum wahrnehmbarem Quietschen eine Tür im nächtlichen Wind hin und her schwang.
      </p>
      <p>
       Könnte dieses der flehentlich herbeigesehnte Ausweg aus seinem Dilemma sein? Langsam bewegte er sich
       auf die offene Tür zu, immer dicht an die Mauer gepresst. Würde diese Tür seine Rettung werden?
       Er hörte leise Schritte hinter sich. Das bedeutete nichts Gutes. Wer würde ihm schon folgen, spät in
       der Nacht und dazu noch in dieser engen Gasse mitten im übel beleumundeten Hafenviertel? Gerade jetzt,
       wo er das Ding seines Lebens gedreht hatte und mit der Beute verschwinden wollte! Hatte einer seiner
       zahllosen Kollegen dieselbe Idee gehabt, ihn beobachtet und abgewartet, um ihn nun um die Früchte seiner
       Arbeit zu erleichtern? Oder gehörten die Schritte hinter ihm zu einem der unzähligen Gesetzeshüter
       dieser Stadt, und die stählerne Acht um seine Handgelenke würde gleich zuschnappen?
      </p>
     </div>
    </div>
    <div id="contact" class="slider active">
     <div class="img-holder"><img src="https://images.pexels.com/photos/325185/pexels-photo-325185.jpeg?auto=compress&cs=tinysrgb&dpr=3&h=750&w=1260" alt="#"></div>
     <div class="title">
      <h1>Jetzt kontaktieren</h1>
     </div>
    <div class="description">
    <?= $dbMessage ?>
    <?php if(!$dbMessage): ?>
      <form action="" method="post" novalidate>

       <span><input type="radio" name="gender" value="male" id="male" <?php if($gender == "male") echo "checked" ?>><label class="genders" for="male">Herr</label></span>
       <span><input type="radio" name="gender" value="female" id="female" <?php if($gender == "female") echo "checked" ?>><label class="genders" for="female">Frau</label></span>
       <span><input type="radio" name="gender" value="other" id="other" <?php if($gender == "other") echo "checked" ?>><label class="genders" for="other">Andere</label></span>
          <span class="error"><?= $errorGender ?></span><br>

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
              <span class="label">E-Mail:</span><span class="error"><?= $errorEmail ?></span><br>
          </label>

          <label for="phone" class="inp">
              <input type="text" name="phone" id="phone" value="<?= $phone ?>" placeholder="&nbsp;" >
              <span class="label">Telefon:</span><span class="error"><?= $errorPhone ?></span><br>
          </label>

          <label for="message" class="inp">
              <textarea name="message" id="message" placeholder="Ihre Nachricht" rows="4" required><?= $message ?></textarea>
              <span class="error"><?= $errorMessage ?></span>
          </label>

          <input type="submit" name="formsent" value="Nachricht abschicken">
          <input type="checkbox" id="true" name="true" value="true" <?php if($true == "true") echo "checked" ?> >
          <label for="true">Ich stimme der Datenschutzreglung hiermit zu. </label><span class="error"><span class="error"><?= $errorTrue ?></span>
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
