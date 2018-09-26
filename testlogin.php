<?php
session_start();
include_once "Functions.php";
include_once "Config.php";
include_once "tempfunc.php";
$returncode = $_GET["code"];
if (!empty($returncode) and $_SESSION['loggedin'] == false) {
    $eve = new pullclass("Oauth");
    $loggin = $eve->_Return($returncode,"yeah");
    $dbconn = new DBconn();
    $YN = $dbconn->Login($loggin["CharacterID"], $loggin["CharacterOwnerHash"]);
    sleep(0.1);
    if($YN == true) {
    header("Refresh:0; url=index2.php");
    }
    else {
        echo "logging in failed, Either you do not have a account Or you need to contact a web Master <br>";
        echo  '<a href="https://login.eveonline.com/oauth/authorize?response_type=code&redirect_uri=http://ascee.droeftoeters.com/test2.php&client_id='.$Client_ID.'&scope='.$scopes.'"><img src="https://web.ccpgamescdn.com/eveonlineassets/developers/eve-sso-login-black-large.png" alt="Login" border="0"></a>';
        echo "<br>Please Press the button to make a account";
    }

}elseif ( $_SESSION['loggedin'] == true){

    header("Refresh:0; url=index2.php");
}

else
    {
    echo '<a href="https://login.eveonline.com/oauth/authorize?response_type=code&redirect_uri=http://ascee.droeftoeters.com/testlogin.php&client_id='.$Client_IDLogin.'"><img src="https://web.ccpgamescdn.com/eveonlineassets/developers/eve-sso-login-black-large.png" alt="Login" border="0"></a>';

};