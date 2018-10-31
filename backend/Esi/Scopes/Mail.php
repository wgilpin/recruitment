<?php
include_once $_SERVER['DOCUMENT_ROOT']."/backend/Include.php";
_include("ESI");
class Mail extends ESI
{
    private function SendMail($refresh_token, $From, $mail)
    {
        $this->debug($From);
        $FromAccessToken = $this->AccesTokenDispencer($From);
        $fromCharID = $this->verify($FromAccessToken)["CharacterID"];
        $this->AccessToken = $this->AccesTokenDispencer($refresh_token);
        $CharID = $this->verify($this->AccessToken)["CharacterID"];
        $mail = "{ \"approved_cost\": 0, \"body\": \"$mail[1]\", \"recipients\": [ { \"recipient_id\": 94443335, \"recipient_type\": \"character\" } ], \"subject\": \"$mail[0]\"}";
        $this->DATAPOST("characters/$fromCharID/mail", $mail, $FromAccessToken);
    }

    private function Maillist($refresh_token)
    {
        $keyarray = array("from", "recipient_id");
        $returnarray = array();

        $this->AccessToken = $this->AccesTokenDispencer($refresh_token);
        $CharID = $this->verify($this->AccessToken)["CharacterID"];
        $this->Scope = $this->Scopemaker("characters", $CharID, "mail");
        $array = $this->DATAPULLAUTH($this->AccessToken, $this->Scope);

        $idArray = $this->_Foreach($array, $returnarray, $this->Pull_Func, $keyarray);
        $Replacearray = $this->DATAPOST("universe/names", "[" . $this->ArraytoString($idArray) . "]");
        $idArray = $this->_Foreach($Replacearray, array(), $this->idArray_Changer);
        $FinalArray = $this->_Foreach($array, $returnarray, $this->Write_standing_Func, $idArray);
        array_shift($FinalArray);
        $returnarray["blacklist"] = $this->Blacklist($idArray);
        $returnarray["info"] = $FinalArray;
        $returnarray["list"] = $this->Cachepull($idArray, true);
        return $returnarray;
    }

    private function MailID($refresh_token, $MailID)
    {
        $this->AccessToken = $this->AccesTokenDispencer($refresh_token);
        $CharID = $this->verify($this->AccessToken)["CharacterID"];
        $this->Scope = $this->Scopemaker("characters", $CharID, "mail", $MailID);
        $array = $this->DATAPULLAUTH($this->AccessToken, $this->Scope);
        return $array["body"];
    }

    public function run($refresh_token, $MailID, $mail)
    {
        if ($MailID) {
            if ($mail) {
                Return $this->SendMail($refresh_token, $MailID, $mail);
            } else {
                Return $this->MailID($refresh_token, $MailID);
            }
        } Else {
            Return $this->Maillist($refresh_token);
        }
    }
}
