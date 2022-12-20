<?php
include __DIR__ . "/headerminimal.php";
include __DIR__ . "/user-functions.php";

if(isLoggedIn()){
    
} else{
    header('Location: http://127.0.0.1:8080/nerdygadgets-main/login.php');
}
?>

<!DOCTYPE html>
<html>
<head>
    <link>
<title>Myorders</title>
</head>
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
?>

        <div class="orderbox">
            <?php print("<h3>$productNaam</h3>");?>
            <?php print("<p>" . "Bestelnummer: ". "$orderid" . " </p>");?>
            <?php print("<p>" . "Besteldatum: " . "$orderdatum". "</p>");?>
            
        <div class="prijs">
        <?php
            print("<h3>" . "€" . "$prijs" . "</h3>");
        ?>

    </div>
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
<button id="review-button" class="review-button" type="button">Wil je een review over dit product achterlaten? </button>


    <div id="review-form" style="display: none;">
            <div class="Reviewtext"> Laat hier een review achter:
                </div>
            <form method="post" action="review.php">
                <div class="form-group">
                <input type="text" class="form-control" id="title" name="title" placeholder="Titel van uw review" required>
            <h3>Hoe zou u ons product beoordelen?</h3>
            <div class="center" >
                    <div class="stars">
                        <input type="radio" id="five" name="rate" value="5" />
                        <label for="five"></label>
                        <input type="radio" id="four" name="rate" value="4" />
                        <label for="four"></label>
                        <input type="radio" id="three" name="rate" value="3" />
                        <label for="three"></label>
                        <input type="radio" id="two" name="rate" value="2" />
                        <label for="two"></label>
                        <input type="radio" id="one" name="rate" value="1" required/>
                        <label for="one"></label>
                    </div>
                </div>
                <h3> Motiveer uw beoordeling:</h3>
                <div class="form-group">
                    <textarea class="form-control" id="ReviewText" name="ReviewText" rows="4" required ></textarea>
                </div>
                
                    <input type="submit" class="Reviewsubmit" value="Verstuur">
            
            </form>
        </div>
    </div>

