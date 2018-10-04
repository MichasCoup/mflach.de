<?php
	/*************************************/
	/*********** CONFIGURATION ***********/
	/*************************************/

	require_once("include/config.inc.php");
	require_once("include/form.inc.php");
	require_once("include/db.inc.php");

/************************************************************************************************************************************/
/* Variablen initialisieren **************************/

					$firstname		= NULL;
					$lastname		= NULL;
					$email			= NULL;
					$company	    = NULL;
					$dbMessage		= NULL;

					$errorFirstname		= NULL;
					$errorLastname		= NULL;
					$errorEmail			= NULL;
					$errorCompany   	= NULL;
					$errorPassword		= NULL;
					$errorPasswordCheck	= NULL;

					$showform 		= true;

/************************************************************************************************************************************/

/* Formularverarbeitung **************************/
				if(isset($_POST['formsentRegistration'])) {
if(DEBUG)		echo "<p class='debug'>Formular abgeschickt</p>";
					$firstname		 = cleanString( $_POST["firstname"] );
					$lastname		 = cleanString( $_POST["lastname"] );
					$email			 = cleanString( $_POST["email"] );
					$company    	 = cleanString( $_POST["company"] );
					$password		 = cleanString( $_POST["password"] );
					$passwordCheck	 = cleanString( $_POST["passwordCheck"] );

if(DEBUG)			echo "<p class='debug'>\$firstname: $firstname</p>";
if(DEBUG)			echo "<p class='debug'>\$lastname: $lastname</p>";
if(DEBUG)			echo "<p class='debug'>\$email: $email</p>";
if(DEBUG)			echo "<p class='debug'>\$accountname: $company</p>";
if(DEBUG)			echo "<p class='debug'>\$password: $password</p>";
if(DEBUG)			echo "<p class='debug'>\$passwordCheck: $passwordCheck</p>";

					$errorFirstname		= checkInputString( $firstname , 2, 255 );
					$errorLastname		= checkInputString( $lastname , 3, 255 );
					$errorEmail			= checkEmail( $email );
					$errorCompany	= checkInputString( $company , 3, 20 );
					$errorPassword		= checkInputString( $password , 8, 255 );
					$errorPasswordCheck	= checkInputString( $passwordCheck , 8 );

/* Password prüfen ******************************************************/
						// Password mit PasswordCheck vergleichen
                    if( !$errorPassword ) {
                        if( $password != $passwordCheck ) {
                            $errorPasswordCheck ="Die Passworte stimmen nicht überein!";
                        } else {
if(DEBUG)			echo "<p class='debug'>\$password =  \$passwordCheck</p>";
								// Passwort verschlüsseln
								$passwordHash = password_hash($password, PASSWORD_DEFAULT);
if(DEBUG)			echo "<p class='debug'>\$passwordHash = $passwordHash</p>";
                        }
                    }
/*************************************************************************/

/* Abschließende Formularprüfung *****************************************/
					if( !$errorFirstname &&
						!$errorLastname &&
						!$errorEmail &&
						!$errorCompany &&
						!$errorPassword &&
						!$errorPasswordCheck) {
							// Erfolgsfall Formularprüfung
if(DEBUG)			echo "<p class='debug'>Das Formular wird nunr verarbeitet ... </p>";

/* Daten Weiterverarbeitung *********************************************/
// Datenbankoperation

                        $pdo = dbConnect();

/****** Prüfen, ob die EMail-Adresse schon vergeben wurde **************/
                        $statement = $pdo->prepare("
                            SELECT COUNT(usr_email) FROM users
                            Where usr_email = :ph_usr_email
                        ");

                        $statement->execute(array(
                            "ph_usr_email" => $email
                        )) OR DIE( $statement->errorInfo()[2] );

                        $emailExists = $statement->fetchColumn();

                            if( !$emailExists ){
                                // Erfolgsfall
if(DEBUG)			echo "<p class='debug'>$email ist noch nicht registriert ...</p>";


/* Registrierungsdaten in DB einfügen ****************************************/

// 1. User in die DB schreiben
                                $statement = $pdo->prepare("
                                    INSERT INTO users
                                    (usr_firstname, usr_lastname, usr_email, usr_company, usr_password)
                                    VALUE (:ph_usr_firstname, :ph_usr_lastname, :ph_usr_email, :ph_usr_company, :ph_usr_password)
                                ");

                                $statement->execute(array(
                                    "ph_usr_firstname" => $firstname,
                                    "ph_usr_lastname" => $lastname,
                                    "ph_usr_email" => $email,
                                    "ph_usr_company" => $company,
                                    "ph_usr_password" => $passwordHash
                                )) OR DIE( $statement->errorInfo()[2] );

                                $newUserId = $pdo->lastInsertID();
if(DEBUG)						echo "<p class='debug'>\$newUserId =  $newUserId</p>";

                                if( !$newUserId ) {
                                // Fehlerfall User anlegen
if(DEBUG)							echo "<p class='debug'>FEHLER beim Anlegen des neuen Users.</p>";
									$dbMessage = "<h3 class='error'>Es ist ein Fehler aufgetreten! Bitte versuchen sie es später erneut.</p>";
								}

                            } else {
                                // Fehlerfall Email ist vorhanden
                                $errorEmail = "Diese E-Mail-Adresse ist bereits registriert!";
                            }
						} else {
							// Fehlerfall Formularprüfung
if(DEBUG)			echo "<p class='debug'>FEHLER bei der Formularverarbeitunng! </p>";
						} // Ende Formularprüfung
if(DEBUG)			echo "<p class='debug'>... Formularverarbeitung ist abgeschlossen! </p>";
				} // Ende Formularverarbeitung

/************************************************************************************************************************************/

?>

<!doctype html>
<html lang="de">
<head>
    <title>Anmeldung</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="css/portfolio.css" type="text/css" media="screen"/>
</head>
    <body class="max-view">
        <header class="small-content">
            <div class="logo">
                <span><a href="index.php">Zur Startseite</a></span>
            </div>
        </header>
            <main>
                <article>
                    <?= $dbMessage ?>
            <?php if( $showform ): ?>
                    <form action="" method="POST">
                        <input type="hidden" name="formsentRegistration">
                        <!--Vorname-->
                        <label for="firstname" class="inp">
                            <input type="text" name="firstname" id="firstname" value="<?= $firstname ?>" placeholder="&nbsp;" >
                            <span class="label">Vorname:</span><span class="error"><?= $errorFirstname ?></span><br>
                        </label>
                        <!--Nachname-->
                        <label for="lastname" class="inp">
                            <input type="text" name="lastname" id="lastname" value="<?= $lastname ?>" placeholder="&nbsp;" >
                            <span class="label">Nachname:</span><span class="error"><?= $errorLastname ?></span><br>
                        </label>
                        <!--Firma-->
                        <label for="company" class="inp">
                            <input type="text" name="company" id="company" value="<?= $company ?>" placeholder="&nbsp;" >
                            <span class="label">Firma:</span><span class="error"><?= $errorCompany ?></span><br>
                        </label>
                        <!--E-Mail-->
                        <label for="email" class="inp">
                            <input type="text" name="email" id="email" value="<?= $email ?>" placeholder="&nbsp;" >
                            <span class="label">E-Mail:</span><span class="error"><?= $errorEmail ?></span><br>
                        </label>
                        <!--Passwort-->
                        <label for="password" class="inp">
                            <input type="password" name="password" id="password" placeholder="&nbsp;" >
                            <span class="label">Passwort wählen:</span><span class="error"><?= $errorEmail ?></span><br>
                        </label>
                        <!--Passwort-->
                        <label for="password" class="inp">
                            <input type="password" name="passwordCheck" id="password" placeholder="&nbsp;" >
                            <span class="label">Passwort wiederholen:</span><span class="error"><?= $errorEmail ?></span><br>
                        </label>
<!--
                                <span class="error"><?= $errorPassword ?></span><br>
                            <input type="password" name="password" placeholder="Passwort wählen"><span class="marker">*</span><br>
                                <span class="error"><?= $errorPasswordCheck ?></span><br>
                            <input type="password" name="passwordCheck" placeholder="Passwort wiederholen"><span class="marker">*</span><br>
-->
                        <input type="submit" value="Registrierung abschliessen">
                    </form>
            <?php endif ?>
                </article>
        </main>
    </body>
</html>