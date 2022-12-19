<!-- dit bestand bevat alle code voor de pagina die één product laat zien -->
<?php
include __DIR__ . "/header.php";
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
                        <p class="StockItemPriceText"><b><?php if($StockItem['SellPrice'] > 0 ){print sprintf("€ %.2f", $StockItem['SellPrice']);} else { print("Product niet beschikbaar."); } ?></b></p>
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
                    </tr>
                <?php } ?>
                </table><?php
            } else { ?>

                <p><?php print $StockItem['CustomFields']; ?>.</p>
                <?php
            }
            ?>
        </div>
        <div id="ReviewDescription">
            <p>Laat hier een review achter:</p>
            
            <div class="form-group">
                <input type="text" class="form-control" id="title" name="title" placeholder="Titel van uw review">
            <h3>Hoe zou u ons product beoordelen?</h3>
            <div class="center">
                    <div class="stars">
                        <input type="radio" id="five" name="rate" value="5" />
                        <label for="five"></label>
                        <input type="radio" id="four" name="rate" value="4" />
                        <label for="four"></label>
                        <input type="radio" id="three" name="rate" value="3" />
                        <label for="three"></label>
                        <input type="radio" id="two" name="rate" value="2" />
                        <label for="two"></label>
                        <input type="radio" id="one" name="rate" value="1" />
                        <label for="one"></label>
                    </div>
                </div>
                <h3> Motiveer uw beoordeling:</h3>
                <div class="form-group">
                    <textarea class="form-control" id="ReviewText" name="ReviewText" rows="4" ></textarea>
                </div>
                    <a href="/nerdygadgets-main/review.php">
                        <button class="btn btn-primary button" type="button">Verstuur</button>
                    </a>
            
            </form>
        </div>
        <?php
    } else {
        ?><h2 id="ProductNotFound">Het opgevraagde product is niet gevonden.</h2><?php
    } ?>
</div>