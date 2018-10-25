<?php
    session_start();
    echo "hoi";
    include 'Apanel.php';
    $Apanel = new adminPanel("apMail");
    echo "<pre>";
    $_SESSION["admin"] = true;
    print_r($Apanel->output("1", 7));