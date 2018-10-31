<?php
include '../Functions.php';

class test extends DBconn
{
    public function input($var1, $var2, $var3)
    {

    }

    public function output()
    {

    }
}

class test2 extends DBconn
{
    public function input($var1, $var2, $var3)
    {

    }

    public function output()
    {

    }
}

class listClaimableApplicants extends DBconn
{
    public function input($var1, $var2, $var3)
    {

    }

    public function output()
    {

        $query = "SELECT users.ID, users.username, users.characterOwnerHash  FROM users WHERE user_level = '2'";
        $stmt = $this->Connect()->query($query)->fetchAll(PDO::FETCH_ASSOC);
        return $stmt;
    }
}

class listRequestedHelp extends DBconn
{
    public function input($var1, $var2, $var3)
    {

    }

    public function output()
    {
        $query = "SELECT recruitment.ID, recruiterID, recruiterName.username recruiter, CharacterOwnerHashApplyer, applicantName.username applicant FROM recruitment INNER JOIN users recruiterName ON recruitment.recruiterID = recruiterName.characterOwnerHash INNER JOIN users applicantName ON recruitment.CharacterOwnerHashApplyer = applicantName.characterOwnerHash WHERE status = '1' AND escalationID = '0' ";
        $stmt = $this->Connect()->query($query)->fetchAll(PDO::FETCH_ASSOC);
        if (empty($stmt)){
            unset($stmt);
        }
        return $stmt;
    }
}

class requestHelp extends DBconn
{
    public function input($var1, $var2)
    {
        if ($var1 && $var2)
        {
            $query = "UPDATE recruitment SET status='1' WHERE recruiterID = '$var1' and CharacterOwnerHashApplyer = '$var2'";
            $stmt = $this->Connect()->query($query)->fetchAll(PDO::FETCH_ASSOC);
        }
        return $stmt;
    }

    public function output()
    {

    }
}

class claimedRequestedHelpList extends recruitmentEscalations
{
    public function input($var1, $var2)
    {

    }

    public function output($var1)
    {
        $userLvl = $this->userLevelDispenser($var1);
        if ($userLvl == 4){
            $query = "
            SELECT escalationID, recruiterID recruiterHash, recruiterName.username, CharacterOwnerHashApplyer applicantHash, applicantName.username, status FROM recruitment INNER JOIN users recruiterName ON recruitment.recruiterID = recruiterName.characterOwnerHash INNER JOIN users applicantName ON recruitment.CharacterOwnerHashApplyer = applicantName.characterOwnerHash WHERE recruiterID = '$var1' AND recruitment.status = 1 
            ";
            $escalations = $this->Connect()->query($query)->fetchAll(PDO::FETCH_ASSOC);
            if ($escalations[0]["escalationID"] != 0){
                $query = "SELECT recruitmentEscalations.ID, seniorCharacterOwnerHash, seniorName.username FROM recruitmentEscalations INNER JOIN users seniorName ON recruitmentEscalations.seniorCharacterOwnerHash = seniorName.characterOwnerHash WHERE recruitmentEscalations.ID = '".$escalations[0]["escalationID"]."'";
            }
            $escalations2 = $this->Connect()->query($query)->fetchAll(PDO::FETCH_ASSOC);
            foreach ($escalations2 as $key => $value){
                $escalations2[$value["ID"]] = $value;
                unset($escalations2[$key]);
            }
            foreach ($escalations as $key => $value){
                $escalations[$key]["escalationID"] = $escalations2[$escalations[$key]["escalationID"]];
                unset($escalations[$key]["escalationID"]["ID"]);
            }

            return $escalations;
        }elseif ($userLvl == 5){
            $query = "
SELECT recruiter.username recruiter, recruiterID recruiterHash, Rmain.charID rID, status, applicant.username applicant,  CharacterOwnerHashApplyer applierHash, Amain.charID aID FROM recruitment
INNER JOIN recruitmentEscalations ON recruitment.escalationID = recruitmentEscalations.ID
INNER JOIN users recruiter ON recruiter.characterOwnerHash = recruitment.recruiterID
INNER JOIN main Rmain ON Rmain.ID = recruiter.main_ID
INNER JOIN users applicant ON applicant.characterOwnerHash = recruitment.CharacterOwnerHashApplyer
INNER JOIN main Amain ON Amain.ID = applicant.main_ID
WHERE recruitmentEscalations.seniorCharacterOwnerHash = '$var1'
";
            $escalations = $this->Connect()->query($query)->fetchAll(PDO::FETCH_ASSOC);
            return $escalations;
        }

    }
}

class claimedPersons extends DBconn
{
    public function input($var1, $var2, $var3)
    {

    }

    public function output($var1)
    {
        return $this->advancedListDispenser($var1);
    }
}

class applicantListHandler
{
    private $obj;

    public function __construct($scope)
    {
        switch ($scope){
            case "claimedPersons":
                $this->obj = new claimedPersons();
                break;
            case "claimedRequestedHelpList":
                $this->obj = new claimedRequestedHelpList();
                break;
            case "requestHelp":
                $this->obj = new requestHelp();
                break;
            case "listRequestedHelp":
                $this->obj = new listRequestedHelp();
                break;
            case "listClaimableApplicants":
                $this->obj = new listClaimableApplicants();
                break;
        }
    }

    public function input($var1, $var2, $var3)
    {
        session_start();
        return $this->obj->input($var1, $var2, $var3, $_SESSION["characterOwnerHash"]);
    }

    public function output($var1, $var2, $var3)
    {
        session_start();
        $var1 = $var1 ?: $_SESSION["characterOwnerHash"];
        return $this->obj->output($var1, $var2, $var3);
    }
}

if ($_GET) {
    $temp = new applicantListHandler($_GET["scope"]);
    if ($_GET["var1"]){
        echo json_encode($temp->input($_GET["var1"], $_GET["var2"], $_GET["var3"]));
    }else{
        echo json_encode($temp->output());
    }
}