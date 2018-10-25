<?php
session_start();
include 'applicantListHandler.php';
echo "<pre>";
$listHandler = new claimedRequestedHelpList("claimedRequestedHelpList");
dprintr($listHandler->output('miO3yNzM7IW0DJt1zC7iYkIRN+Q='));