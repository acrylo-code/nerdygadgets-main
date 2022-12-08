<?php

include_once "database.php";

$databaseConnection = connectToDatabase();
// Checkt string op SQL injectie
function isStringVulnerable($string) {
    $string = strtolower($string);
    $vulnerable = array("select", "drop", "insert", "update", "delete", "alter", "create", "truncate",
     "union", "join", "where", "like", "--", ";", "=", "*", "&", "!", "?", ">", "<", "~", "`", "\"", "'",
      "\\", "/", ":", ",", ".", "|", "^", "$", "#", "@", "%", "&", ";", ")", "(", "}", "{", "]", "[", "}", "{");
    foreach ($vulnerable as $vul) {
        if (strpos($string, $vul) !== false) {
            return true;
        }
    }
    return false;
}

function getCart(){
    if(isset($_SESSION['cart'])){
        return $_SESSION['cart'];
    } else{
        return [];
    }
}

function saveCart($cart){
    $_SESSION["cart"] = $cart;
}


function addToCart($stockItemID){
    $product = getStockItem($stockItemID, connectToDatabase())['QuantityOnHand'];
    $stock = (int) filter_var($product, FILTER_SANITIZE_NUMBER_INT);

    // Haalt het winkelwagentje op (key-value array van StrockItemID: aantal, heeft geen info over de productinformatie)
    $cart = getCart();
    // Checkt de string op SQL injectie en gaat verder als de string veilig is.
    if (!isStringVulnerable($stockItemID)){
        // Checkt of het product in ons winkelwagentje bestaat
        if(array_key_exists($stockItemID, $cart)){
            // check if the the amount in cart + 1 is more than the stock
            if($cart[$stockItemID] > $stock){
                $cart[$stockItemID] = $stock;
            } else{
                $cart[$stockItemID] += 1;
            }
        }else{
            // Voeg anders aantal 1 van dat product toe.
            $cart[$stockItemID] = 1;
        }
    }
    saveCart($cart);
}

function removeOneFromCart($productId){
    $cart = getCart();

    if (!isStringVulnerable($productId)){
        if(array_key_exists($productId, $cart)){
            // if product quantity is 1, remove product from array
            if($cart[$productId] == 1){
                unset($cart[$productId]);
            } else {
                $cart[$productId]--;
            }
            
        }
        saveCart($cart);   
    }  
}

function addOneToCart($productId){
    $cart = getCart();
    
    if (!isStringVulnerable($productId)){
        if(array_key_exists($productId, $cart)){ 
            $cart[$productId]++;
        }
    } 
    saveCart($cart);
}

function getCartItems(){
    $cart = getCart();
    $cartItems = [];
    foreach ($cart as $productId => $quantity){
        $row = getStockItem($productId, connectToDatabase());
        $row['quantityInCart'] = $quantity;
        $row['productId'] = $productId;
        array_push($cartItems, $row);
        // var_dump($cartItems);
    }
    return $cartItems;
}

function changeQuantity($productId, $quantity){
    $product = getStockItem($productId, connectToDatabase())['QuantityOnHand'];
    $stock = (int) filter_var($product, FILTER_SANITIZE_NUMBER_INT);


    // Haalt het winkelwagentje op (key-value array van StrockItemID: aantal, heeft geen info over de productinformatie)
    $cart = getCart();
    // Checkt de string op SQL injectie en gaat verder als de string veilig is.
    if (!isStringVulnerable($productId)){
        // Checkt of het product in ons winkelwagentje bestaat
        if(array_key_exists($productId, $cart)){
            // Als aantal gelijk aan of lager dan 0 is, haal het product uit het winkelwagentje.
            if($quantity <= 0){
                unset($cart[$productId]);
            } elseif($quantity >= $stock){
                $cart[$productId] = $stock;
            } else {
                // Pas anders het aantal aan naar de opgegeven $quantitiy
                $cart[$productId] = $quantity;
            }
        }
    }
    // Sla het winkelwagentje op in de sessie
    saveCart($cart);
}

//fucntion to check if cart contains any items
function cartContainsItems(){
    $cart = getCart();
    if (count($cart) > 0){
        return true;
    } else {
        return false;
    }
}
// Zelfadhandelende functie die het winkelwagentje regelt. 
function handleCartAction($action){
    $productId = $_GET['productId'];
    $quantity = $_GET['quantity'];

    switch ($action) {
        // Vtoorbeeld url: /nerdygadges-main/cart.php?action=addToCart&productId=1
        // Required: productId
        case 'addToCart':
            if(isset($productId)){
                addToCart($productId);
            }
            header("Location: cart.php");
            break;
        
        // Voorbeeld url: /nerdygadgets-main/cart.php?action=removeOneFromCart&productId=1
        // Required: productId
        case 'removeOneFromCart':
            if(isset($productId)){
                removeOneFromCart($productId);
            }
            header("Location: cart.php");
            break;
        
        // Voorbeeld url: /nerdygadgets-main/cart.php?action=addOneToCart&productId=1
        // Required: productId
        case 'addOneToCart':
            if(isset($productId)){
                addOneToCart($productId);
            }
            header("Location: cart.php");
            break;
        // Voorbeeld url: /nerdygadgets-main/cart.php?action=addOneToCart&productId=1&quantity=1
        // Required: productId, quantity
        case 'changeQuantity':
            if(isset($productId) && isset($quantity)){
                changeQuantity($productId, $quantity);
            }
            header("Location: cart.php");
            break;
    }
}

// make a function for the total number of items including quantity in cart
function getTotalItemsInCart(){
    $cart = getCart();
    $totalItems = 0;
    foreach ($cart as $productId => $quantity){
        $totalItems += $quantity;
    }
    return $totalItems;
}