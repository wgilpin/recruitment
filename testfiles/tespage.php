<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="../CSS/style.css">
    </head>
    <body>
        <header>
            <div id="header">
                <link rel="shortcut icon" href="data:image/x-icon;," type="image/x-icon">
            <?php
            session_start();
            include_once 'Functions.php';
            include_once '../tempfunc.php';
            $portrait = new pullclass("Portrait");
            $db = new questions();
            ?>
            </div>
        </header>
        <div id="questions">
            <p>blub</p>
            <?php
            
            $len=count($_SESSION['tokens'],1);
            for($x=0;$x<$len;$x++)
            {
                ?>
                <div id="<?=$x?>" class="Profilepic">

                    <p><?php
                        $tempPic = $portrait->_Return("", $_SESSION['tokens'][$x], true);
                        echo  '<img src="' . $tempPic[px64x64] . '">        <span class="char-name">'."$tempPic[Name]</span><br>";
                        echo"character".$x." ";
                        ?></p>
                </div>

                <?php

            }
            $random = $db->questionPuller($_SESSION["tokens"][0]);
            ?>
        </div>
        <div id="evidence">
            <ul id="nav">
                <li><a href="content">content</a></li>
            </ul>
            <div class="allcontent">
                <div class="fancy">
                    <button id="WALLET"  class="collapsible frontbutton">WALLET</button>
                    <div class="content">

                    </div>

                </div><!--WALLET -->
                <div class="fancy">
                    <button id="ASSETS" class="collapsible frontbutton">ASSETS</button>
                    <div class="content">

                    </div>

                </div><!--ASSETS -->
                <div class="fancy">
                    <button id="LOGIN" class="collapsible frontbutton">LOGIN</button>
                    <div class="content">

                    </div>

                </div><!--LOGIN -->
                <div class="fancy">
                    <button id="TITELS" class="collapsible frontbutton">TITELS</button>
                    <div class="content">

                    </div>


                </div><!--TITELS -->
                <div class="fancy">
                    <button id="BOOKMARKS" class="collapsible frontbutton">BOOKMARKS</button>
                    <div class="content">

                    </div>


                </div><!--BOOKMARKS-->
                <div class="fancy">
                    <button id="BLUEPINTS" class="collapsible frontbutton">BLUEPRINTS</button>
                    <div class="content">

                    </div>


                </div><!--BLUEPRINTS-->
                <div class="fancy">
                    <button id="MAIL" class="collapsible frontbutton">MAIL</button>
                    <div class="content">

                    </div>

            </div><!--MAIL-->
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.12/handlebars.min.js"></script>
        <script type="text/javascript" src="js/base.js"></script>
        <script type="text/javascript" src="js/templates.js"></script>
        <script type="text/javascript" src="js/global.js"></script>
        <script type="text/javascript" src="js/login.js"></script>
        <script type="text/javascript" src="js/wallet.js"></script>
        <script type="text/javascript" src="js/people.js"></script>
        <script type="text/javascript" src="js/assets.js"></script>
        <script type="text/javascript" src="js/bookmarks.js"></script>
        <script type="text/javascript" src="js/mail.js"></script>
        <script type="text/javascript" src="js/titles.js"></script>
        <script type="text/javascript" src="js/blueprints.js"></script>
        <script type="text/javascript" src="js/index.js"></script>

    </body>
</html>


