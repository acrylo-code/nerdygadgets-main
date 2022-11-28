<?php
error_reporting(E_ERROR | E_PARSE);
session_start();
include "database.php";
$databaseConnection = connectToDatabase();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>NerdyGadgets</title>

    <!-- Javascript -->
    <script src="Public/JS/fontawesome.js"></script>
    <script src="Public/JS/jquery.min.js"></script>
    <script src="Public/JS/bootstrap.min.js"></script>
    <script src="Public/JS/popper.min.js"></script>
    <script src="Public/JS/resizer.js"></script>

    <!-- Style sheets-->
    <link rel="stylesheet" href="Public/CSS/style.css" type="text/css">
    <link rel="stylesheet" href="Public/CSS/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="Public/CSS/typekit.css">
</head>
<body>
<div class="Background">
    <div class="row" id="Header">
        <div class="col-2"><a href="./" id="LogoA">
                <div id="LogoImage"></div>
            </a></div>
        <div class="col-10" id="CategoriesBar">
            <ul id="ul-class">
                <lu>
                    <div class="TIM col-5 " id="SearchBar">
                        <form action="/nerdygadgets-main/browse.php" method="get">
                            <input type="text" name="search_string" placeholder="Zoeken naar producten..." id="SearchInput">
                        </form>
                    </div>
                </lu>
                <?php
                $HeaderStockGroups = getHeaderStockGroups($databaseConnection);

                foreach ($HeaderStockGroups as $HeaderStockGroup) {
                    ?>
                    <li>
                        <a href="/nerdygadgets-main/browse.php?category_id=<?php print $HeaderStockGroup['StockGroupID']; ?>"
                           class="HrefDecoration"><?php print $HeaderStockGroup['StockGroupName']; ?></a>
                    </li>
                    <?php
                }
                ?>
                <li>
                    <a href="/nerdygadgets-main/categories.php" class="HrefDecoration">Alle categorieën</a>
                </li>
            </ul>
        </div>
        <div class='navigation_bar'>
            <ul id="ul-class-navigation">
                <li>
                    <a href="/nerdygadgets-main/cart.php" class="HrefDecoration "><i aria-hidden="true">
                    <img src="Public\ProductIMGHighRes\cart.png" alt="cart" width="40" height="32">
                </i>
            </a>
                <span class="badge badge-dark itemCount"  ><?php echo getTotalItemsInCart(); ?></span>
                </li>                
            </ul>
        </div>
    </div>
    <div class="row" id="Content">
        <div class="col-12">
            <div id="SubContent">