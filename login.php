 <?php
include __DIR__ . "/header.php";

 if(isset($_POST['email']) && isset($_POST['password'])){

    $email = $_POST['email'];
    $password = $_POST['password'];
     $query = "SELECT * from klantgegevens WHERE Email = ? AND Wachtwoord = SHA(?) LIMIT 1";
    $statement = mysqli_prepare(connectToDatabase(), $query);
     mysqli_stmt_bind_param($statement, "ss", $email, $password);
     mysqli_stmt_execute($statement);
     $result = mysqli_stmt_get_result($statement);
    var_dump($result);
     
 
    var_dump($result);
     if(isset($result)){
         $_SESSION['user_id'] = $result['id'];
         header('Location: index.php');
     } else {
         echo "Invalid email or password";
     }
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
?>
