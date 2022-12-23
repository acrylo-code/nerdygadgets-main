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
    if(isset($_POST['uitloggen'])) 
    {
        unset ($_SESSION["email"]);
        unset ($_SESSION["password"]);
        unset ($_SESSION["idklant"]);
    } 
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
                    <?php    
                    if(isset($_SESSION['KlantID']) != false){ 
                        print('<a style="left: 500px" href="/nerdygadgets-main/order-functions.php?action=checkout" class="checkoutbtn">Bestellen</a><br><br>');
                        ?>
                        <?php
                        print('<a style="left: 400px"> Je bent ingelogged en kan gelijk bestellen. </a>');
                    } ?>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . "nerdygadgets-main/footer.php"; ?>
