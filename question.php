<?php
include_once 'Functions.php';
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
                else{echo "<option value='$z'>$z</option>";}

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
            <form action='' method='post'>
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
                        echo "Question $key: <textarea name='$key'>$value</textarea><br>";
                    }
                }elseif ($len < $count){
                    $count = $count - $len;
                    for ($g=0;$g<($count);$g++){
                        array_pop($currentQuestions);
                    }
                    foreach ($currentQuestions as $key => $value){
                        echo "Question $key: <textarea name='$key'>$value</textarea><br>";
                    }
                }elseif($len == $count){
                    foreach ($currentQuestions as $key => $value){
                        echo "Question $key: <textarea name='$key'>$value</textarea><br>";
                    }
                }else{
                    echo 'error';
                }
                ?>
                <input type='submit' id='questionchangersubmit' name='submit' value='Change questions'>
            </form>
        </div>
    <?php }
    elseif (isset($_POST['submit'])){
        $questions = $_POST;
        array_pop($questions);
        $inserted = $question->questionInserter($questions);
        if ($inserted == true){
            echo 'Questions succesfully updated';
        }elseif ($inserted == false){
            echo 'Something went wrong when updating questions';
        }else{
            echo 'you broke it.';
        }
    }else {
        echo "<h2>Current questions.</h2>";
        $currentQuestions = $question->currentQuestion();
        foreach ($currentQuestions as $key => $value){
            echo "$key: <textarea name='$key'>$value</textarea><br>";
        }
    }
    ?>
</div>