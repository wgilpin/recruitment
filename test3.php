<?php
echo "hallo";
include_once 'Functions.php';
echo " test";
include 'tempfunc.php';
echo " doei<br>";
$pullcase = new pullclass('debug');
echo "test1";
$cache = new localEveCache;
echo "test2";
$refresh = "GzKY8Qk5vvF4gJi_4hCyKwqkGjCw4MWidfEZqpIedYDi9l5jENDq_Diq1P9yidoN4ZUPh2Fc1VXTHk-9KIS-dKbuLAb2Gz4Nrbc_fYRs4O1wGYs29LWHK-Cf_osuAnrPsxhrMNDGGmZArFDLsF4_hwMPmKoaeFal2-_977BnengIHbe6i8caiLBWFkxyyRjcKiZwhp2S7LuWqxBoEOhxW9rtle3rhznu-Aj9f-CnNpChkzKOxphzmDF9suPurJpc3Z63mLxSyX_zhUmxcoM13lnqfMcv65BBWYGH4eX577wluhxlDPkpJb2jJgQMx8Ca5CfPhPhwIxpQcYxJHG8NUi08LSpJmp2fSgYu6-2Vt0LVnv97XwUarn4aPTzOIE9hUqzquwsXYQCyfzCRPJ6SNA2";
$test = $pullcase->_Return($refresh, array("1022734985679" => "1022734985679", "1024779618012" => "1024779618012"));
dprintr($test);