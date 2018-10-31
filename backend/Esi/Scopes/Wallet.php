<?php
include_once $_SERVER['DOCUMENT_ROOT']."/backend/Include.php";
_include("ESI");
class Wallet extends ESI
{

    public function Run($refresh_token)
    {

        $keyarray = array("first_party_id", "second_party_id", "tax_receiver_id");
        $returnarray = array();

        $this->AccessToken = $this->AccesTokenDispencer($refresh_token);
        $CharID = $this->verify($this->AccessToken)["CharacterID"];
        $this->Scope = $this->Scopemaker("characters", $CharID, "wallet", "journal");
        $array = $this->DATAPULLAUTH($this->AccessToken, $this->Scope);
        $idArray = $this->_Foreach($array, $returnarray, $this->Pull_Func, $keyarray);
        $Tempstring = $this->ArraytoString($idArray);
        $Replacearray = $this->DATAPOST("universe/names", "[$Tempstring]");
        $idArray = $this->_Foreach($Replacearray, array(), $this->idArray_Changer);
        $FinalArray = $this->_Foreach($array, $returnarray, $this->Write_standing_Func, $idArray);
        $returnarray = array();
        $returnarray["blacklist"] = $this->Blacklist($idArray);
        $returnarray["info"] = $FinalArray;
        $returnarray["list"] = $this->Cachepull($idArray, true);
        return $returnarray;
    }

}