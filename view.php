<!-- dit bestand bevat alle code voor de pagina die één product laat zien -->
<?php
include __DIR__ . "/header.php";
$product = getProduct($_GET['id']);
?>
<link rel="stylesheet" href="/nerdygadgets-main/Public/CSS/review.css">
<?php
$StockItem = getStockItem($_GET['id'], connectToDatabase());
$StockItemImage = getStockItemImage($_GET['id'], connectToDatabase()); 
$ColdroomTemp = getColdroomTemp(connectToDatabase());

foreach ($ColdroomTemp as $temp) {
    $temp = $temp['Temperature'];
    //calc avarige temp
    $gemTemp += $temp;
 }
$gemTemp /= count($ColdroomTemp);
$gemTemp = "<a class='StockItemName'>".$gemTemp."°C</a>, gemeten op ".$ColdroomTemp[0]['RecordedWhen'].".";
?>
<style>
    .button {
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

    .button:hover {background-color: #3741ff
    }

    .button:active {
        background-color: #3741ff;
        box-shadow: 0 5px #666;
        transform: translateY(4px);
    }
</style>

<div style="display: none;" id="discount"><?php echo $product['discount']; ?></div>
<div style="display: none;" id="DiscountValidUntil"><?php echo $product['DiscountValidUntil']; ?></div>
<div style="display: none;" id="DiscountIsPercentage"><?php echo $product['DiscountIsPercentage']; ?></div>

<script>
    var discount = document.getElementById("discount").innerHTML;
    var DiscountValidUntil = document.getElementById("DiscountValidUntil").innerHTML;
    var DiscountIsPercentage = document.getElementById("DiscountIsPercentage").innerHTML;
    console.log(discount, DiscountValidUntil, DiscountIsPercentage);
    
    // If discount is not null, and the discount is still valid, change div#DiscountValidUntil to show how long the discount is valid, and count down.
    if (discount != null && DiscountValidUntil > new Date().toISOString().slice(0, 10)) {
        var countDownDate = new Date(DiscountValidUntil).getTime();
        var x = setInterval(function () {
            var now = new Date().getTime();
            var distance = countDownDate - now;
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            document.getElementById("DiscountCountdown").innerHTML = "Korting is nog " +days + " dag(en) " + hours + " uur "
                + minutes + " minuten geldig.";
            if (distance < 0) {
                clearInterval(x);
                document.getElementById("DiscountCountdown").innerHTML = "Korting verlopen";
            }
        }, 1000);
    }

    // Change div#DiscountCountdown text color every 0.5 seconds from red to white;
    setInterval(function () {
        var element = document.getElementById("DiscountCountdown");
        element.classList.toggle("text-danger");
    }, 500);
    
</script>

<div id="CenteredContent">
    <?php
    if ($StockItem != null) {
        ?>
        <?php
        if (isset($StockItem['Video'])) {
            ?>
            <div id="VideoFrame">
                <?php print $StockItem['Video']; ?>
            </div>
        <?php }
        ?>


        <div id="ArticleHeader">
            <?php
            if (isset($StockItemImage)) {
                // één plaatje laten zien
                if (count($StockItemImage) == 1) {
                    ?>
                    <div id="ImageFrame"
                         style="background-image: url('/nerdygadgets-main/Public/StockItemIMG/<?php print $StockItemImage[0]['ImagePath']; ?>'); background-size: 300px; background-repeat: no-repeat; background-position: center;"></div>
                    <?php
                } else if (count($StockItemImage) >= 2) { ?>
                    <!-- meerdere plaatjes laten zien -->
                    <div id="ImageFrame">
                        <div id="ImageCarousel" class="carousel slide" data-interval="false">
                            <!-- Indicators -->
                            <ul class="carousel-indicators">
                                <?php for ($i = 0; $i < count($StockItemImage); $i++) {
                                    ?>
                                    <li data-target="#ImageCarousel"
                                        data-slide-to="<?php print $i ?>" <?php print (($i == 0) ? 'class="active"' : ''); ?>></li>
                                    <?php
                                } ?>
                            </ul>

                            <!-- slideshow -->
                            <div class="carousel-inner">
                                <?php for ($i = 0; $i < count($StockItemImage); $i++) {
                                    ?>
                                    <div class="carousel-item <?php print ($i == 0) ? 'active' : ''; ?>">
                                        <img src="\nerdygadgets-main\Public\StockItemIMG\<?php print $StockItemImage[$i]['ImagePath'] ?>">
                                    </div>
                                <?php } ?>
                            </div>

                            <!-- knoppen 'vorige' en 'volgende' -->
                            <a class="carousel-control-prev" href="#ImageCarousel" data-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </a>
                            <a class="carousel-control-next" href="#ImageCarousel" data-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </a>
                        </div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div id="ImageFrame"
                     style="background-image: url('\nerdygadgets-main\Public\StockItemIMG\<?php print $StockItem['BackupImagePath']; ?>'); background-size: cover;"></div>
                <?php
            }
            ?>
            

            <h1 class="StockItemID">Artikelnummer: <?php print $StockItem["StockItemID"]; ?></h1>
            <h2 class="StockItemNameViewSize StockItemName">
                <?php print $StockItem['StockItemName']; ?>
            </h2>
            <div class="QuantityText"><?php print $StockItem['QuantityOnHand']; ?></div>
            <div class="QuantityText" style="padding-left: 220px"><?php if (isset($StockItem['IsChillerStock'])) {if ($StockItem['IsChillerStock'] == 1) { print ("De temperatuur van dit item is : ".$gemTemp);}}?>
            </div>
            <div id="StockItemHeaderLeft">
                <div class="CenterPriceLeft">
                    <div class="CenterPriceLeftChild">
                        
                        <p class="StockItemPriceText" ><b><?php if($StockItem['SellPrice'] > 0 ){print sprintf("€ %.2f", getProductPrice($StockItem['StockItemID']));} else { print("Product niet beschikbaar."); } ?></b></p>
                        <div id="DiscountCountdown" style="text-align: right;"></div>
                        <h6> Inclusief BTW </h6>
                        <a href="/nerdygadgets-main/cart.php?action=addToCart&productId=<?php echo $StockItem['StockItemID'] ?>">
                            <button class="btn btn-primary button" type="button">In winkelwagen</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div id="StockItemDescription">
            <h3>Artikel beschrijving</h3>
            <p><?php print $StockItem['SearchDetails']; ?></p>
        </div>
        <div id="StockItemSpecifications">
            <h3>Artikel specificaties</h3>
            <?php
            $CustomFields = json_decode($StockItem['CustomFields'], true);
            if (is_array($CustomFields)) { ?>
                <table>
                <thead>
                <th>Naam</th>
                <th>Data</th>
                </thead>
                <?php
                foreach ($CustomFields as $SpecName => $SpecText) { ?>
                    <tr>
                        <td>
                            <?php print $SpecName; ?>
                        </td>
                        <td>
                            <?php
                            if (is_array($SpecText)) {
                                foreach ($SpecText as $SubText) {
                                    print $SubText . " ";
                                }
                            } else {
                                print $SpecText;
                            }
                            ?>
                            
                        </td>
                        <td>
                    </tr>
                <?php } ?>
                </table><?php
            } else { ?>

                <p><?php print $StockItem['CustomFields']; ?>.</p>
                <?php
            }
            ?>
        </div>
        <?php   $reviews = selectReviews($StockItem['StockItemID']);
            if (isset($reviews[0])) { ?>
        <div id="ReviewFrame">
            <h3 class="StockItemID">Reviews</h3>
        <table>
            <?php
            
            $review = $reviews[rand(0, count($reviews) - 1)];
                print("Titel : <a class='StockItemID'>". $review['Title']."</a> <br>");
                switch ($review['Rating']) {
                    case '5':
                        print("Aantal sterren : <a class=' stars'> ★★★★★ </a> <br>");
                        break;
                    case '4':
                        print("Aantal sterren : <a class=' stars'> ★★★★ </a> <br>");
                        break;
                    case '3':
                        print("Aantal sterren : <a class=' stars'> ★★★ </a> <br>");
                        break;
                    case '2' :
                         print("Aantal sterren : <a class=' stars'> ★★ </a> <br>");
                        break;
                    case '1':
                        print("Aantal sterren : <a class=' stars'> ★ </a> <br>");
                        break;      
                };
                print("De review : <a class='StockItemID'>".$review['Review']."</a> <br>");
                print("Datum : <a class='StockItemID'>".$review['Date']."</a> <br>");
            }

            if (isLoggedIn() && $_GET['LeaveReview'] == 'true') {
                $_SESSION['ReviewProductID'] = $_GET['id']
                ?>
        </table>
        
        </div>
        <div id="ReviewDescription">
    <div class="Reviewtext"> 
                <h3>Laat hier een review achter:</h3> 
    </div>
                <form method="post" action="review.php">
                <div class="center" >
                    <div class="stars">
                        <input type="radio" id="five" name="rate" value="5" />
                        <label for="five"></label>
                        <input type="radio" id="four" name="rate" value="4" />
                        <label for="four"></label>
                        <input type="radio" id="three" name="rate" value="3" />
                        <label for="three"></label>
                        <input type="radio" id="two" name="rate" value="2" />
                        <label for="two"></label>
                        <input type="radio" id="one" name="rate" value="1" required/>
                        <label for="one"></label>
                    </div>
                </div>
                <div class="form-group">
                <input type="text" class="form-control" id="title" name="title" placeholder="Titel van uw review" required>
                
                <div class="form-group">
                    <textarea class="form-control" id="ReviewText" name="ReviewText" rows="4" placeholder="Motiveer uw beoordeling" required ></textarea>
                </div>
                    <button type="submit" class="btn btn-primary button">Verstuur</button>
            </form>
        </div>
        <?php
    }
    } else {
        ?><h2 id="ProductNotFound">Het opgevraagde product is niet gevonden.</h2><?php
    } ?>
</div>