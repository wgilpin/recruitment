<?php

$x = 5;
session_start();

if(isset($_GET['entry'])&&isset($_GET['callback'])){

function getGUID(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125);// "}"
        return $uuid;
    }
}
$_SESSION['State'] = getGUID();
$_SESSION['return'][$_SESSION['State']] = array("callBack" => $_GET['callback'],"entry"=>$_GET['entry']);
    include_once $_SERVER['DOCUMENT_ROOT']."/backend/Include.php";
    _include(array("Config"));
switch ($_GET['entry']){
    case "0":
        $return = "https://login.eveonline.com/oauth/authorize?response_type=code&redirect_uri=http://$Url/eveauth.php&client_id=bed0695a168f47288252e7633f1dd669&state=".base64_encode($_SESSION['State']);
        break;
    case "1":
//        $return = "https://login.eveonline.com/oauth/authorize?response_type=code&redirect_uri=http://ascee.droeftoeters.com/test2.php&client_id='.$Client_ID.'&scope='.$scopes.'";
        break;
    case "2":

        break;
}
    echo "<a href='$return' > Login </a>";
}elseif(isset($_GET['code'])&&isset($_SESSION['return'][base64_decode($_GET['state'])])){
    $session = $_SESSION['return'][base64_decode($_GET['state'])];
    unset( $_SESSION['return']);
    echo "succesfull <br>";
    echo $session["callBack"];
}else{
    unset( $_SESSION['return']);
echo "ESI error or Database error please go back". " <a href='/eveauth.php?entry=0&callback=blub' > Login </a>";;
}