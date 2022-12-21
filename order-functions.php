<?php
include_once "header.php";


// $klantgegevens = [ 
//     "Voornaam" => $_POST['Voornaam'],
//     "Achternaam" => $_POST['Achternaam'],
//     "Achternaam" => "Jansen",
//     "Adres" => "Jansstraat 1",
//     "Postcode" => "1234AB",
//     "Woonplaats" => "Jansstad",
//     "Telefoonnummer" => "dsd",
//     "Email" => "Nederland",
//     "Huisnummer" => "1" 
// ];
$klantgegeven = [];
$KlantID = $_SESSION['KlantID'];

$klantgegeven = [
    "Voornaam" => strtolower($_POST['Voornaam']),
    "Tussenvoegsel" => strtolower($_POST['Tussenvoegsel']),
    "Achternaam" => strtolower($_POST['Achternaam']),
    "Adres" => strtolower($_POST['Adres']),
    "Postcode" => strtoupper($_POST['Postcode']),
    "Woonplaats" => strtolower($_POST['Woonplaats']),
    "Telefoonnummer" => $_POST['Telefoonnummer'],
    "Email" => $_POST['Email'],
    "Huisnummer" => $_POST['Huisnummer']
];

if(isset($_POST['Voornaam']) && isset($_POST['Achternaam']) && isset($_POST['Adres']) && isset($_POST['Postcode']) && isset($_POST['Woonplaats']) && isset($_POST['Telefoonnummer']) && isset($_POST['Email']) && isset($_POST['Huisnummer'])){
// Test if string contains spaces
// Test if string contains spaces or other characters
if(!preg_match("/^[a-zA-Z]*$/",$_POST['Voornaam']) || !preg_match("/^[a-zA-Z]*$/",$_POST['Achternaam']) !== false 
|| strpos($_POST['Postcode']," ") !== false || strpos($_POST['Email']," ") !== false ||  strlen($_POST['Postcode']) != 6){
  //goto order.php
    $_SESSION['error'] = "Een veld is niet geldig.";
    header("Location: order.php");
} else{
        $split_Postcode = str_split(strtoupper($_POST['Postcode']),4);
        $split_Postcode[0] = (preg_match("/^[0-9]{4}$/",$split_Postcode[0]));
        $split_Postcode[1] = (preg_match("/^[a-zA-Z]{2}$/",$split_Postcode[1]));
        if ($split_Postcode[0] == 1 && $split_Postcode[1] == 1){
            placeOrder($klantgegeven, $KlantID);
            ?>
            <script type="text/javascript">
                alert("Uw bestelling is geplaatst!");
                //window.location = "https://bankieren.rabobank.nl/welcome/"
            </script>
            <?php
            $cart = [];
            saveCart($cart);
            $_SESSION['totalPrice'] = 0;
    } else {
        $_SESSION['error'] = "Een veld is niet geldig.";
        header("Location: order.php");
        }
    }
}

function register($klantgegevens){
    // Haal het winkelwagentje op
    $cart = getCart();
    // Haal de database connectie op
    $conn = connectToDatabase();
    // Haal de huidige datum op
    $currentDate = date("Y-m-d H:i:s");
    // Maak een query voor het toevoegen aan het tabel "klantgegevens" maak gebruik van mysqli_stmt
    $query = "INSERT INTO klantgegevens (Voornaam, Tussenvoegsel, Achternaam, Adres, Postcode, Woonplaats, Telefoonnummer, Email, Huisnummer) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $statement = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($statement, "ssssssssi", $klantgegevens["Voornaam"],$klantgegevens["Tussenvoegsel"],$klantgegevens["Achternaam"], $klantgegevens["Adres"], $klantgegevens["Postcode"], $klantgegevens["Woonplaats"], $klantgegevens["Telefoonnummer"], $klantgegevens["Email"], $klantgegevens["Huisnummer"]);
    
    // Voer de query uit
    mysqli_stmt_execute($statement);
    // Haal het laatst toegevoegde KlantID op
    $KlantID = mysqli_insert_id($conn);
    print (mysqli_insert_id($conn));
    $_SESSION['KlantID'] = $KlantID;
}

// Voorbeeld van een row waar de functie mee werkt, deze functie gebruik je in de addOrder functie.
// $orderRow = [
//     "OrderID" =>  0,
//     "ProductId" => 0,
//     "Aantal" => 0,
//     "Prijs" => 0,
// ];
function addOrderRow($OrderID, $StockItemID, $Aantal, $Prijs){
    // Haal de database connectie op
    $conn = connectToDatabase();
    // Maak een query voor het toevoegen aan het tabel "orderregels" maak gebruik van mysqli_stmt
    $query = "INSERT INTO bestellingen_rows (OrderID, ProductID, Aantal, Prijs) VALUES (?, ?, ?, ?)";
    $statement = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($statement, "iiii", 
        $OrderID, 
        $StockItemID, 
        $Aantal, 
        $Prijs);
    // Voer de query uit
    mysqli_stmt_execute($statement);
    print('OrderRow function'."<br>");
    print($OrderID."<br>");
    print($StockItemID."<br>");
    print($Aantal."<br>");
    print($Prijs."<br>");
}

function addOrder($KlantID){
    // get current cart
    $cartItems = getCartItems();
    $cart = getCart();
    // Haal de database connectie op
    $conn = connectToDatabase();
    // Haal de huidige datum op
    $currentDate = date("Y-m-d H:i:s");
    // Maak een query voor het toevoegen aan het tabel "orders" maak gebruik van mysqli_stmt
    $query = "INSERT INTO bestellingen (KlantID, OrderDatum) VALUES (?, ?)";
    $statement = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($statement, "is", 
        $KlantID, 
        $currentDate);
    // Voer de query uit
    mysqli_stmt_execute($statement);
    // Haal het laatst toegevoegde OrderID op
    $orderId = mysqli_insert_id($conn);
    
    // Voeg elke orderregel toe van de bestelling
    foreach($cartItems as $cartItem){
        // $orderRow = [
        //     "OrderID" =>  $orderId,
        //     $ProductId = $cartItem["StockItemID"],
        //     "Aantal" => $cartItem["quantityInCart"],
        //     "Prijs" => $cartItem["SellPrice"],
        // ];
        addOrderRow($orderId, $cartItem["StockItemID"], $cartItem["quantityInCart"], $cartItem["SellPrice"]);
    }
}



// Voert alle helper functies uit om een bestelling te plaatsen
function placeOrder($klantgegevens, $KlantID){
    // Haal de database connectie op
    $conn = connectToDatabase();
    // Haal de huidige datum op
    $currentDate = date("Y-m-d H:i:s");
    // Haal de huidige gebruiker op
    
    // Gebruik de opgeslagen UserID om de bestelling te plaatsen
    if(isset($KlantID)){
        addOrder($KlantID);
    } else{
        //Gebruik $klantgegevens om een nieuwe bestelling te plaatsen voor een klant
        if(cartContainsItems()){
            // Registreer de gebruiker met de gegevens uit $klantgegevens
            register($klantgegevens);
            // Haal de UserID op van de net geregistreerde gebruiker en zet deze vast in de sessie.
            $KlantID = $_SESSION['KlantID'];
            // Plaats de bestelling
            addOrder($KlantID);
        }
    }
}

function getOrderRow($id){
    // Haal de database connectie op
    $conn = connectToDatabase();
    // Maak een query voor het ophalen van de orderregel met het opgegeven ID
    $query = "SELECT * FROM bestellingen_rows WHERE OrderRegelID = $id";
    // Voer de query uit
    $result = mysqli_query($conn, $query);
    // Haal de eerste rij op
    $row = mysqli_fetch_assoc($result);
    // Geef de rij terug
    return $row;
}

function getOrders(){
    // Haal de database connectie op
    $conn = connectToDatabase();
    // Haal de huidige gebruiker op
    $KlantID = $_SESSION['KlantID'];
    // Maak een query voor het ophalen van alle bestellingen van de huidige gebruiker
    if(isset($KlantID)){
        $query = "SELECT * FROM bestellingen WHERE KlantID = $KlantID";
        // Voer de query uit
        $result = mysqli_query($conn, $query);
        // Haal alle resultaten op
        $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);

        // Haal de rows van deze order op
        foreach($orders as $key => $order){
            $orders[$key]["rows"] = getOrderRow($order["OrderID"]);
        }
        return $orders;
    } else{
        // add an error message to $_SESSION['errorMessages']
        $_SESSION['errorMessages'][] = "Uw moet ingelogd zijn om bestellingen te kunnen plaatsen.";
    }
}
?>
