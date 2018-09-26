<?php
include 'Config.php';
include_once 'Functions.php';
include_once 'tempfunc.php';
session_start();
$returncode = $_GET["code"];

/*  mhjgljhygopuy   */
if ($_SESSION['loggedin'] == true and !empty($returncode)){
    $eve = new pullclass("Oauth");
    $register = $eve->_Return($returncode);
    $db = new DBconn();
    $alt = $db->registerAlt($register['CharacterOwnerHash'], $register['CharacterID'], $register['refresh_token'], $register['CharacterName']);
    header("Refresh:0; url=recruiter.php?case=$alt");
    switch ($alt){
        case 1:
            echo "You already have a account please use this link to log in <br>";
            break;
        case 2:
            echo "Registering Succesfull! <br> Please use the following link to login <br>";
            break;
        case 3:
            echo "Alt already exists!";
            break;
        case 4:
            echo "Alt has been added";
            break;
        case 5:
            echo 'something went terribly wrong';
            break;
    }
}
elseif ($_SESSION['loggedin'] == true and empty($returncode)){
    header("Refresh:0; url=index.php");
}
elseif ($_SESSION['loggedin'] == false and !empty($returncode)) {
$eve = new pullclass("Oauth");
$register = $eve->_Return($returncode);
$dbConn = new DBconn();
$register = $dbConn->register($register[CharacterOwnerHash], $register[CharacterID],$register[refresh_token], $register[CharacterName]);
    switch ($register){
        case 1:
            echo "You already have a account please use this link to log in <br>";
            break;
        case 2:
            echo "Registering Succesfull! <br> Please use the following link to login <br>";
            break;
        case 3:
            echo "Alt already exists!";
            break;
        case 4:
            echo "Alt has been added";
            break;
        case 5:
            echo 'something went terribly wrong';
            break;
    }
echo '<a href="https://login.eveonline.com/oauth/authorize?response_type=code&redirect_uri=http://ascee.droeftoeters.com/testlogin.php&client_id='.$Client_IDLogin.'"><img src="https://web.ccpgamescdn.com/eveonlineassets/developers/eve-sso-login-black-large.png" alt="Login" border="0"></a>';




} else {
    Echo  "A new Account! <br>";
    echo    '<a href="https://login.eveonline.com/oauth/authorize?response_type=code&redirect_uri=http://ascee.droeftoeters.com/test2.php&client_id='.$Client_ID.'&scope='.$scopes.'"><img src="https://web.ccpgamescdn.com/eveonlineassets/developers/eve-sso-login-black-large.png" alt="Login" border="0"></a>';
echo "<br>Please Press the button to make a account";}
