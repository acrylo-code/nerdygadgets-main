<?php
error_reporting(E_ERROR | E_PARSE);
session_start();
include "database.php";
include_once "product-functions.php";
include_once "cart-functions.php";
include_once "user-functions.php";
include_once "product-functions.php";
$databaseConnection = connectToDatabase();

if($_GET['logout'] == 1){
    logout();
    header("Location: index.php");
}
// Switch die bij elke pagina de relevante checks uitvoort (voor de header!) - dit is naar deze pagina heen verplaats.
switch ($_SERVER['PHP_SELF']) {
    // Manage discounts pagina checks
    case '/nerdygadgets-main/manage-discounts.php':
        if(!isLoggedIn() || !isAdmin()){
            error_reporting( E_ALL );
            addErrorMessage("U dient als administrator ingelogd zijn om deze pagina te bekijken!"); 
            header("Location: login.php");
        }

        if(isset($_GET['method'])){
            handleDiscount($_GET['method']);
        }
        break;
    // Korting aanpassen pagina checks
    case '/nerdygadgets-main/edit-discount.php':
        if(!isset($_GET['StockItemID'])){
            header("Location: manage-discounts.php");
        } else{
            if(isset($_GET['discountAmount']) && isset($_GET['discountValidUntil'])){
                $stockItemID = $_GET['StockItemID'];
                $discountAmount = $_GET['discountAmount'];
                $discountValidUntil = $_GET['discountValidUntil'];
                if(isset($discountAmount) &&  isset($discountValidUntil)){
                    setDiscount($stockItemID, $discountAmount, $discountValidUntil);
                } else{
                    addErrorMessage("Vul alle velden in a.u.b.");
                }
                header("Location: manage-discounts.php");
            }
        }
        break;
    // Korting toevoegen pagina checks
    case '/nerdygadgets-main/add-discount.php':
        if(isset($_GET['method'])){
            handleDiscount($_GET['method']);
        }
        break;
    // Bestelling plaatsen checks
    case '/nerdygadgets-main/order.php':
        if(!isLoggedIn()){
            addErrorMessage("U dient ingelogd te zijn om een bestelling te kunnen plaatsen.");
            header("Location: login.php");
        }
        if(isset($_GET['action'])){
            handleCartAction($_GET['action']);
        }
        break;
    // Winkelwagen pagina checks
    case '/nerdygadgets-main/cart.php':
        if(isset($_GET['action'])){
            handleCartAction($_GET['action']);
        }
        break;
    // Bestellingen bekijken pagina checks
    case '/nerdygadgets-main/myorders.php':
        if(isLoggedIn()){
            $KlandID = $_SESSION['KlantID'];
            myorderss($KlandID);
        } else{
            header('Location: /nerdygadgets-main/login.php');
        }
        break;

    case '/nerdygadgets-main/login.php':
        if (isset($_SESSION['KlantID'])) {
            header('Location: myorders.php');
        } else {
            if(isset($_POST['email']) && isset($_POST['password'])){
        
                $resultlogin = login($_POST['email'], $_POST['password']);
                
                if(isset($resultlogin)){
                    $_SESSION['KlantID'] = $resultlogin[0]['KlantID'];
                    header('Location: myorders.php');
                } else {
                    echo "Invalid email or password";
                }
            }
        }
        break;
    // Registratie pagina checks start
    case '/nerdygadgets-main/register.php':
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
                            register($inloggegevens);
                            print ("Je bent geregistreerd.");
                         } else {
                        print("Een veld is niet geldig.");
                        header("Location: order.php");
                        }
                    } else { print ("Je wachtwoorden zijn niet sterk genoeg. Gebruik 8 tekens, en zorg dat er een cijfer, een speciaal teken, een hoofd- en een normale letter instaat.");}
            } else {print("Je wachtwoordenzijn niet hetzelfde.") ;}
        } else {print("Een veld is niet geldig.");}
        break;
    // Registratie pagina checks einde
    // Contact pagina checks start
    case '/nerdygadgets-main/contact.php':
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
        break;
    // Contact pagina checks einde

    default:
        # code...
        break;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>NerdyGadgets</title>

    <!-- Javascript -->
    <script src="Public/JS/fontawesome.js"></script>
    <script src="Public/JS/jquery.min.js"></script>
    <script src="Public/JS/bootstrap.min.js"></script>
    <script src="Public/JS/popper.min.js"></script>
    <script src="Public/JS/resizer.js"></script>

    <!-- Style sheets-->
    <link rel="stylesheet" href="Public/CSS/style.css" type="text/css">
    <link rel="stylesheet" href="Public/CSS/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="Public/CSS/typekit.css">
</head>
<body>
<div class="Background">
<div class="row" id="Header">
</div>
    <div class="row" id="Header">
        <div class="col-2"><a href="./" id="LogoA">
                <div id="LogoImage"></div>
            </a></div>
        <div class="col-10" id="CategoriesBar">
            <ul id="ul-class">
                <lu>
                    <div class="TIM col-5 " id="SearchBar">
                        <form action="/nerdygadgets-main/browse.php" method="get">
                            <input type="text" name="search_string" placeholder="Zoeken naar producten..." id="SearchInput">
                        </form>
                    </div>
                </lu>
                <?php
                $HeaderStockGroups = getHeaderStockGroups($databaseConnection);

                foreach ($HeaderStockGroups as $HeaderStockGroup) {
                    ?>
                    <li>
                        <a href="/nerdygadgets-main/browse.php?category_id=<?php print $HeaderStockGroup['StockGroupID']; ?>"
                           class="HrefDecoration"><?php print $HeaderStockGroup['StockGroupName']; ?></a>
                    </li>
                    <?php
                }
                ?>
                <li>
                    <a href="/nerdygadgets-main/categories.php" class="HrefDecoration">Alle categorieën</a>
                </li>
            </ul>
        </div>
        <div class='navigation_bar'>
            <ul id="ul-class-navigation">
                <liv>
                    <?php if (isset($_SESSION['KlantID'])) { ?>
                    <form method="post" action="header.php?logout=1">
                        <button type="submit" class="btn btn-primary btn_nerdy" style="margin-top: 20px" name="uitloggen">Uitloggen</button>
                    </form>
                    <?php } ?>
                </liv>
                <li>
                    <a href="/nerdygadgets-main/cart.php" class="HrefDecoration "><i aria-hidden="true">
                    <img src="Public\ProductIMGHighRes\cart.png" alt="cart" width="40" height="32">
                </i>
                <li>⠀</a> <?php if(isset($_SESSION['KlantID'])) {?>
                    <a href="/nerdygadgets-main/myorders.php" class="HrefDecoration "><i aria-hidden="true">
                    <img src="Public\ProductIMGHighRes\login.png" alt="login" width="40" height="32">
                    <?php } else {?>
                    <a href="/nerdygadgets-main/login.php" class="HrefDecoration "><i aria-hidden="true">
                    <img src="Public\ProductIMGHighRes\login.png" alt="login" width="40" height="32">
                    <?php }?>
                </i>
                <li>⠀</a>
                    <a href="/nerdygadgets-main/contact.php" class="HrefDecoration "><i aria-hidden="true">
                    <img src="Public\ProductIMGHighRes\contact.png" alt="login" width="40" height="32">
                </i>
            </a>
                <span class="badge badge-dark itemCount"  ><?php echo getTotalItemsInCart(); ?></span>
                </li>                
            </ul>
        </div>
        <?php if ($_SESSION['totalPrice'] >= 100) {?>
        <div id="KortingFrame"><h6>Gebruik "5EUROKORTING" bij meer dan 100 euro.</h6>
        </div>
        <?php }          
            if(isset($_POST['uitloggen'])) 
            {
                unset ($_SESSION["email"]);
                unset ($_SESSION["password"]);
                unset ($_SESSION["KlantID"]);
            }
        ?>
    </div>
    <div class="row" id="Content">
        <div class="col-12">
            <div id="SubContent">
