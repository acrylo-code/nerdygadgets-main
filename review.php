<?php
include __DIR__ . "/header.php";

$review = [
    'StockItemID' => $_SESSION['ReviewProductID'],
    'Rating' => $_POST['rate'],
    'Review' => $_POST['ReviewText'],
    'KlantID' => $_SESSION['KlantID'],
    'Title' => $_POST['title']
];

$_SESSION['ReviewProductID'] = null;

// toevogen check voor de ID's
if (isset($_POST['rate']) && isset($_POST['ReviewText']) && isset($_POST['title'])) {
    addReview($review);
}

function addReview($review){
    // Haal de database connectie op
    $conn = connectToDatabase();
    // haal datum op
    $currentDate = date("Y-m-d H:i:s");
    // Maak een query voor het toevoegen aan het tabel "orderregels" maak gebruik van mysqli_stmt
    $query = "INSERT INTO reviews (StockItemID, KlantID, Review, Rating, Date, Title) VALUES (?, ?, ?, ?, ?, ?)";
    $statement = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($statement, "iisiss", 
            $review["StockItemID"],
            $review["KlantID"],
            $review["Review"],
            $review["Rating"],
            $currentDate,
            $review["Title"]);
    // Voer de query uit
    mysqli_stmt_execute($statement);
}

?>
<div id="SubContent">
<div class="IndexStyle">
    <div class="col-11">
        <div class="TextPrice">
            <a>
                <div class="TextMain">
                    Je review is toegevoegd!
                </div>
                <ul id="ul-class-price">
                    <li class="HomePagePrice">bedankt!</li>
                </ul>
        <div class="Bedanktvoorreview"></div>
    </div>
</div>