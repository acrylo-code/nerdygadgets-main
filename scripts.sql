-- Create the table klantgegevens if it doesn't exist
CREATE TABLE IF NOT EXISTS `klantgegevens` (
  `KlantID` int(11) NOT NULL AUTO_INCREMENT,
  `Voornaam` varchar(255) NOT NULL,
  `Tussenvoegsel` varchar(255),
  `Achternaam` varchar(255) NOT NULL,
  `Adres` varchar(255) NOT NULL,
  `Postcode` varchar(255) NOT NULL,
  `Woonplaats` varchar(255) NOT NULL,
  `Telefoonnummer` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Huisnummer` int(11)

  PRIMARY KEY (`KlantID`)
);

-- Create a table orders and let it have a foreign key to klantgegevens
CREATE TABLE IF NOT EXISTS `bestellingen` (
  `OrderID` int(11) NOT NULL AUTO_INCREMENT,
  `KlantID` int(11) NOT NULL,
  `OrderDatum` date NOT NULL,
  `OrderTotaal` decimal(10,2) NOT NULL,
  `Betaald` tinyint(1) NOT NULL,
  PRIMARY KEY (`OrderID`),
  KEY `KlantID` (`KlantID`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`KlantID`) REFERENCES `klantgegevens` (`KlantID`)
);

-- Create a table orderregels and let it have a foreign key to orders
CREATE TABLE IF NOT EXISTS `bestellingen_rows` (
  `OrderRegelID` int(11) NOT NULL AUTO_INCREMENT,
  `OrderID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Aantal` int(11) NOT NULL,
  `Prijs` decimal(10,2) NOT NULL,
  PRIMARY KEY (`OrderRegelID`),
  KEY `OrderID` (`OrderID`),
  CONSTRAINT `orderregels_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`)
);

-- Create the table contactgevenst if it doesn't exist
CREATE TABLE IF NOT EXISTS `contactgevens` (
  `ContactID` int(11) NOT NULL AUTO_INCREMENT,
  `Voornaam` varchar(255) NOT NULL,
  `Achternaam` varchar(255),
  `Email` varchar(255) NOT NULL,
  `Bericht` varchar(500) NOT NULL,
  `sendTime` varchar(25) NOT NULL,
  PRIMARY KEY (`ContactID`)
);
 
--een tabel met kortingsCodes
CREATE TABLE IF NOT EXISTS `kortingsCodes` (
  `CodeID` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(255) NOT NULL,
  `Type` varchar(255),
  `Aantal` varchar(255) NOT NULL,
  PRIMARY KEY (`CodeID`)
);
-- Nieuwe code aanmaken
INSERT INTO kortingscodes (Aantal, Code, Type)
VALUES (5, '5PROCENTKORTING', 'procent');

INSERT INTO kortingscodes (Aantal, Code, Type)
VALUES (5, '5EUROKORTING', 'aantal');

-- Script om klantgegevens aan te passen
ALTER TABLE klantgegevens ADD isAdmin BIT DEFAULT 0 NOT NULL;
ALTER TABLE klantgegevens ADD Wachtwoord VARCHAR(255) NOT NULL;

-- script om de reviews tabel aan te maken

CREATE TABLE IF NOT EXISTS `reviews` (
  `ReviewID` int(11) NOT NULL AUTO_INCREMENT,
  `StockItemID` int(11) NOT NULL,
  `KlantID` int(11) NOT NULL,
  `Review` varchar(255) NOT NULL,
  `Rating` int(11) NOT NULL,
    `Date` date NOT NULL,
    `Title` varchar(255) NOT NULL,
  PRIMARY KEY (`ReviewID`),
  KEY `KlantID` (`KlantID`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`KlantID`) REFERENCES `klantgegevens` (`KlantID`)
);
 
-- script om de img on de website te herstellen

-- change the StockItems table, add a discount column and pupulate it with 0, use 0 as default
ALTER TABLE stockitems ADD Discount INT DEFAULT 0 NOT NULL;
-- change the StockItems table, add a discountValidUntil, pupulate all existing rows with 0000-00-00 00:00
ALTER TABLE stockitems ADD DiscountValidUntil DATETIME DEFAULT '0000-00-00 00:00' NOT NULL;
-- change the StockItems table, add a discountIsPercentage column and pupulate it with 0, use 0 as default
ALTER TABLE stockitems ADD DiscountIsPercentage BIT DEFAULT 0 NOT NULL;

INSERT INTO stockitemimages (StockItemID, ImagePath)
VALUES (223, 'Chocolate.jpg');
INSERT INTO stockitemimages (StockItemID, ImagePath)
VALUES (224, 'Chocolate.jpg');
INSERT INTO stockitemimages (StockItemID, ImagePath)
VALUES (225, 'Chocolate.jpg');
INSERT INTO stockitemimages (StockItemID, ImagePath)
VALUES (226, 'Chocolate.jpg');
INSERT INTO stockitemimages (StockItemID, ImagePath)
VALUES (227, 'Chocolate.jpg');
INSERT INTO stockitemimages (StockItemID, ImagePath)
VALUES (222, 'Chocolate.jpg');
INSERT INTO stockitemimages (StockItemID, ImagePath)
VALUES (220, 'Chocolate.jpg');



-- ##python script voor de raspberry

-- import sense_hat
-- import time
-- import requests

-- # url doorgeven en hoelang er gewacht moet worden
-- wacht = 3  # second
-- url = "http://192.168.33.15:80/nerdygadgets-main/temp.php?sensor=5&wachtwoord=938285693256&temp="

-- # afkorting voor sense_hat.SenseHat()
-- sense = sense_hat.SenseHat()

-- # aanmaken loop

-- while True:

--     # meet de temperatuur
--     temp = str(round(sense.get_temperature(), 1))
    
--     #post de temperatuur op de temp.php van de website
--     temp = url+temp
--     result = requests.get(temp)
--     print(result.text)
--     print("meting is verstuurd")

    
--     # wacht 3 seconden
--     time.sleep(wacht)
