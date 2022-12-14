<!DOCTYPE html>
<html>
<head>
<title>Registreren</title>
</head>
<body>

<?php
include_once 'header.php';

$inloggegevens = [
    "Voornaam" => $_POST["Voornaam"],
    "Tussenvoegsel" => $_POST["Tussenvoegsel"],
    "Achternaam" => $_POST["Achternaam"],
    "Adres" => $_POST["Adres"],
    "Postcode" => $_POST["Postcode"],
    "Plaats" => $_POST["Plaats"],
    "Telefoonnummer" => $_POST["Telefoonnummer"],
    "Email" => $_POST["Email"],
    "Huisnummer" => $_POST["Huisnummer"],
    "Wachtwoord" => SHA1($_POST["Wachtwoord"])
];

if (
    isset($_POST['Voornaam']) &&
    isset($_POST['Tussenvoegsel']) &&
    isset($_POST['Achternaam']) &&
    isset($_POST['Adres']) &&
    isset($_POST['Postcode']) &&
    isset($_POST['Plaats']) &&
    isset($_POST['Telefoonnummer']) &&
    isset($_POST['Email']) &&
    isset($_POST['Huisnummer']) &&
    isset($_POST['Wachtwoord'])
){
    var_dump ($inloggegevens);
    register2($inloggegevens);
    }

function register2($inloggegevens){
    // Haal de database connectie op
    $conn = connectToDatabase();
    // Haal de huidige datum op
    $currentDate = date("Y-m-d H:i:s");
    // Maak een query voor het toevoegen aan het tabel "inlogge$inloggegevens" maak gebruik van mysqli_stmt
    // Registreren van een klant
    $query3 = "INSERT INTO klantgegevens (Voornaam, Tussenvoegsel, Achternaam, Adres, Postcode, Woonplaats, Telefoonnummer, Email, Huisnummer, Wachtwoord) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    // $query = "INSERT INTO klantgegevens (Voornaam, Tussenvoegsel, Achternaam, Adres, Postcode, Woonplaats, Telefoonnummer, Email, Huisnummer, SHA1(Wachtwoord)) VALUES (?, ?, ?, ?, ?, ?, ?, ?, SHA('?'))";
    // Ophalen van een klant dmv email & ww
    //$query2 = "SELECT * FROM klantgegevens $inloggegevens WHERE Email = ? AND Wachtwoord = SHA1(?)";
    $statement3 = mysqli_prepare($conn, $query3);
    mysqli_stmt_bind_param($statement3, "ssssssssis", 
        $inloggegevens["Voornaam"], 
        $inloggegevens["Tussenvoegsel"], 
        $inloggegevens["Achternaam"],
        $inloggegevens["Adres"], 
        $inloggegevens["Postcode"], 
        $inloggegevens["Plaats"], 
        $inloggegevens["Telefoonnummer"], 
        $inloggegevens["Email"], 
        $inloggegevens["Huisnummer"],
        $inloggegevens["Wachtwoord"]);
    // Voer de query uit
    mysqli_stmt_execute($statement3);
    // Haal het laatst toegevoegde KlantID op
    $klantID = mysqli_insert_id($conn);
    $_SESSION['KlantId'] = $klantID;
}

?>
<form action="/nerdygadgets-main/register.php" method="post">
    <div class="inputboxlogin">
        <label>Voornaam</label>
        <input type="Voornaam" name="Voornaam">
        <br>

        <label>Tussenvoegsel</label>
        <input name="Tussenvoegsel" value="a" >
        <br>

        <label>Achternaam</label>
        <input type="Achternaam" name="Achternaam" value="a">
        <br>

        <label>Email adres</label>
        <input type="Email" name="Email" value="a@a.nl">
        <br>

        <label>Adres</label>
        <input type="Adres" name="Adres" value="a" >
        <br>

        <label>Huisnummer</label>
        <input type="Huisnummer" name="Huisnummer" value="1" >
        <br>

        <label>Telefoonnummer</label>
        <input type="Telefoonnummer" name="Telefoonnummer" value="123">
        <br>
        
        <label>Postcode</label>
        <input type="Postcode" name="Postcode"value="123" >
        <br>

        <label>Plaats</label>
        <input type="Plaats" name="Plaats" value="a" >
        <br>

        <label>Wachtwoord</label>
        <input type="Wachtwoord" name="Wachtwoord" value="a">
        <br>

        <label>Herhaling wachtwoord</label>
        <input type="WachtwoordRepeat" name="WachtwoordRepeat" value="a">
        <br>
        <input type="submit" value="Submit" href="/nerdygadgets-main/register.php">
</body>
</html>