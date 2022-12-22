<?php
include __DIR__ . "/header.php";

if (isset($_SESSION['KlantID'])) {
    header('Location: myorders.php');
} else {
    if(isset($_POST['email']) && isset($_POST['password'])){

        $resultlogin = login($_POST['email'], $_POST['password']);
        
        if(isset($resultlogin)){
            $_SESSION['KlantID'] = $resultlogin[0]['KlantID'];
            header('Location: myorders.php');
        } else {
            echo "Invalid email or password";
        }
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
    return($resultlogin);
}

?>
<div class="loginForm">
<form action="login.php" method="post">
    <label class="logintext"  for="username">Gebruikersnaam</label>
    <input class="logininput" type="text" name="email" id="email" required>
    <label class="logintext2" for="password">Wachtwoord</label>
    <input class="logininput" type="password" name="password" id="password" required>
    <input class="loginsubmit" type="submit" value="Inloggen">
</form>
</div>
<a href="/nerdygadgets-main/register.php" class="register">Geen account? Maak hier een account aan.</a>


<?php
include __DIR__ . "/footer.php";
