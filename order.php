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
            <h2 class="StockItemNameViewSize StockItemName">Winkelmandje</h2>
            <!-- Create a table to display the products in cart -->
            <table class="table table-striped" style="color: white;">
                        <thead>
                            <tr>   
                                <th scope="col">Product</th>
                                <th scope="col">Prijs p/s</th>
                                <th scope="col">Totaal</th>
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
                                            <?php echo $product['StockItemName']; ?>
                                        </td>
                                        <!-- format the price as: €49,99 -->
                                        <td><?php echo '€' . number_format($product['SellPrice'], 2, ',', '.'); ?></td>
                                        <!-- display total for that current item(s) -->
                                        <td><?php echo '€' . number_format($product['SellPrice'] * $product['quantityInCart'], 2, ',', '.'); ?></td>
                                        <!-- Totaal laten zien: -->
                                    </tr>
                                    <?php
                                }
                            ?>
                        </tbody>
                    </table>    
                    <div class="row">
                        <div class="col-12" style="position: relative;">
                        <h3 class="StockItemNameViewSize StockItemName" style="position: absolute; bottom: -50px; right: 0;">Totaal: <?php echo '€' . number_format($totalPrice, 2, ',', '.'); ?></h3>
                            <a href="/nerdygadgets-main/cart.php" class="btn btn-primary checkoutbtn" style="position: absolute; bottom: -100px; right: 0;">Terug naar winkelmand</a>
                    </div>
        </div>
    </div>
</div>
<?php include __DIR__ . "nerdygadgets-main/footer.php"; ?>