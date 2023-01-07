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


if (isset($_GET['action'])) {
    if ($_GET['action'] == 'checkout') {
        addOrder($KlantID);
        ?>
        <script type="text/javascript">
                alert("Uw bestelling is geplaatst!");
                window.location = "https://bankieren.rabobank.nl/welcome/"
            </script>
            <?php
            $cart = [];
            saveCart($cart);
            $_SESSION['totalPrice'] = 0;
    }
} else {
    if(isset($_POST['Voornaam']) && isset($_POST['Achternaam']) && isset($_POST['Adres']) && isset($_POST['Postcode']) && isset($_POST['Woonplaats']) && isset($_POST['Telefoonnummer']) && isset($_POST['Email']) && isset($_POST['Huisnummer'])){
    // Test of de ingevoerde gegevens geldig zijn en geen spaties bevatten of andere ongewenste tekens
    if(!preg_match("/^[a-zA-Z]*$/",$_POST['Voornaam']) || !preg_match("/^[a-zA-Z]*$/",$_POST['Achternaam']) !== false 
    || strpos($_POST['Postcode']," ") !== false || strpos($_POST['Email']," ") !== false ||  strlen($_POST['Postcode']) != 6){
    // Als de gegevens niet geldig zijn, stuur de gebruiker terug naar de order pagina en geef een error
        $_SESSION['error'] = "Een veld is niet geldig.";
        header("Location: order.php");
    } else{
        // Als de gegevens wel geldig zijn, voer de functie uit om de gegevens in de database te zetten
        // en stuur de gebruiker door naar de betalingspagina
        //eerst wordt de postcode gesplitst in 2 delen, de eerste 4 cijfers en de laatste 2 letters
        // vervolgens wordt gekeken of de eerste 4 cijfers een getal zijn en de laatste 2 letters een letter
            $split_Postcode = str_split(strtoupper($_POST['Postcode']),4);
            $split_Postcode[0] = (preg_match("/^[0-9]{4}$/",$split_Postcode[0]));
            $split_Postcode[1] = (preg_match("/^[a-zA-Z]{2}$/",$split_Postcode[1]));
            if ($split_Postcode[0] == 1 && $split_Postcode[1] == 1){
                placeOrder($klantgegeven, $KlantID);
                ?>
                <script type="text/javascript">
                    alert("Uw bestelling is geplaatst!");
                    window.location = "https://bankieren.rabobank.nl/welcome/"
                </script>
                <?php
                // Als de bestelling is geplaatst, maak de winkelwagen leeg en zet de totale prijs op 0
                $cart = [];
                saveCart($cart);
                $_SESSION['totalPrice'] = 0;
        } else {
            // Als de postcode niet geldig is, stuur de gebruiker terug naar de order pagina en geef een error
            $_SESSION['error'] = "Een veld is niet geldig.";
            header("Location: order.php");
            }
        }
    }
}

function updateStock($StockItemID, $quantity){
    $conn = connectToDatabase();
    $query = $query = "UPDATE stockitemholdings SET QuantityOnHand = QuantityOnHand - $quantity WHERE StockItemID = $StockItemID";    
    $result = mysqli_query($conn, $query);
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
}

function addOrder($KlantID){
    // get current cart
    $cartItems = getCartItems();
    $cart = getCart();
    // Haal de database connectie op
    $conn = connectToDatabase();
    // Start the transaction
    mysqli_begin_transaction($conn);
    // Haal de huidige datum op
    $currentDate = date("Y-m-d H:i:s");
    $orderTotaal = $_SESSION['totalPrice'];

    // Maak een query voor het toevoegen aan het tabel "orders" maak gebruik van mysqli_stmt
    $query = "INSERT INTO bestellingen (KlantID, OrderDatum, OrderTotaal) VALUES (?, ?, ?)";
    $statement = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($statement, "isi", 
        $KlantID, 
        $currentDate,
        $orderTotaal
    );
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
        updateStock($cartItem["StockItemID"], $cartItem["quantityInCart"]);
    }
    // If all queries are successful, commit the transaction
    mysqli_commit($conn);
}



// Voert alle helper functies uit om een bestelling te plaatsen
function placeOrder($klantgegevens, $KlantID){
    // Haal de database connectie op
    $conn = connectToDatabase();

    if(isLoggedIn()){
        if(cartContainsItems()){
            addOrder(getKlantId());
        }

    } else{
        addErrorMessage("Log a.u.b. in of maak een account aan om een bestelling te plaatsen.");
        header("Location: login.php");
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
