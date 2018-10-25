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

class requestedHelpList extends DBconn
{
    public function input($var1, $var2, $var3)
    {

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

    public function output()
    {
        echo "hoi";
        $escalations = $this->getEscalationData("", "");
        $query = "";
        foreach ($escalations as $key => $value){
            $query = $query . " SELECT * FROM recruitment WHERE ID = '$escalations[recruitmentID]'; ";
        }
        $query = substr($query, 0, -9);
        $extraInfo = $this->Connect()->query($query);
        $returnArray = $escalations + $extraInfo;
        return $returnArray;
    }
}

class claimedPersons extends DBconn
{
    public function input($var1, $var2, $var3)
    {

    }

    public function output()
    {
        return $this->advancedListDispenser($_SESSION['characterOwnerHash']);
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
            case "requestedHelp":
                $this->obj = new requestedHelpList();
        }
    }

    public function input($var1, $var2, $var3)
    {

        return $this->obj->input($var1, $var2, $var3);
    }

    public function output()
    {
        return $this->obj->output();
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