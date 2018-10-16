<?php
include '../Functions.php';
$question = new questions();
session_start();
$db = new DBconn();
$userlevel = $db->userLevelDispenser($_SESSION['characterOwnerHash']);
if ($_SESSION['loggedin'] == false or $userlevel <= 3 or $_SESSION['loggedin'] == 0){
    header('Refresh:0; url=/index2.php');
}
?>
<!--<div id='questionChanger2'>-->
<!--    <form action="" method="post">-->
<!--        How many questions? <select name="amountofquestions">-->
<!--            --><?php
//            for ($y=0;$y<32;$y++){
//                $z=$y+1;
//                if($_POST['amountofquestions'] == $z){
//                    echo "<option value='$z' selected='selected'>$z</option>";
//                }
//                else{
//                    echo "<option value='$z'>$z</option>";
//                }
//            }
//            ?><!-- <!-- All the Options-->-->
<!--        </select>-->
<!--        <input type='submit' name='submit2' value="questions">-->
<!--    </form>-->
<!---->
<!---->
<!---->
<!--    --><?php
//    if (isset($_POST['submit2'])){
//        $len = $_POST['amountofquestions'];
//        ?>
<!--        <div id='questionChanger'>-->
<!--                <h1>Change questions</h1>-->
<!--                --><?php
//                $currentQuestions = $question->currentQuestion();
//                $currentQuestions = $db->arrayKeyChanger($currentQuestions);
//                $count = (count($currentQuestions) -1);
//                unset($currentQuestions[0]);
//                if ($len > $count){
//                    $count = $len - $count;
//                    for ($x=0;$x<($count);$x++){
//                        array_push($currentQuestions, "");
//                    }
//                    foreach ($currentQuestions as $key => $value){
//                        echo "<form action='questionHandler.php' method='get'>";
//                        echo "Question $key: <textarea name='question$key'>$value</textarea><br>";
//                        echo "<input type='reset' value='Reset Question'>";
//                        echo "<input type='submit' name='q$key' formtarget='_blank'>";
//                        echo "</form>";
//                    }
//                }elseif ($len < $count){
//                    $count = $count - $len;
//                    for ($g=0;$g<($count);$g++){
//                        array_pop($currentQuestions);
//                    }
//                    foreach ($currentQuestions as $key => $value){
//                        echo "<form action='questionHandler.php' method='get'>";
//                        echo "Question $key: <textarea name='question$key'>$value</textarea><br>";
//                        echo "<input type='reset' value='Reset Question'>";
//                        echo "<input type='submit' name='q$key' formtarget='_blank'>";
//                        echo "</form>";
//                    }
//                }elseif($len == $count){
//                    foreach ($currentQuestions as $key => $value){
//                        echo "<form action='questionHandler.php' method='get'>";
//                        echo "Question $key: <textarea name='question$key'>$value</textarea><br>";
//                        echo "<input type='reset' value='Reset Question'>";
//                        echo "<input type='submit' name='q$key' formtarget='_blank'>";
//                        echo "</form>";
//                    }
//                }else{
//                    echo 'error';
//                }
//                ?>
<!--        </div>-->
<!--    --><?php //}


//        echo "<div class='roleManagement'>
//            <form method='post' action=''>
//                Character name: <input type='text' name='charName' required><br>";
//                echo "Role: <select name='userRole'>";
//                echo "<option></option>";
//                echo "<option value='4'>Recruiter</option>";
//                echo "<option value='5'>Senior Recruiter</option>";
//                echo "<option value='6'>Internal Affaires</option>";
//                echo "<option value='7'>Director</option>";
//                echo "</select><br>";
//                echo "<input type='submit' name='roleManagement' value='Change'>";
//        echo "</form>";
//        echo "</div>";
//        if (isset($_POST["roleManagement"])){
//            $charName = $_POST["charName"];
//            $role = $_POST["userRole"];
//            $result = $db->changeRanks($charName, $role);
//            if ($result == true){
//                echo "Succesfully changed user role.";
//            }elseif ($result == false){
//                echo "The username is wrong or already has that role.";
//            }else{
//                echo "you broke this, please contact web owner.";
//            }
//        }
    ?>

<div class="days">
    <form method="post" action="">
        Cache time for characters: <select name="char"><?php for ($x=1;$x<366;$x++){echo "<option value='$x'>$x</option>";}?></select> days<br>
        <input type="submit" name="cacheTime" value="Change">
    </form>
    <form method="post" action="">
        Cache time for corporation: <select name="corp"><?php for ($x=1;$x<366;$x++){echo "<option value='$x'>$x</option>";}?></select> days<br>
        <input type="submit" name="cacheTime" value="Change">
    </form>
        <form method="post" action="">
        Cache time for alliance: <select name="ally"><?php for ($x=1;$x<366;$x++){echo "<option value='$x'>$x</option>";}?></select> days<br>
            <input type="submit" name="cacheTime" value="Change">
        </form>
            <form method="post" action="">
        Cache time for structure: <select name="struct"><?php for ($x=1;$x<366;$x++){echo "<option value='$x'>$x</option>";}?></select> days<br>
                <input type="submit" name="cacheTime" value="Change">
            </form>


</div>
<?php
if (isset($_POST["cacheTime"])){
    dprintr($_POST);
}