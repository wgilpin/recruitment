<?php
session_start();
include_once 'Functions.php';
include_once 'tempfunc.php';
include_once 'Config.php';
echo 'hoi ik ben de header <Br>';
echo "
    <nav>
    <ul>
        <li><a href='tespage.php'>test pagina</a></li>
        <li><a href='recruiter.php'>Recruitment section</a></li>
        <li><a href='Old/Logout.php'>Logout</a></li>
    </ul>
    </nav>
    ";
if ($_SESSION['loggedin'] == true) {
    $db = new DBconn();
    $quest = new questions();
    $ESI = new pullclass("Portrait");
    $level = $db->userLevelDispenser($_SESSION['characterOwnerHash']);
    switch ($level){
        case 1:
            $charInfo = $db->getCharacterInfo($_SESSION['characterOwnerHash']);
            $mainID = $charInfo['main_ID'];
            $tempPic = $ESI->_Return($charInfo['characterID']);
            echo  '<img src="' . $tempPic[px128x128] . '">        '."<br>Hello " .$charInfo['character_name'];
            if (!empty($altlist)){
                foreach ($altlist as $key => $value){

                }
            }
            echo '
            <br>
            <h3>Add alts</h3>
            <a href="https://login.eveonline.com/oauth/authorize?response_type=code&redirect_uri=http://ascee.droeftoeters.com/test2.php&client_id='.$Client_ID.'&scope='.$scopes.'"><img src="https://web.ccpgamescdn.com/eveonlineassets/developers/eve-sso-login-black-large.png" alt="Login" border="0"></a>
            <br>
            <h3>Question List</h3>
            ';


            $questions = $quest->currentQuestion();

            echo
            '
            <form action="" method="post">
            ';
            $x=1;
            foreach ($questions as $question)
            {
                echo $question.': <br> <input type="text" name="question'. $x .'"><br>';
                $x++;
            }
            $x=NULL;
            echo'
            <input type="submit" name="submit" value="submit">
            </form>
            ';
            if (isset($_POST['submit'])){
                $block = false;
                foreach ($_POST as $value){
                   if ($value == '0' or $value == false or empty($value) or $value == ''){
                       echo 'Please fill in the questions';
                       $block = true;
                   }
               }
               if ($block == false){
                    $quest->qanswerInserter($_POST, $mainID);
                    $characterOwnedHash = $_SESSION['characterOwnerHash'];
                    $query = "UPDATE users SET user_level = 2 WHERE characterOwnerHash = '$characterOwnedHash'";
                    $conn = $db->Connect();
                    $conn->query($query);
                    header("Refresh:0; url=recruiter.php");
                }
            }






            break;
        case 2:
            $characterOwnedHash = $_SESSION['characterOwnerHash'];
            $questions = $quest->questionHandler($characterOwnedHash);
            foreach ($questions[0] as $key => $value){
                if (empty($value)){
                    unset($questions[0][$key]);
                }
            }
            array_shift($questions[0]);
            array_shift($questions[0]);
            echo "<ol>";
            foreach ($questions[0] as $value){
                echo "<li>";
                echo $value;
                echo "</li>";
            }
            echo "</ol>";
            echo 'Alts added: <br><br>';
            $altlist2 = $db->altListDispenser($_SESSION["characterOwnerHash"]);
            foreach ($altlist2 as $key => $value){
                $tempPic = $ESI->_Return($value['characterID']);
                echo '<img src="' . $tempPic[px64x64] . '">        ';
                echo "$value[character_name]<br>";

            }
            echo '<h3>Add alts</h3>
            <a href="https://login.eveonline.com/oauth/authorize?response_type=code&redirect_uri=http://ascee.droeftoeters.com/test2.php&client_id='.$Client_ID.'&scope='.$scopes.'"><img src="https://web.ccpgamescdn.com/eveonlineassets/developers/eve-sso-login-black-large.png" alt="Login" border="0"></a>
            <br>';
            break;
        case 3:
            echo 'go home your drunk';
            break;
        case 4 or 5 or 6 or 7 or 8:
            echo "<a href='evidenceChecker.php'>Your claimed characters.</a>";
            $list = $db->ListShitter(true);
            echo "<ol>";
            foreach ($list as $characterOwnedHash) {
                echo "<li><form action='evidenceChecker.php' method='post'>";
                $charInfo = $db->getCharacterInfo($characterOwnedHash);
                $tempPic = $ESI->_Return($charInfo['characterID']);
                echo '<img src="' . $tempPic[px128x128] . '">        ';
                echo $charInfo['character_name'];
                echo '<input type="hidden" name="claimID" value="'.$characterOwnedHash.'">';
                echo '<input type="submit" name="claim" value="Claim">';
                echo "</form></li>";
            }
            echo "</ol>";

            break;
        default:
            echo 'nope nope nope';
    }
}