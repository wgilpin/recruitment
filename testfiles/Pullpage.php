<?php
session_start();
include_once "../tempfunc.php";
$keyarray = array("characters", "current_user", "questions");
if (in_array($_POST['scope'], $keyarray)) {
    $db = new DBconn();
switch ($_POST['scope']){
    case "characters":
        $PERSON = new pullclass("Portrait");
        foreach ($_SESSION['tokens'] as $key=>$value){
            $returnData[$key] = $PERSON->_Return("",$value,"1");
        }
        break;
    case "current_user":
        $PERSON = new pullclass("Portrait");
        $returnData['level'] = $db->userLevelDispenser($_SESSION["characterOwnerHash"]);
        $temp = $PERSON->_Return("",$_SESSION["refresh_token"],"1");
        $returnData["name"] = $temp['name'];
        unset($temp['name']);
        $returnData["img"] = $temp;
        break;
    case "questions":
        $returnData = $db->questionPuller($_SESSION["tokens"][0]);
        break;
}
echo $returnData;
} else {
    $PERSON = new pullclass($_POST['scope']);

    if ($_POST['MailID']) {
        $returnData = $PERSON->_Return($_SESSION['tokens'][$_POST['id']], $_POST['MailID']);
    } else {
        $returnData = $PERSON->_Return($_SESSION['tokens'][$_POST['id']]);
    }


    if (empty($returnData)) {
        echo json_encode("empty", true);
    } else {

        echo json_encode($returnData, true);
    }
}