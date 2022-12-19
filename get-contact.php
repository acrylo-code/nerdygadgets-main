<?php include __DIR__ . "/header.php"; ?>
<?php include_once __DIR__ . "/database.php"; ?>
<html>
    <body>
    <?php print("<p style=color:red>".$_SESSION['error']."</p>");
                        $_SESSION['error'] = "";
                        ?>
        <form action="https://formsubmit.co/nink469@gmail.com" target=_blank method="post">
        <p class="StockItemName" style="color:#676EFF; position: absolute; left: 95px; top: 100px; width: 500px; font-size: 35px;" >Zoek Contact</p>
        <p style="color:#FFFFFF; position: absolute; left: 95px; top: 180px; width: 500px; font-size: 17px;" >Wilt u contact met ons opnemen?<br>Vul het formulier in en laat ons weten wat de reden is om contact op te nemen.</p>
        <input style="position: absolute; right: 400px; top: 120px; width: 210px; height: 30px;" placeholder = "Voornaam" type=Titel name="Klant Voornaam"><br><br>
        <input style="position: absolute; right: 100px; top: 120px; width: 290px; height: 30px;" placeholder = "Achternaam" type=Titel name="Klant Achternaam"><br><br>
        <input style="position: absolute; right: 100px; top: 155px; width: 510px; height: 30px;" placeholder = "E-mail" type="email" name="Klant Mail"><br><br>
        <textarea style="resize: none; position: absolute; right: 100px; top: 190px; width: 510px; height: 150px;" placeholder = "  Bericht" type=Titel name="Klant Bericht"></textarea><br><br>
        <!--<button onclick="verstuurBericht()" class="btn btn-primary button" type="button" style="position: absolute; left: 1000px; top: 350px; width: 150px;">Verstuur</button>-->
        <input type="submit" value="Verstuur" href="/nerdygadgets-main/get-contact.php" class="btn btn-primary checkoutbtn" style="position: absolute; right: 100px; top: 350px; width: 150px;"></a>
    </form>
    </body>
</html>

<style>
    .checkoutbtn {
        display: inline-block;
        padding: 5px 5px;
        font-size: 20px;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        outline: none;
        color: #fff;
        background-color: #676EFF;
        border: none;
        border-radius: 15px;
        box-shadow: 0 7px #9598ff;
        box-shadow: 0 7px #9598ff;
    }

     .checkoutbtn:hover {background-color: #3741ff
    }

    .checkoutbtn:active {
        background-color: #3741ff;
        box-shadow: 0 5px #666;
        transform: translateY(4px);
    }
    
</style>    

<?php
$contactForm = [
    "Voornaam" => $_POST['contactVoornaam'],
    "Achternaam" => $_POST['contactAchternaam'],
    "Email" => $_POST['contactMail'],
    "Bericht" => $_POST['contactBericht']
];

if(isset($_POST['contactVoornaam']) && isset($_POST['contactAchternaam']) && isset($_POST['contactMail']) && isset($_POST['contactBericht'])){
    // Test if string contains spaces
    // Test if string contains spaces or other characters
    if(preg_match("/^[a-zA-Z]*$/",$_POST['contactVoornaam']) && preg_match("/^[a-zA-Z]*$/",$_POST['contactAchternaam'])){
      //goto order.php
        sendContact($contactForm);
        print ("<a style='color:#676EFF; position: absolute; left: 95px; top: 270px; width: 500px; font-size: 20px;'> Bericht is verzonden.</a>");
    } else{
        $_SESSION['error'] = "Voornaam en Achternaam geen spaties of andere karakters bevatten.";
        header("Location: get-contact.php");
    }
    }


function sendContact($contactForm){
    // Haal de database connectie op
    $conn = connectToDatabase();
    // Haal de huidige datum op
    $currentDate = date("Y-m-d H:i:s");
    // Maak een query voor het toevoegen aan het tabel "klantgegevens" maak gebruik van mysqli_stmt
    $query = "INSERT INTO contactgegevens (Voornaam, Achternaam, Email, Bericht, sendTime) VALUES (?, ?, ?, ?, ?)";
    $statement = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($statement, "sssss", $contactForm["Voornaam"],$contactForm["Achternaam"], $contactForm["Email"], $contactForm["Bericht"], $currentDate);
    
    // Voer de query uit
    mysqli_stmt_execute($statement);
}

?>