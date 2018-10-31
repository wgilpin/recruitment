<?php
include_once $_SERVER['DOCUMENT_ROOT']."/backend/Include.php";
_include("ESI");
class Portrait extends ESI
{
    public function Run($CharID, $refresh_token, $YN)
    {
        if (empty($CharID)) {
            $temp = $this->verify($this->AccesTokenDispencer($refresh_token));
            $CharID = $temp["CharacterID"];
        }
        $this->Scope = $this->Scopemaker("", $CharID, "portrait");
        if ($YN) {
            $temp2 = $this->DATAPULLUNAUTH($this->Scope);
            $temp3 = array("Name" => $temp["CharacterName"]);
            $temp2 = $temp3 + $temp2;
            return $temp2;
        }
        return $this->DATAPULLUNAUTH($this->Scope);
    }
}