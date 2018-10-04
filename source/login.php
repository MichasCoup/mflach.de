<?php

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
