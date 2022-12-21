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
    SELECT 
    bestellingen_rows.ProductID, bestellingen_rows.Prijs, bestellingen_rows.Aantal, 
    bestellingen_rows.OrderID, bestellingen.OrderDatum, bestellingen.OrderTotaal, bestellingen_rows.OrderRegelID, bestellingen.Betaald FROM bestellingen
    JOIN bestellingen_rows ON bestellingen_rows.OrderRegelID = bestellingen.OrderID 
    WHERE bestellingen.KlantID = '".$KlandID."'  ";

    $Statement = mysqli_prepare($conn, $query4);
    mysqli_stmt_execute($Statement);
    $result = mysqli_stmt_get_result($Statement);
    $resultlogin = mysqli_fetch_all($result, MYSQLI_ASSOC);

    foreach ($resultlogin as $key => $login) 
    {
        $productNaam = $login['StockItemName'];
        $image = $login['ImagePath'];
        $orderid = $login['OrderId'];
        $orderdatum = $login['OrderDatum'];
        $prijs = $login['Prijs'];
        $productID = $login['ProductID'];
?>

        <div class="orderbox col-12" style="left: 7%">
      <?php print("<h3>$productNaam</h3>");
            print("<p>" . "Bestelnummer: ". "$orderid" . " </p>");
            print("<p>" . "Besteldatum: " . "$orderdatum". "</p>");?>   
        <div class="prijs">
        <?php
            print("<h3>" . "€" . "$prijs" . "</h3>");
        ?>
        </div>
        <div style="padding : 10px">
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
        <div>
            <img style="max-width: 100px;" src="/nerdygadgets-main/Public/StockItemIMG/<?php echo $image ?>" alt="">
        </div>
        <?php
    }
    return($resultlogin);
}

$result = myorderss($KlandID);

    function checkproducts($ProductID) {
        $conn = connectToDatabase();
        $query = "
        SELECT stockitems.StockItemName, ImagePath FROM stockitems
        LEFT JOIN stockitemimages ON stockitemimages.StockItemId = stockitems.StockItemId
        WHERE stockitems.StockItemID = '".$ProductID."' LIMIT 1;
        ";

        $StatementY = mysqli_prepare($conn, $query);
        mysqli_stmt_execute($StatementY);
        $resultY = mysqli_stmt_get_result($StatementY);
        $resultXY = mysqli_fetch_all($resultY, MYSQLI_ASSOC);
        $resultXY = $resultXY[0];
        return($resultXY);
    }

?>
</div>
</body>
</html>
