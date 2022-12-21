<?php
include __DIR__ . "/header.php";

 if(isset($_POST['email']) && isset($_POST['password'])){

    $resultlogin = login($_POST['email'], $_POST['password']);
    
    if(isset($resultlogin)){
        $_SESSION['KlantID'] = $resultlogin[0]['KlantID'];
        header('Location: index.php');
    } else {
        echo "Invalid email or password";
    }
}

function login($email, $password){
    $conn = connectToDatabase();
    $query4 = "
    SELECT KlantID
    FROM klantgegevens
    WHERE Email = '".$email."' AND Wachtwoord = SHA1('".$password."') LIMIT 1";
    $Statement = mysqli_prepare($conn, $query4);
    mysqli_stmt_execute($Statement);
    $result = mysqli_stmt_get_result($Statement);
    $resultlogin = mysqli_fetch_all($result, MYSQLI_ASSOC);
    var_dump($resultlogin);
    return($resultlogin);
}

?>

<form action="login.php" method="post">
    <label for="username">Gebruikersnaam</label>
    <input type="text" name="email" id="email" required>
    <label for="password">Wachtwoord</label>
    <input type="password" name="password" id="password" required>
    <input type="submit" value="Login">
</form>

<a href="register.php"> You can register here</a>


<?php
include __DIR__ . "/footer.php";
