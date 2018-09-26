<?php
session_start();
include_once ('Content/header.php');
include_once 'Functions.php';
include_once 'Config.php';
$db = new DBconn();
//$ESI = new ESI();
//$ESI->Config();
echo "REEEE";
if ($_SESSION['loggedin'] == false)                                                                                     // NOT LOGGED IN
{
    header("Refresh:0; url=index2.php");
//    echo 'register here';
//    echo '<br><a href="test2.php">register</a>';
//    echo '<br>Please login using this link <br>';
//    echo '<a href="https://login.eveonline.com/oauth/authorize?response_type=code&redirect_uri=http://ascee.droeftoeters.com/testlogin.php&client_id='.$Client_IDTEST.'"><img src="https://web.ccpgamescdn.com/eveonlineassets/developers/eve-sso-login-black-large.png" alt="Login" border="0"></a>';
}
elseif ($_SESSION['loggedin'] == true){                                                                                 // LOGGED IN
//    $userlevel = $db->userLevelDispenser($_SESSION['characterOwnerHash']);
//    switch ($userlevel) {
//        case 0:
//            echo "You broke the website please go back";
//            break;
//        case 1:
//
//            break;
//        case 2 or 3:
//
//            break;
//        default:
//            echo 'you are not logged in, please log in';
//    }
    header("Refresh:0; url=index2.php");
}
else {
    echo '<br> Please contact the webmaster.';
}
include_once 'Content/footer.php';