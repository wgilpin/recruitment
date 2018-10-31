<?php
function getdbdata($charID, $playerOwnedHash)
{
if ($_SESSION['loggedin'] == true) {
try {
$_SESSION['playerOwnedHash'] = '';
$_SESSION['char_ID'] = '';
$connect = new PDO('mysql:host=172.30.11.100:3306;dbname=md436580db426616;charset=utf8', 'md436580db426616', 'Bier123');
$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$query = "SELECT users.playerOwnedHash, main.charID FROM users INNER JOIN main ON users.main_ID = main.ID where users.playerOwnedHash = '$playerOwnedHash' AND main.charID = '$charID'";
$stmt = $connect->query($query);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
$hash = $row['playerOwnedHash'];
$char_id = $row['charID'];
$_SESSION['playerOwnedHash'] = $hash;
$_SESSION['char_ID'] = $char_id;
if ($_SESSION['playerOwnedHash'] == $hash && $_SESSION['char_ID'] == $char_id) {
return $POHandCharID = true;
}
}
if ($_SESSION['playerOwnedHash'] == '' && $_SESSION['char_ID'] == '') {
return $POHandCharID = false;
}
echo '<br>' . $_SESSION['playerOwnedHash'] . '<br>' . $_SESSION['char_ID'];
} catch (PDOException $e) {
die('');
}
}
}
function insertdbdata($playerOwnedHash, $charID)
{
if ($_SESSION['loggedin'] == true) {
$userid = $_SESSION['userid'];
$block = false;
try {
$connect = new PDO('mysql:host=172.30.11.100:3306;dbname=md436580db426616;charset=utf8', 'md436580db426616', 'Bier123');
$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$query = "SELECT users.playerOwnedHash FROM users WHERE users.playerOwnedHash = '$playerOwnedHash';";
$stmt = $connect->query($query);
while ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
if ($row['playerOwnedHash'] !== $playerOwnedHash) {
$block = true;
}
}
$query = "SELECT main.charID FROM main WHERE main.charID = '$charID'";
$stmt = $connect->query($query);
while ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
if ($row['charID'] !== $charID) {
$block = true;
}
}
if ($block == false) {
$query = "INSERT INTO main(charID, refreshtoken, portraitLink) VALUES ('$charID', '0', '0')";
$connect->query($query);
sleep(0.5);
$query = "SELECT main.ID FROM main WHERE charID = '$charID';";
$stmt = $connect->query($query);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
$mainid = $row['ID'];
}
$query = "UPDATE users SET playerOwnedHash = '$playerOwnedHash', main_ID = $mainid WHERE users.ID = $userid;";
$connect->query($query);
return $exists = false;
} else {
return $exists = true;
}

} catch (PDOException $e) {
die($e);
}
}
}