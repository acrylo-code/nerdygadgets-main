<?php
include __DIR__ . "/header.php";
include __DIR__ . "/user-functions.php";

if(isLoggedIn()){
    print("You're logged in");
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
<script> 
  document.addEventListener("DOMContentLoaded", function() {
  // Get references to the review button and form
  var reviewButton = document.getElementById("review-button");
  var reviewForm = document.getElementById("review-form");

  // Add a click event listener to the review button
  reviewButton.addEventListener("click", function() {
    // Show the review form
    reviewForm.style.display = "block";
  });
});
0
</script>
<body>
<h1>Mijn bestellingen</h1>

<form method="GET"> 
<input type="text" name="id" id="devidd" required>
<input type="submit" value="devidinlog">

</form> 


<?php


function myorderss(){
    $devid = $_GET['id'];   
    $conn = connectToDatabase();
    $query4 = "
    SELECT 
    bestellingen_rows.ProductID, bestellingen_rows.Prijs, bestellingen_rows.Aantal, 
    bestellingen_rows.OrderID, bestellingen.OrderDatum, bestellingen.OrderTotaal, bestellingen_rows.OrderRegelID, bestellingen.Betaald FROM bestellingen
    JOIN bestellingen_rows ON bestellingen_rows.OrderRegelID = bestellingen.OrderID 
    WHERE bestellingen.KlantID = '".$devid."'  ";

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
        <a href="/nerdygadgets-main/view.php?id=<?php echo($productID);?>&LeaveReview=true" class="btn btn-primary btn_nerdy">Schrijf een review</a>
        </div>
        </div>
        <div>
            <img style="max-width: 100px;" src="/nerdygadgets-main/Public/StockItemIMG/<?php echo $image ?>" alt="">
        </div>
        <?php
    }
    return($resultlogin);
}

$result = myorderss();

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
