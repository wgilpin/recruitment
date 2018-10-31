<?php
include '../Functions.php';
session_start();

$db = new DBconn();
$question = new questions();



$userlevel = $db->userLevelDispenser($_SESSION['characterOwnerHash']);
if ($_SESSION['loggedin'] == false or $userlevel <= 3 or $_SESSION['loggedin'] == 0){
    header('Refresh:0; url=index.php');
}
if ($_GET){
    echo "hoi";
    $questionKey = key($_GET);
    $questionValue = reset($_GET);
    $inserted = $question->questionInserter($questionKey, $questionValue);
    if ($inserted == true){
        echo 'Questions succesfully updated';
    }elseif ($inserted == false){
        echo 'Something went wrong when updating questions';
    }else{
        echo 'you broke it.';
    }
}