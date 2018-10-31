<?php
session_start();
if ($_SESSION['loggedin'] == true){
    header("Refresh:0; url=index.php");
}else {
    include_once '../Functions.php';
    include_once 'Processingfiles/loginproces.php';
    include_once '../Content/header.php';
    echo '
        <form action="" method="post">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" name="submit" value="Send"><br>
        <a href="Register.php">No account yet? Click here.</a>
        </form>
';
    include_once 'Content/footer.php';
}