<?php include __DIR__ . "/header.php"; ?>
<?php include_once __DIR__ . "/cart_functions.php"; ?>

<?php
    $conn = connectToDatabase();
?>

<style>

    .changeQuantity, .addQuantity, .removeQuantity, .checkoutbtn {
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

    .changeQuantity:hover, .addQuantity:hover, .removeQuantity:hover, .checkoutbtn:hover {background-color: #3741ff
    }

    .changeQuantity:active, .addQuantity:active, .removeQuantity:active, .checkoutbtn:active {
        background-color: #3741ff;
        box-shadow: 0 5px #666;
        transform: translateY(4px);
    }
    
    .changeQuantity{
        margin: 15px 0;
    }

    .addQuantity{
        padding: 15px 0;
    }

    .removeQuantity{
        margin-top: 15px;
    }


</style>
<div class="row">
    <div id="CenteredContent">
        <div id="ArticleHeader">
            <h2 class="StockItemNameViewSize StockItemName">Winkelmandje</h2>
            <!-- Create a table to display the products in cart -->
            <table class="table table-striped" style="color: white;">
                        <thead>
                            <tr>
                                <th scope="col">Afbeelding</th>
                                <th scope="col">Product</th>
                                <th scope="col">Aantal</th>
                                <th scope="col">Prijs</th>
                                <th scope="col">Totaal</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $totalPrice = 0;
                                foreach(getCartItems() as $productId => $product){
                                    $totalPrice += $product['SellPrice'] * $product['quantityInCart'];
                                    ?>
                                    <tr>
                                        <td>
                                            <img style="max-width: 200px;" src="/nerdygadgets-main/Public/StockGroupIMG/<?php echo $product['BackupImagePath'] ?>" alt="">
                                        </td>
                                        <td>
                                           <?php echo $product['StockItemName']; ?>
                                        </td>
                                        <td>
                                            <form action="/nerdygadgets-main/cart.php" method="get">
                                                <input type="number" name="quantity" value="<?php echo $product['quantityInCart']; ?>">
                                                <input type="hidden" name="action" value="changeQuantity">
                                                <input type="hidden" name="productId" value="<?php echo $product['StockItemID']; ?>">
                                                <input type="submit" value="Wijzig" class="changeQuantity">   
                                            </form>
                                        </td>
                                        <!-- format the price as: €49,99 -->
                                        <td><?php echo '€' . number_format(getProductPrice($product['StockItemID']), 2, ',', '.'); ?></td>
                                        <!-- display total for that current item(s) -->
                                        <td><?php echo '€' . number_format(getProductPrice($product['StockItemID']) * $product['quantityInCart'], 2, ',', '.'); ?></td>
                                        <!-- Totaal laten zien: -->
                                        <td>
                                            <form action="/nerdygadgets-main/cart.php">
                                                <input type="hidden" name="action" value="addOneToCart">
                                                <input type="hidden" name="productId" value="<?php echo $product['StockItemID']; ?>">
                                                <input type="submit" value="+" class="addQuantity">
                                            </form>

                                            <form action="/nerdygadgets-main/cart.php">
                                                <input type="hidden" name="action" value="removeOneFromCart">
                                                <input type="hidden" name="productId" value="<?php echo $product['StockItemID']; ?>">
                                                <input type="submit" value="-" class="removeQuantity">
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            ?>
                        </tbody>
                    </table>    
                    <div class="row">
                <div class="col-12" style="position: relative;">
                <?php if($totalPrice > 0){ ?>
                    <h3 style="position: absolute; bottom: 15px; right: 0;"class="StockItemName">Subtotaal: <?php echo '€' . number_format($totalPrice, 2, ',', '.'); ?></h3>
                            <!-- add a button to view cart -->
                            <?php
                            //kijk of de kortingscode geplaatst is
                            if (isset($_POST["Kortingscode"])) {
                                $code = $_POST["Kortingscode"];
                                // controleer op sql injecties
                                if (isStringVulnerable($code)) {
                                    echo "Kortingscode is niet geldig";
                                } else {
                                    //check met databaseof de kortingscode bestaat
                                    $korting = selectDiscountCode($code, $conn);
                                    //als het een niet-procentuele korting is
                                    if ($korting[0]['Type'] == "aantal") {
                                        //controleer of de korting niet in de min gaat
                                        if ($korting[0]['Aantal'] > $totalPrice) {
                                            echo "Kortingscode is niet geldig bij dit bedrag";
                                        } else {
                                            //haal de kortingscode van de prijs af en bij de speciaale korting kijken we of de prijs meer dan 100 euro is
                                            if ($totalPrice >= 100 && $korting[0]['Code'] == "5EUROKORTING") {
                                                $totalPrice = $totalPrice - $korting[0]['Aantal'];
                                            } elseif ($korting[0]['Code'] != "5EUROKORTING") {
                                                $totalPrice = $totalPrice - $korting[0]['Aantal'];
                                            } else {
                                                echo "Kortingscode is niet geldig bij dit bedrag";
                                            }
                                        }
                                        //als het een procentuele korting is
                                    } elseif ($korting[0]['Type'] == "procent") {
                                        //kijk if de korting niet boven 100% uitkomt
                                        if ($korting[0]['Aantal'] > 100) {
                                            echo "Kortingscode is niet geldig";
                                        } else {
                                            //haal de korting van de prijs af
                                            $totalPrice = $totalPrice * ((100 - $korting[0]['Aantal']) / 100);
                                        }
                                    } else {
                                        echo "Kortingscode is niet geldig";
                                    }
                                }
                                //sla de totaalprijs op in de sessie.
                            } $_SESSION['totalPrice'] = $totalPrice;
                            ## korting?>
                            
                            <form action="/nerdygadgets-main/cart.php" method="post">
                                <input style="position: absolute; bottom: -20px; right: 0; width: 170px; height: 30px;" placeholder = "Kortingscode" type=Titel name="Kortingscode"><br><br>
                                <input type="submit" value="Activeer" href="/nerdygadgets-main/cart.php" class="btn btn-primary checkoutbtn" style="position: absolute; right: -90px; top: 75px; width: 80px; height: 40px;"></a>
                            </form>
                            <h3 style="position: absolute; bottom: -65px; right: 0;"class="StockItemName">Totaal: <?php echo '€' . number_format($totalPrice, 2, ',','.'); ?></h3>
                            
                                <a href="/nerdygadgets-main/order.php" class="btn btn-primary checkoutbtn" style="position: absolute; bottom: -110px; right: 0;">Afrekenen</a>
                            <a style="position: absolute; bottom: -190px; right: 0; padding-left: 100px">⠀</a>
                            <br><br>
                            <?php } ?>
                    </div>
                 </div>
            </div>                          
        </div>
    </div>
</div>
<?php include __DIR__ . "nerdygadgets-main/footer.php"; 


?>