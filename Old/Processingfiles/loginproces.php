<?php
if (isset($_POST['submit'])) {
    $username = safedata($_POST['username']);
    $password = safedata($_POST['password']);
    //check of username voorkomt
    // zo ja,
    //check of de password gelijk is
    // anders deny
    try {
        $connect = new PDO('mysql:host=172.30.11.100:3306;dbname=md436580db426616;charset=utf8', 'md436580db426616', 'Bier123');
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SELECT * FROM users WHERE username = '$username'";
        $stmt = $connect->query($query);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($row['username'] == $username) {
                $username = $row['username'];
                $usnname = true;
            } else {
                echo 'Wrong username or password.3';
            }
            $dbpass = $row['password'];
            $password = password_verify($password, $dbpass);
            if ($password == false) {
                echo 'Wrong username or password.2';
            }
            if ($password == true && $usnname == true) {
                $_SESSION['loggedin'] = true;
                $_SESSION['userid'] = $row['ID'];
                $_SESSION['username'] = $usnname;
                echo 'Login succesfull.';
            }
        }
    } catch (PDOException $e) {
        die($e);
    }
    $stmt = null;
    $connect = null;
}
