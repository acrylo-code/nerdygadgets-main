<?php include __DIR__ . "/header.php"; ?>
<?php
    if(isset($_GET['action'])){
        handleCartAction($_GET['action']);
    }
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

    <?php
    $totalPrice = 0;
    foreach(getCartItems() as $productId => $product){
    $totalPrice += $product['SellPrice'] * $product['quantityInCart'];}
    ?>



</style>
<div class="row">
    <div id="CenteredContent">
        <div id="ArticleHeader">
            <h2 class="StockItemNameViewSize StockItemName">Jouw bestelling.</h2>
            <!-- Create a table to display the products in cart -->
            <table class="table table-striped" style="color: white;">
                        <thead>
                            <tr>   
                                <th scope="col">Product</th>
                                <th scope="col">Aantal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $totalPrice = 0;
                                foreach(getCartItems() as $productId => $product){
                                    $totalPrice += $product['SellPrice'] * $product['quantityInCart'];
                                    ## haal de korting eraf
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $product['StockItemName']; ?>
                                        </td>
                                        <!-- format the price as: €49,99 -->
                                        <td><?php echo ($product['quantityInCart']); ?></td>
                                        <!-- display total for that current item(s) -->
                                    </tr>
                                    <?php
                                }
                            ?>
                        </tbody>
                    </table>
                    <?php    if(isset($_SESSION['KlantID']) == false){ print('Je bent ingelogged!');}?>
            <form method="post" action="/nerdygadgets-main/order-functions.php">
            <div class="row">
                <div class="col-md-5">
                    <div class="orderpage__heading">
                        <h4>Persoonlijke gegevens</h4>
                        <?php print("<p style=color:red>".$_SESSION['error']."</p>");
                        $_SESSION['error'] = "";
                        ?>
                    </div>
                    <div class="orderpage__form">
                        <div class="form-groups mb-4">
                            <label>Voornaam *</label>
                            <input type="text" name="Voornaam" required="" value="Voornaam">
                        </div>
                        <div class="d-flex">
                            <div class="form-groups mb-4 mr-3">
                                <label>Achternaam *</label>
                                <input type="text" name="Achternaam" required="" value="Achternaam">
                            </div>
                            <div class="form-groups mb-2">
                                <label>Tussenvoegsel</label>
                                <input type="text" name="Tussenvoegsel">
                            </div>
                        </div>
                        <div class="form-groups mb-2">
                            <label>Email adres *</label>
                            <input type="email" name="Email" required="" value="test@gmail.com">
                        </div>
                        <div class="form-groups mb-4">
                            <label>Telefoonnummer</label>
                            <input type="tel" name="Telefoonnummer" value="06-12345678">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 offset-1">
                    <div class="orderpage__heading">
                        <h4>Verzendadres</h4>
                    </div>
                    <div class="orderpage__form">
                        <div class="form-groups mb-4 d-flex">
                            <div class="orderpage__form-item mr-3">
                                <label>Straatnaam *</label>
                                <input type="text" name="Adres" value="straatnaam">
                            </div>
                            <div class="orderpage__form-item">
                                <label>Huisnummer *</label>
                                <input type="number" name="Huisnummer" value="2">
                            </div>
                        </div>
                        <div class="form-groups mb-4 d-flex">
                            <div class="orderpage__form-item mr-3">
                                <label>Stad *</label>
                                <input type="text" name="Woonplaats" value="stad">
                            </div>
                            <div class="orderpage__form-item">
                                <label>Postcode *</label>
                                <input type="text" name="Postcode" value="1234DE">
                            </div>
                        </div>
                        <div class="mt-5 d-flex">
                            <div class="col-6" style="position: relative;">
                            <h3 class="StockItemNameViewSize StockItemName" style="position: absolute; bottom: -50px; left: 0;">Totaal: <?php echo '€' . number_format($_SESSION['totalPrice'], 2, ',', '.'); ?></h3>
                                <input type="submit" value="Afrekenen" href="/nerdygadgets-main/order-functions.php" class="btn btn-primary checkoutbtn" style="position: absolute; bottom: -100px; right: -300;">* zijn verplichte velden.</a>
                                <!--<input type="button" value="Terug" href="/nerdygadgets-main/cart.php" class="btn btn-primary checkoutbtn" style="position: absolute; bottom: -100px; right: 300;"></a> -->
                            </div>
                        </div>
                    </form>  
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . "nerdygadgets-main/footer.php"; ?>
