<!DOCTYPE html>
<html>
<head>
<title>Registreren</title>
</head>
<body>

<?php
include_once 'header.php';

$inloggegevens = [
    "Voornaam" => $_GET["Voornaam"],
    "Tussenvoegsel" => $_GET["Tussenvoegsel"],
    "Achternaam" => $_GET["Achternaam"],
    "Adres" => $_GET["Adres"],
    "Postcode" => $_GET["Postcode"],
    "Plaats" => $_GET["Plaats"],
    "Telefoonnummer" => $_GET["Telefoonnummer"],
    "Email" => $_GET["Email"],
    "Huisnummer" => $_GET["Huisnummer"],
    "Wachtwoord" => SHA1($_GET["Wachtwoord"])
];

if ( isset($_GET['Voornaam']) && isset($_GET['Tussenvoegsel']) && isset($_GET['Achternaam']) && isset($_GET['Adres']) && isset($_GET['Postcode']) && isset($_GET['Plaats']) && isset($_GET['Telefoonnummer']) && isset($_GET['Email']) && isset($_GET['Huisnummer']) && isset($_GET['Wachtwoord']) && isset($_GET['WachtwoordRepeat'])){  
    if(!preg_match("/^[a-zA-Z]*$/",$_GET['Voornaam']) || !preg_match("/^[a-zA-Z]*$/",$_GET['Achternaam']) !== false 
        || strpos($_GET['Postcode']," ") !== false || strpos($_GET['Email']," ") !== false ||  strlen($_GET['Postcode']) != 6){
        //goto order.php
            print("Een veld is niet geldig.");
            header("Location: register.php");
        } elseif (($_GET['Wachtwoord'] == $_GET['WachtwoordRepeat'])) {
            if (isStrongPassword($_GET['Wachtwoord'])){
                $split_Postcode = str_split(strtoupper($_GET['Postcode']),4);
                $split_Postcode[0] = (preg_match("/^[0-9]{4}$/",$split_Postcode[0]));
                $split_Postcode[1] = (preg_match("/^[a-zA-Z]{2}$/",$split_Postcode[1]));
                if ($split_Postcode[0] == 1 && $split_Postcode[1] == 1){
                    register2($inloggegevens);
                    print ("Je bent geregistreerd.");
                 } else {
                print("Een veld is niet geldig.");
                header("Location: order.php");
                }
            } else { print ("Je wachtwoorden zijn niet sterk genoeg. Gebruik 8 tekens, en zorg dat er een cijfer, een speciaal teken, een hoofd- en een normale letter instaat.");}
    } else {print("Je wachtwoordenzijn niet hetzelfde.") ;}
} else {print("Een veld is niet geldig.");}

function isStrongPassword($password){
    $regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/";
    if(preg_match($regex, $password)){
        return true;
        print("passed password check");
    } else{
        return false;
        print("didnt passed password check");
    }
    
}


function register2($inloggegevens){
    // Haal de database connectie op
    $conn = connectToDatabase();
    // Haal de huidige datum op
    $currentDate = date("Y-m-d H:i:s");
    // Maak een query voor het toevoegen aan het tabel "inlogge$inloggegevens" maak gebruik van mysqli_stmt
    // Registreren van een klant
    $query3 = "INSERT INTO klantgegevens (Voornaam, Tussenvoegsel, Achternaam, Adres, Postcode, Woonplaats, Telefoonnummer, Email, Huisnummer, Wachtwoord) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    // Ophalen van een klant dmv email & ww
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