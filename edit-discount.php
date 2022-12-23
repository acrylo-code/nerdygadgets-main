<?php 
    include_once "header.php";

    $product = getProduct($_GET['StockItemID']);

    function discountIsPercentage($product){
        if($product['DiscountIsPercentage'] == 1){
            return true;
        } else{
            return false;
        }
    }

    $isPercentage = discountIsPercentage($product);
    
?>
<div id="content" style="margin: 0 150px; margin-top: 80px;">
    <div class="content-box">
        <!-- Create a basic bootstrap form -->
        <form action="edit-discount.php" method="get">
            <h1><? echo $product['StockItemName'];  ?></h1>
            <?php if(hasErrorMessages()){ echo getErrorMessages(); clearErrorMessages(); } ?>
            <div class="form-group">
                <input type="hidden" class="form-control" id="StockItemID" value="<? echo $product['StockItemID'] ?>" name="StockItemID" placeholder="Product ID">
            </div>
            <div class="form-group">
                <label for="discountAmount">Korting (voor procenten, gebruik %, anders is korting in euro van de productprijs.</label>
                <input type="text" class="form-control" id="discountAmount" value="<? if(!$isPercentage){ print($product['discount']); }else{ echo $product['discount'] . "%"; } ?>" name="discountAmount" placeholder="Korting">
            </div>
            <div class="form-group">
                <label for="discountValidUntil">Geldig tot</label>
                <input type="datetime" class="form-control" id="discountValidUntil" value="<? echo $product['DiscountValidUntil'] ?>" name="discountValidUntil" placeholder="Geldig tot">
            </div>
            <button type="submit" class="btn btn-primary">Opslaan</button>
        </form>
    </div>
</div>
<pre>
<? 
    
?>
</pre>