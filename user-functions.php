<?php
    function isLoggedIn(){
        if(isset($_SESSION['KlantID']))
        {
            return true;
        } else {
            return false;
        }
    }

    function getKlantId(){
        return $_SESSION['KlantID'] || false;
    }

    function logout(){
        $_SESSION['KlantID'] = 0;
    }

    function isAdmin(){
        if(isset($_SESSION['KlantID']))
        {
            $conn = connectToDatabase();
            $query = "SELECT isAdmin FROM klantgegevens WHERE KlantID = ".$_SESSION['KlantID'];
            $result = mysqli_query($conn, $query);
            $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
            if($result[0]['isAdmin'] == 1){
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // login Lucas
    function login($email, $password){
        $conn = connectToDatabase();
        $query4 = "
        SELECT KlantID
        FROM klantgegevens
        WHERE Email = '".$email."' AND Wachtwoord = SHA1('".$password."') LIMIT 1";
        $Statement = mysqli_prepare($conn, $query4);
        mysqli_stmt_execute($Statement);
        $result = mysqli_stmt_get_result($Statement);
        $resultlogin = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return($resultlogin);
    }

    // register lucas
    function register($inloggegevens){
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

    // function login($email, $password){
    //     $conn = connectToDatabase();
    //     $query4 = "
    //     SELECT KlantID
    //     FROM klantgegevens
    //     WHERE Email = '".$email."' AND Wachtwoord = SHA1('".$password."') LIMIT 1 ";
    //     $Statement = mysqli_prepare($conn, $query4);
    //     mysqli_stmt_execute($Statement);
    //     $result = mysqli_stmt_get_result($Statement);
    //     $resultlogin = mysqli_fetch_all($result, MYSQLI_ASSOC);
        
        
    //     $resultlogin = $resultlogin[0];
    //     return($resultlogin);
    // }

    
    // function register($inloggegevens){
    //     // Haal de database connectie op
    //     $conn = connectToDatabase();
    //     // Haal de huidige datum op
    //     $currentDate = date("Y-m-d H:i:s");
    //     // Maak een query voor het toevoegen aan het tabel "inlogge$inloggegevens" maak gebruik van mysqli_stmt
    //     // Registreren van een klant
    //     $query3 = "INSERT INTO klantgegevens (Voornaam, Tussenvoegsel, Achternaam, Adres, Postcode, Woonplaats, Telefoonnummer, Email, Huisnummer, Wachtwoord) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    //     // $query = "INSERT INTO klantgegevens (Voornaam, Tussenvoegsel, Achternaam, Adres, Postcode, Woonplaats, Telefoonnummer, Email, Huisnummer, SHA1(Wachtwoord)) VALUES (?, ?, ?, ?, ?, ?, ?, ?, SHA('?'))";
    //     // Ophalen van een klant dmv email & ww
    //     //$query2 = "SELECT * FROM klantgegevens $inloggegevens WHERE Email = ? AND Wachtwoord = SHA1(?)";
    //     $statement3 = mysqli_prepare($conn, $query3);
    //     mysqli_stmt_bind_param($statement3, "ssssssssis", 
    //         $inloggegevens["Voornaam"], 
    //         $inloggegevens["Tussenvoegsel"], 
    //         $inloggegevens["Achternaam"],
    //         $inloggegevens["Adres"], 
    //         $inloggegevens["Postcode"], 
    //         $inloggegevens["Plaats"], 
    //         $inloggegevens["Telefoonnummer"], 
    //         $inloggegevens["Email"], 
    //         $inloggegevens["Huisnummer"],
    //         $inloggegevens["Wachtwoord"]);
    //     // Voer de query uit
    //     mysqli_stmt_execute($statement3);
    //     // Haal het laatst toegevoegde KlantID op
    //     $klantID = mysqli_insert_id($conn);
    //     $_SESSION['KlantID'] = $klantID;
    // }

    // Haalt de klantgegevens uit database als klant is ingelogd, anders false.
    function getKlantgegevens(){
        if(isLoggedIn()){
            $conn = connectToDatabase();
            $userId = $_SESSION['KlantID'];
            $query = "SELECT * FROM klantgegevens WHERE KlantID = $userId";
            $result = mysqli_query($conn, $query);
            $user = mysqli_fetch_assoc($result);
            return $user;
        }
        return false;
    }
?>