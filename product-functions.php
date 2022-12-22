<?php 
    include_once "header.php";

    function checkDiscount($stockItemID){
        $databaseConnection = connectToDatabase();
        $sql = "SELECT * FROM stockitems WHERE discount > 0 AND StockItemID = ? AND discountValidUntil > DATE(NOW())";
        $statement = mysqli_prepare($databaseConnection, $sql);
        mysqli_stmt_bind_param($statement, "i", $stockItemID);
        mysqli_stmt_execute($statement);
        $result = mysqli_stmt_get_result($statement);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($statement);
        mysqli_close($databaseConnection);
        if($row){
            return true;
        } else{
            return false;
        }
    }

    function setDiscount($stockItemID, $discountAmount, $discountValidUntil){
        $databaseConnection = connectToDatabase();
        // Check of de prijs een percentage bevat.
        if(strpos($discountAmount, "%") !== false){
            $discountAmount = str_replace("%", "", $discountAmount);
            $sql = "UPDATE stockitems SET discount = ?, discountIsPercentage = 1, discountValidUntil = ? WHERE StockItemID = ?";
            $statement = mysqli_prepare($databaseConnection, $sql);
            mysqli_stmt_bind_param($statement, "dsi", $discountPercentage, $discountValidUntil, $stockItemID);
            mysqli_stmt_execute($statement);
            mysqli_stmt_close($statement);
            mysqli_close($databaseConnection);
        } else{
            // prijs bevat geen percentage, trek het van prijs incl. btw af.
            $sql = "UPDATE stockitems SET discount = ?, discountIsPercentage = 0, discountValidUntil = ? WHERE StockItemID = ?";
            $statement = mysqli_prepare($databaseConnection, $sql);
            mysqli_stmt_bind_param($statement, "dsi", $discountAmount, $discountValidUntil, $stockItemID);
            mysqli_stmt_execute($statement);
            mysqli_stmt_close($statement);
            mysqli_close($databaseConnection);
        }
    }

    function removeDiscount($stockItemID){
        $databaseConnection = connectToDatabase();
        $sql = "UPDATE stockitems SET discount = 0, discountIsPercentage = 0, discountValidUntil = DATE('0000-00-00') WHERE StockItemID = ?";
        $statement = mysqli_prepare($databaseConnection, $sql);
        mysqli_stmt_bind_param($statement, "i", $stockItemID);
        mysqli_stmt_execute($statement);
        mysqli_stmt_close($statement);
        mysqli_close($databaseConnection);
    }

    function getDiscountedPrice($stockItemID){
        $databaseConnection = connectToDatabase();
        $sql = "SELECT * FROM stockitems WHERE discount > 0 AND StockItemID = ? AND discountValidUntil > NOW()";
        $statement = mysqli_prepare($databaseConnection, $sql);
        mysqli_stmt_bind_param($statement, "i", $stockItemID);
        mysqli_stmt_execute($statement);
        $result = mysqli_stmt_get_result($statement);
        $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $result = $result[0];
        $discount = $result['discount'];
        $isPercentage = $result['discountIsPercentage'] == 1 ? true : false;
        $unTaxedPrice = $result['RecommendedRetailPrice'];
        $taxedPrice = $unTaxedPrice * (($result['TaxRate'] / 100) + 1);
        $discountedPrice = 0;
        if($isPercentage){
            $discount = $taxedPrice * ($discount / 100);
            $discountedPrice = $taxedPrice - $discount;
        } else{
            $discountedPrice = $taxedPrice - $discount;
        }
        return round($discountedPrice, 2);
    }
    
?>