<?php
include_once 'Functions.php';
$standing = new standingList();
//$return = $standing->standingInserter("123456789", "character", -10, "nicolay12866 dammer", "testen", 0);


if ($return == 0){
    echo 'alles ging goed';
}elseif ($return == 1){
    echo ' insert ging fout';
}elseif ($return == 2){
    echo 'update ging fout';
}elseif ($return == 4){
    echo 'alles ging fout';
}else{
    echo 'you broke it';
}
echo "<pre>";
//$return = $standing->standingRemover(123456789);
print_r($standing->allStandingPuller());
