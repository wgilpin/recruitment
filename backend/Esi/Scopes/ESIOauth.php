<?php
include_once $_SERVER['DOCUMENT_ROOT']."/backend/Include.php";
_include("ESI");
class EveOauth extends ESI
{
    private function rToken($AccessCode, $Login)

    {
        if (empty($Login)) {
            $yn = 1;
            $temp = $this->Client_Basic;
        } else {
            $yn = 2;
            $temp = $this->Client_BasicLogin;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://login.eveonline.com/oauth/token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"grant_type\":\"authorization_code\", \"code\":\"$AccessCode\"}");
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = "Content-Type: application/json";
        $headers[] = ("Authorization: Basic " . $temp);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);


        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return json_decode($result, true);
    }             //| if you feed it a AccessCode in it shits out a fresh Access Token

    public function Run($accescode, $var2)
    {
        $Tokes = $this->rToken($accescode, $var2);
        $Info = $this->Verify($Tokes["access_token"]);
        $Info["refresh_token"] = $Tokes["refresh_token"];
        return $Info;
    }
}