<!DOCTYPE html>
<html lang="ENG">
<head>
    <title>testing over 9000!</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
    <link rel="stylesheet" href="../testingover9000.css">
</head>
<body>
<div class="container-fluid">
    <div class="row">

        <!-- Left side -->
        <div id="pageLeft" class="col-4">
            <div id="contentLeft">
                <h1>Hello, $username</h1>
                <h2>
                    <br>
                    Case 1|<br>
                    Your application is pending.<br>
                    Case 2|<br>
                    Your application is in process<br>
                    Case 3|<br>
                    A recruiter requires your attention<br>
                </h2>
                <div id="greeting">
                    <h1>
                    Thank you for your patience.<br>
                    Kind regards,<br>
                    ASCEE RECRUITMENT TEAM
                    </h1>
                </div>
            </div>
        </div>



        <!-- right side-->
        <div id="pageRight" class="col-8">
            <div class="row">
                <div id="toDoList" class="col-12">
                    <h1>Things to do</h1>
                </div>

                <div id="toolbar" class="col-12">
                    <div id="toolbarNav" class="row">
                        <button class="col-4" onclick="viewEditQuestion()">Edit</button>
                        <button class="col-4" onclick="viewQuestionList()">Question list</button>
                        <button class="col-4" onclick="viewQuestion()">View</button>
                    </div>
                </div>

                <div id="toolbar2" class="col-12">
                    <div id="toolbarNav2" class="row">
                        <button class="col-6" onclick="vieuwAlts()">View alt</button>
                        <button class="col-6" >Add alt</button>
                    </div>
                </div>

                <div id="contentRight" class="col-12">
                    <div id="edit" class="col-12">

                    </div>
                    <div id="list" class="col-12">

                    </div>
                    <div id="view" class="col-12">

                    </div>
                    <div id="alts" class="col-12">

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="../testingover9000.js"></script>
</body>
</html>