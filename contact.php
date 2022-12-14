<?php include __DIR__ . "/header.php"; ?>
<style>
    .checkoutbtn {
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

     .checkoutbtn:hover {background-color: #3741ff
    }

    .checkoutbtn:active {
        background-color: #3741ff;
        box-shadow: 0 5px #666;
        transform: translateY(4px);
    }
    
</style>    
<p style="text-align:center; font-size: 50px; color:#676EFF;" class="StockItemName"  >Over Nerdygadgets</p>
    <p style="color:#676EFF; position: absolute; left: 30px; top: 100px; width: 150px; font-size: 30px;" class="StockItemName" >Nerdygadgets</p>
        <p style="position: absolute; left: 30px; top: 145px; width: 1000px; font-size: 15px;" > 

            Nerdygadgets is een importeur en groothandel die producten levert aan verschillende warenhuizen en supermarkten in de Verenigde Staten.<br>
            Ook levert Nerdygadgets producten aan weer andere groothandels. Incidenteel verkoopt Nerdygadgets producten rechtstreeks aan consumenten.<br><br>
            Nerdygadgets werkt met een groot netwerk aan vertegenwoordigers die het land doortrekken om hun producten in de markt te krijgen. 
            Nerdygadgets heeft ambities om hun activiteiten ook in Europa op te starten vanuit een nieuw hoofdkantoor in Amsterdam, maar de aandeelhouders hebben de opstart
            steeds tegengehouden omdat ze het een te groot risico vinden om investeringen te doen terwijl belangrijke klanten zijn omgevallen door de hevige concurrentie
            van online aanbieders.
        </p>
    <p style="color:#676EFF; position: absolute; left: 30px; top: 380px; width: 1000px; font-size: 30px;" class='StockItemName' >Contact Gegevens</p>
<p style="position: absolute; left: 30px; top: 430px; width: 1000px; font-size: 15px;" > 

        E-mail: business.nerdygadgets@gmail.com<br>
        Telefoon: 088 469 9911<br>
        Locatie: Campus 2 8017 CA Zwolle<br>
        BTW: NL 1234.05.678.B01<br>
    </p>

<script>
    function redirectGetContact(){
        window.location.href = "/nerdygadgets-main/get-contact.php";
    }
</script>

<button onclick="redirectGetContact()" class="btn btn-primary checkoutbtn" type="button" style="position: absolute; left: 30px; top: 320px; width: 150px;">Zoek Contact</button>