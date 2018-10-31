<?php
include_once $_SERVER['DOCUMENT_ROOT']."/backend/Include.php";
_include("ESI");
class Skills extends ESI
{
    private $skillDB;

    private function Skillplace($MainArray, $SetArray)
    {
        $temp = array();
        foreach ($MainArray as $value) {
            $key = $value["skill_id"];
            $temp[$key] = $value;
            if (array_key_exists($temp[$key]["skill_id"], $this->Standinglist)) {
                $temp[$key]["skill_id"] = array("standing" => $this->Standinglist[$value]["standing"], "id" => $temp[$key]["skill_id"]) + $SetArray[$temp[$key]["skill_id"]];
                if ($this->Standinglist[$value]["standing"] < 0) {
                    $temp["Blacklist"][$key] = $temp[$key];
                }
            } else {
                $temp[$key]["skill_id"] = array("standing" => "0", "id" => $temp[$key]["skill_id"]) + $SetArray[$temp[$key]["skill_id"]];
            }
        }
        return $temp;
    }

    private function Queplace($MainArray, $SetArray)
    {
        $temp = array();
        foreach ($MainArray as $key => $value) {
            $temp[$key] = $value;
            if (array_key_exists($temp[$key]["skill_id"], $this->Standinglist)) {
                $temp[$key]["skill_id"] = array("standing" => $this->Standinglist[$value]["standing"], "id" => $temp[$key]["skill_id"]) + $SetArray[$temp[$key]["skill_id"]];
                if ($this->Standinglist[$value]["standing"] < 0) {
                    $temp["Blacklist"][$key] = $temp[$key];
                }
            } else {
                $temp[$key]["skill_id"] = array("standing" => "0", "id" => $temp[$key]["skill_id"]) + $SetArray[$temp[$key]["skill_id"]];
            }
        }
        return $temp;
    }

    public function __construct()
    {
        ESI::__construct();                                 // call Grandpa's constructor
        $this->skillDB = new skillDB();
    }

    public function run($refresh_token)
    {
        $returnarray = array();
        $this->AccessToken = $this->AccesTokenDispencer($refresh_token);
        $CharID = $this->verify($this->AccessToken)["CharacterID"];

        //_Skills_\\
        $skillarray = $this->DATAPULLAUTH($this->AccessToken, "characters/$CharID/skills");
        $skillID = $this->_Foreach($skillarray, $returnarray, $this->Pull_Func, array("skill_id"));
        $replacearrayskill = $this->skillDB->skillrun($skillID);
        $finalskillarray = $this->Skillplace($skillarray["skills"], $replacearrayskill);
        if ($finalskillarray["Blacklist"]) {
            $blacklist = $finalskillarray["Blacklist"];
            unset($finalskillarray["Blacklist"]);
        }


        //_Skill_Queue_\\
        $Queuearray = $this->DATAPULLAUTH($this->AccessToken, "characters/$CharID/skillqueue");
        $skillIDQue = $this->_Foreach($Queuearray, $returnarray, $this->Pull_Func, array("skill_id"));
        $replacearrayqueue = $this->skillDB->skillrun($skillIDQue);
        $finalQuearray = $this->Queplace($Queuearray, $replacearrayqueue);
        if ($finalQuearray["Blacklist"]) {
            if ($blacklist) {
                $blacklist += $finalskillarray["Blacklist"];
            } else {
                $blacklist = $finalQuearray["Blacklist"];
            }
            unset($finalQuearray["Blacklist"]);
        }


        //_Attributes_\\
        $Attributearray = $this->DATAPULLAUTH($this->AccessToken, "characters/$CharID/attributes");

        unset($skillarray['skills']);

        $returnarray["blacklist"] = $blacklist ?: array();
        $returnarray["queue"] = $finalQuearray;
        $returnarray["skills"] = $finalskillarray;
        $returnarray["attributes"] = $Attributearray;

        $returnarray += $skillarray;
        return $returnarray;
    }
}