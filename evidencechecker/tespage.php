<?php
session_start();
include 'applicantListHandler.php';
echo "<pre>";
$listHandler = new claimedRequestedHelpList("requestedHelp");
dprintr($listHandler->output());