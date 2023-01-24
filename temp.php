

<?php

include_once "database.php";

//check of de data is ingevuld
if(isset($_GET['wachtwoord']) && $_GET['temp'] && isset($_GET['sensor'])){
    //check of het wachtwoord klopt
    if($_GET['wachtwoord'] === "938285693256"){
        $temp = $_GET['temp'];
        $sensor = $_GET['sensor'];
        //connectie maken met de database
        $conn = connectToDatabase();
        //datum ophalen
        $futureDate = date("Y-m-d H:i:s", strtotime("+1000 years"));
        $currentDate = date("Y-m-d H:i:s");
        print ($temp);
        //query maken voor het toevoegen aan het tabel "coldroomtemperatures" maak gebruik van mysqli_stmt
        $query = "INSERT INTO coldroomtemperatures (ColdRoomSensorNumber, RecordedWhen, Temperature, ValidFrom, ValidTo) VALUES (?, ?, ?, ?, ?)";
        $statement = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($statement, "sssss", $sensor, $currentDate, $temp, $currentDate, $futureDate);
        mysqli_stmt_execute($statement);
        //query maken voor het verwijderen van dubbele data
        $query2 = "delete
        from coldroomtemperatures
        WHERE ColdRoomTemperatureID NOT IN (SELECT MAX(ColdRoomTemperatureID) FROM coldroomtemperatures)";
        //query uitvoeren
        $statement2 = mysqli_prepare($conn, $query2);
        mysqli_stmt_execute($statement2);
    }
}



