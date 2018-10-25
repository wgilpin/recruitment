<?php
session_start();
include_once 'Functions.php';
include_once 'tempfunc.php';
include_once 'Config.php';
$db = new DBconn();
//$ESI = new ESI();
//$ESI->Config();
//$SP = new Suspect();
$conn = $db->Connect();
$characterOwnerHash = $_SESSION['characterOwnerHash'];
$claimID = $_POST['claimID'];
if (isset($_POST['claim'])) {
    $duplicate = $db->databaseDuplicateFinder('CharacterOwnerHashApplyer', $claimID, 'recruitment');
    if ($duplicate == 1) {
        echo '';
    } else {
        $query = "UPDATE qanswers INNER JOIN users ON users.main_ID = qanswers.main_ID SET status = 1 WHERE characterOwnerHash = '$claimID'";
        $conn->query($query);
        $query = "INSERT INTO recruitment(recruiterID, CharacterOwnerHashApplyer, status) VALUES ('$characterOwnerHash', '$claimID', '0')";
        $conn->query($query);
    }
}
$level = $db->userLevelDispenser($characterOwnerHash);
switch ($level) {
    case 4:                             //Recruiter
        //list claimed persons
        echo "<h3>All claimed applications</h3>";
        $allarray = $db->advancedListDispenser($_SESSION['characterOwnerHash']);
        foreach ($allarray as $key => $value) {
            echo $value["character_name"];
            $count = count($value["altArray"]);
            echo '<br>Amount of alts: ' . $count."<br>";
            echo "<form action='' method='post'>";
            echo "<input type='hidden' name='value' value='$key'>";
            echo "<input type='submit' name='submit' value='review evidence'>";
            echo "</form>";
            echo '<br>';
        }
        echo "</form>";
        if (isset($_POST["submit"])){
            unset($_SESSION['tokens']);
            $temparray = array();
            $array = $_POST["value"];
            $refreshtoken = "refresh_token";
            $_SESSION['tokens'] = array_values(keycheck($allarray["$array"], $temparray, $refreshtoken));
            header("Refresh:0; url=testfiles/tespage.php");
        }
        //list requested help
        // archive
        break;
    case 5:                             //Senior Recruiter
        //list requested help

        //list claimed request help

        //list claimed persons

        // archive
        break;
    case 6:                             //IA
        //list all applicants

        //list approved

        //list denied
        break;
    case 7:                             //Director
        //list all applicants

        //list approved

        //list denied
        break;
    default:                            //Does not belong here
        header("Refresh:0; url=index2.php");
        break;
}