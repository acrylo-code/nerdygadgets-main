<?php

include __DIR__ . "/header.php";

?>

<!-- Create a basic bootstrap form -->
<form action="add-discount.php" method="get" style="padding: 0 150px; margin-top: 30px;">
    <h1></h1>
    <div class="form-group">
        <label for="StockItemID">Product ID</label>
        <input type="number" class="form-control" id="StockItemID" name="StockItemID" placeholder="">
        
    </div>
    <div class="form-group">
        <input type="hidden" value="addProductDiscount" name="method" id="method">
    </div>
    <div class="form-group">
        <label for="discountAmount">Korting</label>
        <input type="text" class="form-control" id="discountAmount" name="discountAmount" placeholder="Korting">
    </div>
    <div class="form-group">
        <label for="discountValidUntil">Geldig tot</label>
        <input type="datetime" class="form-control" id="discountValidUntil" name="discountValidUntil" value="<? echo date('Y-m-d H:i:s'); ?>" placeholder="Geldig tot">
    </div>
    <button type="submit" class="btn btn-primary">Opslaan</button>
</form>