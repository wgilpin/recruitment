<?php
include_once $_SERVER['DOCUMENT_ROOT']."/backend/Include.php";
_include("ESI");
class Login extends ESI
{
    public function run($refresh_token)
    {
        $this->AccessToken = $this->AccesTokenDispencer($refresh_token);
        $CharID = $this->verify($this->AccessToken)["CharacterID"];
        $this->Scope = $this->Scopemaker("characters", $CharID, "online");
        return $this->DATAPULLAUTH($this->AccessToken, $this->Scope);
    }
}