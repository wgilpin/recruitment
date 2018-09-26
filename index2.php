<?php
session_start();
include_once 'Content/header.php';
include_once 'Functions.php';
include_once 'Config.php';
$db = new DBconn();
//$ESI = new ESI();
//$ESI->Config();
if ($_SESSION['loggedin'] == false)                                                                                     // NOT LOGGED IN
{
    echo '
        <div class="logo">
            <img id="logo" src="https://imperium.news/wp-content/uploads/2017/05/ascendance.png">
        </div>
        <div class="register">
            <p id="register">Apply here</p>
            <div id="register2"><a id="register2" href="https://login.eveonline.com/oauth/authorize?response_type=code&redirect_uri=http://ascee.droeftoeters.com/test2.php&client_id='.$Client_ID.'&scope='.$scopes.'"><img src="https://web.ccpgamescdn.com/eveonlineassets/developers/eve-sso-login-white-large.png" alt="Login" border="0"></a></div>
        </div>
        <div class="login">
            <p id="login">Already registered? click here to login.<br></p>
            <a id="login2" href="https://login.eveonline.com/oauth/authorize?response_type=code&redirect_uri=http://ascee.droeftoeters.com/testlogin.php&client_id='.$Client_IDLogin.'"><img src="https://web.ccpgamescdn.com/eveonlineassets/developers/eve-sso-login-white-small.png" alt="Login" border="0"></a>
        </div>
        
';
    }
elseif ($_SESSION['loggedin'] == true){                                                                                 // LOGGED IN
    $userlevel = $db->userLevelDispenser($_SESSION['characterOwnerHash']);
    switch ($userlevel) {
        case 0:
            echo "You broke the website please go back";
            break;
        case 1:
            header("Refresh:0; url=recruiter.php");
            break;
        case 2 or 3:
            header("Refresh:0; url=recruiter.php");
            break;
        default:
            echo 'you are not logged in, please log in';
    }
}
else {
    echo '<br> Please contact the webmaster.';
}
include_once 'Content/footer.php';