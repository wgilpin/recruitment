<?php
include '../tempfunc.php';
$test = new pullclass("linkID");
$testa = "cZgp4KhLrKyH8Cy5UCZxsTexVOwftjmKaQY3EbYooemBbIaGA7W-yDi41oA-4_clvmmkhrlovGHOQdG4KbzuYLKnB9_UVFP0xOuXCs4Um1Sf0HOzNXw8z65NOkryYs5HNyU9F8MbudBIKvNI0VrFxWrCbblJPgeHmPINrsYcjcqnJ5ITqPT4xHyyFiGpy-duhS5__rHIIlx96qa1wqTE_diT7d20NLiBKUTKahDPXHyJMUxa_bTtEWxIFtYQkybYs1tcrnoI5gZP1y2HGVl9gNFf7K6FuRTXX8JEof7zLX7W6MXUpmMlrulKCiblOr9nbHSFTfRrEo0587ZtwNKjCQ_pp7pyiO0NTp6b1HL1GZNKUbEMmQCLl1sph2QZUZzf_pKidWxFTALi-KLG_nh4Pg2";
$array = array("30004758" => "30004758","255"=>"255","60000004"=>"60000004");
$array = array(array("param1"=>5,"param2"=>"30004758"),array("param1"=>5,"param2"=>"255"),array("param1"=>5,"param2"=>"60000004"),array("param1"=>5,"param2"=>"94443335"));
echo "<pre>";
print_r($test->_Return($testa, $array));