<?php
include '../Functions.php';
$question = new questions();
session_start();
$db = new DBconn();
$userlevel = $db->userLevelDispenser($_SESSION['characterOwnerHash']);
if ($_SESSION['loggedin'] == false or $userlevel <= 3 or $_SESSION['loggedin'] == 0){
    header('Refresh:0; url=index.php');
}
?>
<div id='questionChanger2'>
    <form action="" method="post">
        How many questions? <select name="amountofquestions">
            <?php
            for ($y=0;$y<32;$y++){
                $z=$y+1;
                if($_POST['amountofquestions'] == $z){
                    echo "<option value='$z' selected='selected'>$z</option>";
                }
                else{
                    echo "<option value='$z'>$z</option>";
                }
            }
            ?> <!-- All the Options-->
        </select>
        <input type='submit' name='submit2' value="questions">
    </form>



    <?php
    if (isset($_POST['submit2'])){
        $len = $_POST['amountofquestions'];
        ?>
        <div id='questionChanger'>
                <h1>Change questions</h1>
                <?php
                $currentQuestions = $question->currentQuestion();
                $currentQuestions = $db->arrayKeyChanger($currentQuestions);
                $count = (count($currentQuestions) -1);
                unset($currentQuestions[0]);
                if ($len > $count){
                    $count = $len - $count;
                    for ($x=0;$x<($count);$x++){
                        array_push($currentQuestions, "");
                    }
                    foreach ($currentQuestions as $key => $value){
                        echo "<form action='questionHandler.php' method='get'>";
                        echo "Question $key: <textarea name='question$key'>$value</textarea><br>";
                        echo "<input type='reset' value='Reset Question'>";
                        echo "<input type='submit' name='q$key' formtarget='_blank'>";
                        echo "</form>";
                    }
                }elseif ($len < $count){
                    $count = $count - $len;
                    for ($g=0;$g<($count);$g++){
                        array_pop($currentQuestions);
                    }
                    foreach ($currentQuestions as $key => $value){
                        echo "<form action='questionHandler.php' method='get'>";
                        echo "Question $key: <textarea name='question$key'>$value</textarea><br>";
                        echo "<input type='reset' value='Reset Question'>";
                        echo "<input type='submit' name='q$key' formtarget='_blank'>";
                        echo "</form>";
                    }
                }elseif($len == $count){
                    foreach ($currentQuestions as $key => $value){
                        echo "<form action='questionHandler.php' method='get'>";
                        echo "Question $key: <textarea name='question$key'>$value</textarea><br>";
                        echo "<input type='reset' value='Reset Question'>";
                        echo "<input type='submit' name='q$key' formtarget='_blank'>";
                        echo "</form>";
                    }
                }else{
                    echo 'error';
                }
                ?>
        </div>
    <?php }

    ?>
</div>