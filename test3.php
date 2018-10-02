<?php
echo "hallo";
include_once 'Functions.php';
echo " test";
include 'tempfunc.php';
echo " doei<br>";
$pullcase = new pullclass('debug');
$cache = new localEveCache;
$test = $pullcase->_Return(array("2113841152" => "2113841152"), true);
//dprintr($test);