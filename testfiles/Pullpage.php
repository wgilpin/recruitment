<?php
session_start();
include_once "../tempfunc.php";
$PERSON = new pullclass($_POST['scope']);

if ($_POST['MailID']) {
    $testdata = $PERSON->_Return($_SESSION['tokens'][$_POST['id']],$_POST['MailID']);
} else {
    $testdata = $PERSON->_Return($_SESSION['tokens'][$_POST['id']]);
}


if (empty($testdata)) {
    echo json_encode("empty", true);
} else {

    echo json_encode($testdata, true);
}