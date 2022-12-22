<?php
include __DIR__ . "/header.php";
include __DIR__ . "/user-functions.php";

if(isLoggedIn()){
    $KlandID = $_SESSION['KlantID'];
    myorderss($KlandID);
} else{
    header('Location: /nerdygadgets-main/login.php');
}
?>

<!DOCTYPE html>
<html>
<link rel="stylesheet" href="/nerdygadgets-main/Public/CSS/review.css">
<link rel="stylesheet" href="/nerdygadgets-main/Public/CSS/style.css">
<head>
<title>Mijn bestellingen</title>
</head>
<body>

<?php
function myorderss($KlandID){ 
    $conn = connectToDatabase();
    $query4 = "
    SELECT * FROM bestellingen
    JOIN bestellingen_rows ON bestellingen_rows.OrderRegelID = bestellingen.OrderID 
    WHERE bestellingen.KlantID = '".$KlandID."'  ";

    $Statement = mysqli_prepare($conn, $query4);
    mysqli_stmt_execute($Statement);
    $result = mysqli_stmt_get_result($Statement);
    $resultlogin = mysqli_fetch_all($result, MYSQLI_ASSOC);

    foreach ($resultlogin as $key => $login) 
    {
        $orderid = $login['OrderID'];
        $orderdatum = $login['OrderDatum'];
        $prijs = $login['Prijs'];
        $productID = $login['ProductID'];
        $product = checkproducts($login['ProductID']);
        $productNaam = $product[0]['StockItemName'];
        $image = $product[0]['ImagePath'];
        $searchDetails = $product[0]['SearchDetails'];
?>
        <div class="orderbox">
      <?php print("<h3>".$productNaam."</h3>");
            print("<p>" . "Bestelnummer: ".$orderid." </p>");
            print("<p>" . "Besteldatum: ".$orderdatum."</p>");
            print("<p>" . "Beschrijving : ".$searchDetails."</p>");?>   
        <div class="orderpicture">
            <img style="max-width: 100px;" src="/nerdygadgets-main/Public/StockItemIMG/<?php echo $image ?>" alt="">
        </div>
        <div class="orderprice">
        <?php
            print("<h3>" . "€" . "$prijs" . "</h3>");
        ?>
        </div>
        <div class="orderreview">
        <?php 
        $reviews = selectPostedReviews($productID, $KlandID);
        if(isset($reviews[0]['ReviewID'])){
            print("<p>" . "U heeft al een review geschreven over dit product" . "</p>");
        } else {
        
        ?>
        <a href="/nerdygadgets-main/view.php?id=<?php echo($productID);?>&LeaveReview=true" class="btn btn-primary btn_nerdy">Schrijf een review</a>
        <?php }?>
        </div>
        </div>
        <?php
    }
    return($resultlogin);
}

$result = myorderss($KlandID);

function checkproducts($ProductID) {
    $conn = connectToDatabase();
    $query = "
    SELECT StockItemName, ImagePath, SearchDetails
    FROM stockitems S
    LEFT join stockitemimages I ON S.StockItemID = I.StockItemID
    WHERE S.StockItemID = '".$ProductID."'";
    $Statement = mysqli_prepare($conn, $query);
    mysqli_stmt_execute($Statement);
    $result = mysqli_stmt_get_result($Statement);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return($result);
}

?>
</div>
</body>
</html>
