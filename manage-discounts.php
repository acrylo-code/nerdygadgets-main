<?php

include_once "header.php";

$discounts = getAllDiscounts();

function discountIsPercentage($product){
    if($product['DiscountIsPercentage'] == 1){
        return true;
    } else{
        return false;
    }
}

?>
<!-- Create a basic bootstrap table with the following columns: Korting, Geldig tot, Verwijderen, Aanpassen -->
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 style="text-align: center;">Kortingen</h1>
            <table class="table" style="color: white;">
                <!-- Create a "Toevoegen" button on the top right, relative to the table. -->
                <a href="add-discount.php" class="btn btn-primary" style="float: right; margin-bottom: 10px;">Toevoegen</a>
                <thead>
                    <tr>
                        <th scope="col">StockItemID</th>
                        <th scope="col">Korting (In procent of euro)</th>
                        <th scope="col">Huidige prijs</th>
                        <th scope="col">Geldig tot</th>
                        <th scope="col">Verwijderen</th>
                        <th scope="col">Aanpassen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php  foreach($discounts as $discount): ?>
                        <pre>
                        <?php $currentPrice = getProductPrice($discount['StockItemID']); ?>
                        </pre>
                        <tr>
                            <td><? echo $discount['StockItemID'] ?></td>
                            <td><?php if(discountIsPercentage($discount)){ echo $discount['discount'] . "%"; } else { echo "€" . $discount['discount']; } ?></td>
                            <td><?php echo "€ " . getProductPrice($discount['StockItemID']); ?></td>
                            <td><?php  if( $discount['discountValidUntil'] == "0000-00-00 00:00:00" ){  echo "Verlopen"; } else{ echo $discount['DiscountValidUntil']; }; ?></td>
                            <td><a href="manage-discounts.php?method=removeProductDiscount&StockItemID=<?php echo $discount['StockItemID']; ?>">Verwijderen</a></td>
                            <td><a href="edit-discount.php?StockItemID=<?php echo $discount['StockItemID']; ?>">Aanpassen</a></td>
                        </tr>
                    <?php  endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>