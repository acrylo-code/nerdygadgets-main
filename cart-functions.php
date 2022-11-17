<?php

include_once "database.php";

$databaseConnection = connectToDatabase();
// Checkt string op SQL injectie
function isStringVulnerable($string) {
    $string = strtolower($string);
    $vulnerable = array("select", "drop", "insert", "update", "delete", "alter", "create", "truncate", "union", "join", "where", "like", "--", ";", "=", "*", "&", "!", "?", ">", "<", "~", "`", "\"", "'", "\\", "/", ":", ",", ".", "|", "^", "$", "#", "@", "%", "&", ";", ")", "(", "}", "{", "]", "[", "}", "{");
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
    $cart = getCart();    
    if (!isStringVulnerable($stockItemID)){
        if(array_key_exists($stockItemID, $cart)){
            $cart[$stockItemID] += 1;                  
        }else{
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
    $cart = getCart();
    if (!isStringVulnerable($productId)){
        if(array_key_exists($productId, $cart)){
            if($quantity <= 0){
                unset($cart[$productId]);
            } else {
                $cart[$productId] = $quantity;
            }
        }
    }
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
        // Voorbeeld url: /nerdygadgets-main/cart.php?action=addToCart&productId=1
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