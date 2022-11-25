<?php
include_once "header.php";


// $klantgegevens = [ 
//     "Voornaam" => $_POST['Voornaam'],
//     "Achternaam" => "Jansen",
//     "Adres" => "Jansstraat 1",
//     "Postcode" => "1234AB",
//     "Woonplaats" => "Jansstad",
//     "Telefoonnummer" => "dsd",
//     "Email" => "Nederland",
//     "Wachtwoord" => "test" 
// ];
$gegevens = [];

$gegevens = [
    "Voornaam" => $_POST['Voornaam'],
    "Achternaam" => $_POST['Achternaam'],
    "Adres" => $_POST['Adres'],
    "Postcode" => $_POST['Postcode'],
    "Woonplaats" => $_POST['Woonplaats'],
    "Telefoonnummer" => $_POST['Telefoonnummer'],
    "Email" => $_POST['Email'],
    "Wachtwoord" => $_POST['Wachtwoord']
];

var_dump($gegevens);

// if( isset($_POST['Voornaam'] && $_POST['Achternaam'] && $_POST['Adres'] && $_POST['Postcode'] && $_POST['Woonplaats'] && $_POST['Telefoonnummer'] && $_POST['Email'] && $_POST['Wachtwoord'])){
    
// }

function register($klantgegevens){
    // Haal het winkelwagentje op
    $cart = getCart();
    // Haal de database connectie op
    $conn = connectToDatabase();
    // Haal de huidige datum op
    $currentDate = date("Y-m-d H:i:s");
    // Maak een query voor het toevoegen aan het tabel "klantgegevens" maak gebruik van mysqli_stmt
    $query = "INSERT INTO klantgegevens (Voornaam, Achternaam, Adres, Postcode, Woonplaats, Telefoonnummer, Email, Wachtwoord) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $statement = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($statement, "ssssssss", 
        $klantgegevens["Voornaam"], 
        $klantgegevens["Achternaam"], 
        $klantgegevens["Adres"], 
        $klantgegevens["Postcode"], 
        $klantgegevens["Woonplaats"], 
        $klantgegevens["Telefoonnummer"], 
        $klantgegevens["Email"], 
        $klantgegevens["Wachtwoord"]);
    
    // Voer de query uit
    mysqli_stmt_execute($statement);
    // Haal het laatst toegevoegde KlantID op
    $klantID = mysqli_insert_id($conn);
    $_SESSION['klantId'] = $klantID;
}

// Voorbeeld van een row waar de functie mee werkt, deze functie gebruik je in de addOrder functie.
// $orderRow = [
//     "OrderID" =>  0,
//     "ProductId" => 0,
//     "Aantal" => 0,
//     "Prijs" => 0,
// ];
function addOrderRow($row){
    var_dump($row);
    // Haal de database connectie op
    $conn = connectToDatabase();
    // Maak een query voor het toevoegen aan het tabel "orderregels" maak gebruik van mysqli_stmt
    $query = "INSERT INTO bestellingen_rows (OrderID, ProductID, Aantal, Prijs) VALUES (?, ?, ?, ?)";
    $statement = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($statement, "iiii", 
        $row["OrderID"], 
        $row["ProductId"], 
        $row["Aantal"], 
        $row["Prijs"]);
    // Voer de query uit
    mysqli_stmt_execute($statement);
}

function addOrder($userId){
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
        $userId, 
        $currentDate);
    // Voer de query uit
    mysqli_stmt_execute($statement);
    // Haal het laatst toegevoegde OrderID op
    $orderId = mysqli_insert_id($conn);
    
    // Voeg elke orderregel toe van de bestelling
    foreach($cartItems as $cartItem){
        $orderRow = [
            "OrderID" =>  $orderId,
            "ProductId" => $cartItem["StockItemID"],
            "Aantal" => $cartItem["quantityInCart"],
            "Prijs" => $cartItem["SellPrice"],
        ];
        addOrderRow($orderRow);
    }
}

// Voorbeeld van $klantgegevens:
// $klantgegevens = [ 
//     "Voornaam" => "Jan",
//     "Achternaam" => "Jansen",
//     "Adres" => "Jansstraat 1",
//     "Postcode" => "1234AB",
//     "Woonplaats" => "Jansstad",
//     "Telefoonnummer" => "dsd",
//     "Email" => "Nederland",
//     "Wachtwoord" => "test" 
// ];

// Voert alle helper functies uit om een bestelling te plaatsen
function placeOrder($klantgegevens){
    // Haal de database connectie op
    $conn = connectToDatabase();
    // Haal de huidige datum op
    $currentDate = date("Y-m-d H:i:s");
    // Haal de huidige gebruiker op
    $userId = $_SESSION['klantId'];
    
    // Gebruik de opgeslagen UserID om de bestelling te plaatsen
    if(isset($userId)){
        addOrder($userId);
    } else{
        //Gebruik $klantgegevens om een nieuwe bestelling te plaatsen voor een klant
        if(cartContainsItems()){
            // Registreer de gebruiker met de gegevens uit $klantgegevens
            register($klantgegevens);
            // Haal de UserID op van de net geregistreerde gebruiker en zet deze vast in de sessie.
            $userId = $_SESSION['klantId'];
            // Plaats de bestelling
            addOrder($userId);
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
    $userId = $_SESSION['klantId'];
    // Maak een query voor het ophalen van alle bestellingen van de huidige gebruiker
    if(isset($userId)){
        $query = "SELECT * FROM bestellingen WHERE KlantID = $userId";
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
