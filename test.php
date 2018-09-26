<?php
include_once 'Functions.php';
include_once  'config.php';

echo '
<html>
    <head>
        <title>PHP Test</title>
    </head>
    <body> 
        <a href="https://login.eveonline.com/oauth/authorize?response_type=code&redirect_uri=http://ascee.droeftoeters.com/test.php&client_id=f9e2d467b7134f21811511510a217aab">hoi</a>

        <form action="" method="POST">
            <input type="submit" name="submitbutton" value="Submit"/>
        </form>
    </body>
</html>
';

echo $returncode;
$returncode = $_GET["code"];
$submitbutton= $_POST['submitbutton'];
/*  mhjgljhygopuy   */

if (!empty($returncode)) {

    echo "hoi ik werk denk ik";
    $ESI = new ESI();
    echo "<pre>";
    print_r($ESI->Login($returncode));
    echo "</pre>";
} else {
    echo "please press  Hoi and log in";
};
if ($submitbutton){
    if (!empty($returncode)) {
        echo 'your code is ' . $neededvar;
        if(!empty($var)){ $test = charID ($neededvar);

            echo "<pre>";
            print_r($CIDR);
            echo "</pre>";

        } else {echo "false?";}

        echo "done?" . $test;
    }
};


