<?php
$block = false;
if (isset($_POST['submit'])) {
    if (empty($_POST['username'])) {
        $block = true;
        echo '<div class="errormsg"><p>Please fill in a username.<p></div>';
    } else {
        $username = safedata($_POST['username']);
    }
    if (empty($_POST['password'])) {
        $block = true;
        echo '<div class="errormsg"><p>Please fill in a password.</p></div>';
    } else {
        $password = safedata($_POST['password']);
    }
    if (empty($_POST['confirmpw'])) {
        $block = true;
        echo '<div class="errormsg">Please fill in a correct confirmation password.</div>';
    } else {
        $cpassword = safedata($_POST['confirmpw']);
    }
    if (empty($_POST['email'])) {
        $block = true;
        echo '<div class="errormsg">Please fill in a e-mail address</div>';
    } else {
        $email = safedata($_POST['email']);
    }
    if (empty($_POST['confirmemail'])) {
        $block = true;
        echo '<div class="errormsg">Please fill in a correct confirmation e-mail address</div>';
    } else {
        $cemail = safedata($_POST['confirmemail']);
    }
    if ($cpassword !== $password or $password !== $cpassword) {
        $block = true;
        echo '<div class="errormsg">Please fill in the correct passwords.</div>';
    }
    if ($cemail !== $email or $email !== $cemail) {
        $block = true;
        echo '<div class="errormsg">Please fill in the correct e-mail addresses</div>';
    }
    if ($block == false) {
        try {
            $connect = new PDO('mysql:host=172.30.11.100:3306;dbname=md436580db426616;charset=utf8', 'md436580db426616', 'Bier123');
            $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $query = "SELECT * FROM users WHERE username = '$username'";
            $stmt = $connect->query($query);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($row['username'] == $username){
                    echo 'Username already used, please choose an other username.';
                    $block = true;
                }
            }
            if ($row == false) {
                $validusername = $username;
            }
            $query = "SELECT * FROM users WHERE email = '$cemail'";
            $stmt = $connect->query($query);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                if ($row['email'] == $cemail){
                    echo 'E-mail address already used, please choose an other e-mail address.';
                    $block = true;
                }
            }
            if ($row == false){
                $validemail = $email;
            }
            if ($block == false){
                $hashedpw = password_hash($cpassword, PASSWORD_BCRYPT);
                $register = true;
            }
            if ($block == false && $register == true){
            $time = date("Y-m-d H:i:s");
            $query = "INSERT INTO users(username, password, email, date) VALUES ('$username', '$hashedpw', '$cemail', '$time')";
            $connect->exec($query);
            if ($connect == true){
                echo 'You are successfully registered.';
            }
            }else{
                echo 'something went wrong while processing your data.';
            }
        } catch (PDOException $e) {
            die($e);
        }
        $stmt = null;
        $connect = null;
    }
}