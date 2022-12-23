<?php 

    function checkDiscount($stockItemID){
        $databaseConnection = connectToDatabase();
        $sql = "SELECT * FROM stockitems WHERE discount > 0 AND StockItemID = ?";
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

    function getAllDiscounts(){
        // get all stockitems where discount > 0
        $databaseConnection = connectToDatabase();
        $sql = "SELECT * FROM stockitems WHERE discount > 0";
        $statement = mysqli_prepare($databaseConnection, $sql);
        mysqli_stmt_execute($statement);
        $result = mysqli_stmt_get_result($statement);
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_stmt_close($statement);
        mysqli_close($databaseConnection);
        return $row;
    }

    function setDiscount($stockItemID, $discountAmount, $discountValidUntil){
        $databaseConnection = connectToDatabase();
        // Check of de prijs een percentage bevat.
        if(strpos($discountAmount, "%") !== false){
            $discountAmt = str_replace("%", "", $discountAmount);
            $sql = "UPDATE stockitems SET discount = ?, discountIsPercentage = 1, discountValidUntil = ? WHERE StockItemID = ?";
            $statement = mysqli_prepare($databaseConnection, $sql);
            mysqli_stmt_bind_param($statement, "ssi", $discountAmt, $discountValidUntil, $stockItemID);
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
        $sql = "SELECT * FROM stockitems WHERE discount > 0 AND StockItemID = ?";
        $statement = mysqli_prepare($databaseConnection, $sql);
        mysqli_stmt_bind_param($statement, "i", $stockItemID);
        mysqli_stmt_execute($statement);
        $result = mysqli_stmt_get_result($statement);
        $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $res = $result[0];
        $discount = $res['discount'];
        $isPercentage = $res['discountIsPercentage'] == 1 ? true : false;
        $unTaxedPrice = $res['RecommendedRetailPrice'];
        $taxedPrice = $unTaxedPrice * (($res['TaxRate'] / 100) + 1);
        $discountedPrice = 0;
        if($isPercentage){
            $discount = $taxedPrice * ($discount / 100);
            $discountedPrice = $taxedPrice - $discount;
        } else{
            $discountedPrice = $taxedPrice - $discount;
        }
        return round($discountedPrice, 2);
    }

    function getProductPrice($stockItemID){
        // Product has discount
        if(checkDiscount($stockItemID)){
            return getDiscountedPrice($stockItemID);
        } else{
        // Product does not have discount
            $databaseConnection = connectToDatabase();
            $sql = "SELECT * FROM stockitems WHERE StockItemID = ?";
            $statement = mysqli_prepare($databaseConnection, $sql);
            mysqli_stmt_bind_param($statement, "i", $stockItemID);
            mysqli_stmt_execute($statement);
            $result = mysqli_stmt_get_result($statement);
            $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $result = $result[0];
            $unTaxedPrice = $result['RecommendedRetailPrice'];
            $taxedPrice = $unTaxedPrice * (($result['TaxRate'] / 100) + 1);
            return round($taxedPrice, 2);
        }
    }

    function getProduct($StockItemID){
        $databaseConnection = connectToDatabase();
        $sql = "SELECT * FROM stockitems WHERE StockItemID = ?";
        $statement = mysqli_prepare($databaseConnection, $sql);
        mysqli_stmt_bind_param($statement, "i", $StockItemID);
        mysqli_stmt_execute($statement);
        $result = mysqli_stmt_get_result($statement);
        $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $result = $result[0];
        mysqli_stmt_close($statement);
        mysqli_close($databaseConnection);
        return $result;
    }

    function handleDiscount($action){
        switch ($action) {
            case 'addProductDiscount':
                if(
                    // All values are supplied
                    isset($_GET['StockItemID']) &&
                    isset($_GET['discountAmount']) &&
                    isset($_GET['discountValidUntil'])
                ){
                    setDiscount($_GET['StockItemID'], $_GET['discountAmount'], $_GET['discountValidUntil']);
                    header("Location: manage-discounts.php");
                    // 
                } else{
                    addErrorMessage("Vul a.u.b. alle benodigde velden in.");
                    header("Location: manage-discounts.php");
                }
                break;

            case 'removeProductDiscount':
                if(isset($_GET['StockItemID'])){
                    // Alle benodigde data is geleverd
                    removeDiscount($_GET['StockItemID']);
                    header("Location: manage-discounts.php");
                } else{
                    addErrorMessage("Vul a.u.b. het StockItemID in van het product.");
                    header("Location: manage-discounts.php");
                }
                break;

            case 'changeProductDiscount':
                if(
                    // All values are supplied
                    isset($_GET['StockItemID']) &&
                    isset($_GET['discountAmount']) &&
                    isset($_GET['discountValidUntil'])
                ){
                    // 
                } else{
                    addErrorMessage("Vul a.u.b. alle benodigde velden in.");
                    header("Location: manage-discounts.php");
                }
                break;
        }
    }
?>