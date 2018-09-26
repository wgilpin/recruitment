<?php
session_start();
if ($_SESSION['loggedin'] == true) {
    $text = var_export($_SESSION['loggedin'],true);
    echo $text;
echo "<form action='' method='post'>Do you realy want to log out?<br><input type='submit' name='submit' value='Click to logout.'></form>";
if (isset($_POST['submit'])) {
session_unset();
session_destroy();
header("Refresh:0; url=../index2.php");
}
}else{
    header("Refresh:0; url=../index2.php");
}