<?php
session_start();
include 'applicantListHandler.php';
echo "<pre>";
echo "hoi";
$listHandler = new applicantListHandler("listClaimableApplicants");
echo "hoi";
dprintr($listHandler->output());
echo "hoi";