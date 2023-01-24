<?php
include __DIR__ . "/header.php";
?>
<div class="loginForm">
<form action="login.php" method="post">
    <?php if(hasErrorMessages())
    { echo getErrorMessages(); clearErrorMessages(); } ?>
    <label class="logintext"  for="username">E-mailadres</label>
    <input class="logininput" type="text" name="email" id="email" required>
    <label class="logintext2" for="password">Wachtwoord</label>
    <input class="logininput" type="password" name="password" id="password" required>
    <input class="loginsubmit" type="submit" value="Inloggen">
</form>
</div>
<a href="/nerdygadgets-main/register.php" class="register">Geen account? Maak hier een account aan.</a>


<?php
include __DIR__ . "/footer.php";
