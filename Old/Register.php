<?php
//session_start();
//if ($_SESSION['loggedin'] == true){
//    header("Refresh:0; url=index.php");
//}else{
include_once 'Content/header.php';
include_once 'Functions.php';
include_once 'Processingfiles/registerproces.php';
echo '<main>';
echo '<form action="" method="post" id="registerform"> 
      Username: <input type="text" name="username"><br>
      Password: <input type="password" name="password"><br>
      Confirm password:<input type="password" name="confirmpw"><br>
      Email-address: <input type="email" name="email"><br>
      Confirm email-address: <input type="email" name="confirmemail"><br>
      <input type="submit" name="submit" value="Send">
      </form>';
echo '</main>';
include_once 'Content/footer.php';
//}
