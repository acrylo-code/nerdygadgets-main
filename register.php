<!DOCTYPE html>
<html>
<head>
<title>Registreren</title>
</head>
<body>

<?php
include_once 'header.php';
?>
<form action="/nerdygadgets-main/register.php" method="get">
    <div class="inputboxregister">
        <div class="registerTitle"> Registreren </div>
        <label>Voornaam</label>
        <input class="registerbox" type="Voornaam" name="Voornaam">
        <br>

        <label>Tussenvoegsel</label>
        <input class="registerbox"  name="Tussenvoegsel">
        <br>

        <label>Achternaam</label>
        <input class="registerbox" type="Achternaam" name="Achternaam">
        <br>

        <label>Email adres</label>
        <input class="registerbox" type="Email" name="Email">
        <br>

        <label>Adres</label>
        <input class="registerbox"  type="Adres" name="Adres">
        <br>

        <label>Huisnummer</label>
        <input class="registerbox" type="Huisnummer" name="Huisnummer">
        <br>

        <label>Telefoonnummer</label>
        <input class="registerbox" type="Telefoonnummer" name="Telefoonnummer">
        <br>
        
        <label>Postcode</label>
        <input class="registerbox" type="Postcode" name="Postcode" >
        <br>

        <label>Plaats</label>
        <input class="registerbox" type="Plaats" name="Plaats" >
        <br>

        <label>Wachtwoord</label>
        <input class="registerbox" type="password" name="Wachtwoord" >
        <br>

        <label>Herhaling wachtwoord</label>
        <input class="registerbox" type="password" name="WachtwoordRepeat" >
        <br>
        <input class="registersubmit" type="submit" value="Submit" href="/nerdygadgets-main/register.php">
</body>
</html>