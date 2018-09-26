<?php
//$to = "nm@droeftoeters.com";
//$subject = "hallo";
//$txt = "random iets";
//$headers = "From: hallo@dsgoniu.com" . "\r\n";
//
//
//mail($to,$subject,$txt,$headers);
include_once 'Functions.php';
echo 'hoi';
//$db = new DBconn();
$test = new recruitmentEscalations();
echo 'hoi';
$test2 = $test->getEscalationData("hallo", "ditismijnnaam2");
dprintr($test2);