<?php


class ESI
{
    //_Usually_Used_Variables__\\
    protected $Client_BasicLogin;                                  //| The Codes for logging in
    protected $Client_Basic;                                       //| The Codes for Pulling information
    protected $RefreshToken;                                       //| Refresh tokens
    protected $AccessToken;                                        //| AccessToken - always use Tokenchecker
    protected $scope;

    //_Usually_Used_Objects__\\
    protected $Standing;
    protected $Standinglist;
    protected $Datacall;
    protected $Cachecall;

    //__Used_Functions__\\
    protected $Pull_Func;
    protected $Write_Func;
    protected $Write_standing_Func;
    protected $idArray_Changer;

    public function __construct()
    {
        include 'Config.php';
        include_once 'Functions.php';
        $this->Client_Basic = $Client_Basic;
        $this->Client_BasicLogin = $Client_BasicLogin;
        $this->Datacall = new localEveDB();
        $this->Standing = new standingList();
        $this->Cachecall = new localEveCache();
        $this->Standinglist = $this->Standing->allStandingPuller();

        //__ALL_USED_RECALL_FUNCTIONS__\\
        $this->Pull_Func = function ($key, $value, $returnarray, $keyarray, $array, $last, $counter) {
            if (in_array($key, $keyarray) and $key != "0") {
                $returnarray[$value] = $value;
                $temp = $returnarray;
                return $temp;
            } else {
                return false;
            }
        };
        $this->Pull_Redirect_Func = function ($key, $value, $returnarray, $keyarray, $array, $last, $counter) {
            if (array_key_exists($key, $keyarray) and $key != "0") {
                if ($value == $keyarray[$key][0]) {
                    $returnarray[$array[$keyarray[$key][1]]] = $keyarray[$key][0];
                    return $returnarray;
                } else {
                    $returnarray[$array[$keyarray[$key][1]]] = $keyarray[$key][2];
                    return $returnarray;
                }
            } else {
                return false;
            }

        };
        $this->Write_Func = function ($key, $value, $returnarray, $keyarray, $array, $last, $counter) {
            if (array_key_exists($value, $keyarray)) {
                $value = $keyarray[$value];

                if ($key != "second_party_id" && $key != "tax_receiver_id" && $key != "timestamp") {
                    $returnarray[$key] = $value;
                    return $returnarray;
                }
                if ($last) {
                    $returnarray[$key] = $value;
                    $temparray = array();
                    $newarray = array();
                    foreach ($returnarray as $key => $value) {
                        if (is_numeric($key)) {
                            $temparray[$key] = $value;
                        } else {
                            $newarray[$key] = $value;
                        }
                    }
                    $temparray[] = $newarray;
                    return $temparray;
                } else {

                    return $returnarray;
                }
            } else {
                if ($last) {
                    if ($key != "second_party_id" && $key != "tax_receiver_id" && $key != "timestamp") {
                        $returnarray[$key] = $value;
                        return $returnarray;
                    }
                    $returnarray[$key] = $value;
                    $temparray = array();
                    $newarray = array();
                    foreach ($returnarray as $key => $value) {
//                        echo count(array_intersect_key($value,$keyarray))."<br>";
                        if (is_numeric($key) && is_array($value)) {

                            $temparray[$key] = $value;
                        } else {
                            $newarray[$key] = $value;
                        }
                    }
                    $temparray[$counter] = $newarray;
                    return $temparray;
                } else {
                    $returnarray[$key] = $value;
                    return $returnarray;
                }
            }
        };
        $this->Write_standing_Func = function ($key, $value, $returnarray, $keyarray, $array, $last, $counter) {
            if (array_key_exists($value, $keyarray)) {
                if (array_key_exists($value, $this->Standinglist)) {
                    $value = array("standing" => $this->Standinglist[$value]["standing"], "id" => $value, "name" => $keyarray[$value]);
                } else {
                    $value = array("standing" => "0", "name" => $keyarray[$value], "id" => $value);
                }
                if ($key != "second_party_id" && $key != "tax_receiver_id" && $key != "timestamp") {
                    $returnarray[$key] = $value;
                    return $returnarray;
                }
                if ($last) {
                    $returnarray[$key] = $value;
                    $temparray = array();
                    $newarray = array();
                    foreach ($returnarray as $key => $value) {

                        if (is_numeric($key)) {

                            $temparray[$key] = $value;
                        } else {
                            $newarray[$key] = $value;
                        }
                    }
                    $temparray[] = $newarray;
                    return $temparray;
                } else {

                    return $returnarray;
                }
            } else {
                if ($last) {
                    if ($key != "second_party_id" && $key != "tax_receiver_id" && $key != "timestamp" && $key != "unallocated_sp") {
                        $returnarray[$key] = $value;
                        return $returnarray;
                    }
                    $returnarray[$key] = $value;
                    $temparray = array();
                    $newarray = array();
                    foreach ($returnarray as $key => $value) {
//                        echo count(array_intersect_key($value,$keyarray))."<br>";
                        if (is_numeric($key) && is_array($value)) {

                            $temparray[$key] = $value;
                        } else {
                            $newarray[$key] = $value;
                        }
                    }
                    $temparray[$counter] = $newarray;
                    return $temparray;
                } else {
                    $returnarray[$key] = $value;
                    return $returnarray;
                }
            }
        };
        $this->idArray_Changer = function ($key, $value, $returnarray, $keyarray, $array, $last, $counter) {
            if ($key == "id" && $key != "0") {
                $new[$value] = $array["name"];
                $temp = $new;
                return $temp;
            } else {
                return false;
            }
        }; //Pulls the information behind "name" and puts "ID" as key
    }

//  __Suppport_functions_\\
    protected function debug($refresh_token)
    {
        $this->AccessToken = $this->AccesTokenDispencer($refresh_token);
        $CharID = $this->verify($this->AccessToken)["CharacterID"];
        Echo "<br>$CharID  REE  " . $this->AccessToken . "  REE  <br>";
    }

    protected function dprintr($printer)
    {
        echo "<pre>";
        print_r($printer);
        echo "</pre>";
    }

    protected function _Foreach($array, $returnarray, $function, $keyarray, $counter)
    {
        $counter = $counter ?: 0;
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $counter++;
                $temp = $this->_Foreach($value, $returnarray, $function, $keyarray, $counter);
                if ($temp) {
                    $returnarray = $returnarray + $temp;
                }

            } else {
                end($array);
                if ($key === key($array)) {
                    $last = true;
                } else {
                    $last = false;
                }
                $temp2 = $function($key, $value, $returnarray, $keyarray, $array, $last, $counter);
                if (is_array($temp2)) {
                    $returnarray = $temp2;
                }
            }
        };
        return $returnarray;
    }

    protected function Blacklist($array, $info)
    {
        $return = array();
        foreach ($array as $key => $value) {
            if ((key_exists($key, $this->Standinglist)) and ($this->Standinglist[$key]["standing"] < 0)) {
                $return[$key] = array("standing" => $this->Standinglist[$key]["standing"], "info" => $info[$key]);
            }
        }
        return $return;
    }

    //__All_the_ESI_Pulls__\\
    protected function AccesTokenDispencer($refresh_token)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://login.eveonline.com/oauth/token");                                           //Host Site
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);                                                                        //
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"grant_type\":\"refresh_token\", \"refresh_token\":\"$refresh_token\"}");         //Making the post
        curl_setopt($ch, CURLOPT_POST, 1);                                                                                  //

        $headers = array();                                                                                                                          //
        $headers[] = "Content-Type: application/json";                                                                                               //
        $headers[] = ("Authorization: Basic " . $this->Client_Basic);    //
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);                                                                                       //

        $result = curl_exec($ch);
        $result = json_decode($result, true);

        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return $result[access_token];
    }

    protected function Verify($accessToken)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://esi.tech.ccp.is/verify/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

        $headers = array();
        $headers[] = "Authorization: Bearer $accessToken";
        $headers[] = "Host: esi.tech.ccp.is";
        $headers[] = "Content-Type: application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        $array = json_decode($result, true);
        $this->CharID = $array[CharacterID];
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return $array;
    }
    protected function DATAPOST($place, $scope, $token)
    {
        if ($token) {
            $token = "&token=" . $token;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://esi.evetech.net/latest/$place/?datasource=tranquility$token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "$scope");
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = "Accept: application/json";
        $headers[] = "Content-Type: application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return json_decode($result, true);

    }

    protected function DATAPULLPAGE($accessToken, $scope, $page)
    {
        if (is_numeric($page)) {
            $page = "&page=$page";
        } else {
            $page = "";
        }
        $ch = curl_init();

        if (empty($accessToken)) {
            $token = "";
        } else {
            $token = "&token=$accessToken";
        }
        curl_setopt($ch, CURLOPT_URL, "https://esi.evetech.net/latest/$scope/?datasource=tranquility$page$token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");


        $headers[] = "Accept: application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);

        return json_decode($response, true);
    }

    protected function DATAPULLUNAUTH($scope)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://esi.evetech.net/latest/$scope/?datasource=tranquility");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        // this function is called by curl for each header received
        curl_setopt($ch, CURLOPT_HEADERFUNCTION,
            function ($curl, $header) use (&$headers) {
                $len = strlen($header);
                $header = explode(':', $header, 2);
                if (count($header) < 2) // ignore invalid headers
                    return $len;

                $name = strtolower(trim($header[0]));
                if (!array_key_exists($name, $headers))
                    $headers[$name] = [trim($header[1])];
                else
                    $headers[$name][] = trim($header[1]);
                return $len;
            }
        );

        $headers = array();
        $headers[] = "Accept: application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $body = substr($response, $header_size);
        $body = json_decode($body, true);
        if (array_key_exists("x-pages", $headers)) {
            if ($headers["x-pages"][0] < 2) {
                return $body;
            } else {
                for ($x = 0; $x < $headers["x-pages"][0]; $x++) {
                    $temp = $this->DATAPULLPAGE("", $scope, ($x + 1));
                    $body = array_merge($body, $temp);
                }
            }

            return $body;
        }
        return $body;
    }

    protected function DATAPULLAUTH($accessToken, $scope)
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://esi.evetech.net/latest/$scope/?datasource=tranquility&token=$accessToken");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        // this function is called by curl for each header received
        curl_setopt($ch, CURLOPT_HEADERFUNCTION,
            function ($curl, $header) use (&$headers) {
                $len = strlen($header);
                $header = explode(':', $header, 2);
                if (count($header) < 2) // ignore invalid headers
                    return $len;

                $name = strtolower(trim($header[0]));
                if (!array_key_exists($name, $headers))
                    $headers[$name] = [trim($header[1])];
                else
                    $headers[$name][] = trim($header[1]);
                return $len;
            }
        );

        $headers = array();
        $headers[] = "Accept: application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $body = substr($response, $header_size);
        $body = json_decode($body, true);
        if (array_key_exists("x-pages", $headers)) {
            if ($headers["x-pages"][0] < 2) {
                return $body;
            } else {
                for ($x = 0; $x < $headers["x-pages"][0]; $x++) {
                    $temp = $this->DATAPULLPAGE($accessToken, $scope, ($x + 1));
                    $body = array_merge($body, $temp);
                }
            }

            return $body;
        }
        return $body;
    }

    //Database_Cache_and_EveDump_\\
    protected function Cachepull($array, $reason)
    {
        foreach ($array as $key=>$value){
            $array[$key] = $key;
        }
        $Char_Func = function ($Char_Array) {
            $Char_Key_list = array(
                "id" => "ID",
                "type" => "type",
                "alliance_id" => "alliance_id",
                "ancestry_id" => false,
                "birthday" => "creationDate",
                "bloodline_id" => false,
                "corporation_id" => "corporation_id",
                "description" => "description",
                "gender" => false,
                "name" => "name",
                "race_id" => false,
                "security_status" => "SecStatus"
            );
            $return = array();
            foreach ($Char_Array as $key => $value) {
                $return[$key] = $this->DATAPULLUNAUTH("characters/$key");
                $return[$key]["type"] = "character";
                $return[$key]["id"] = $key;
            }

            foreach ($return as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    if ($Char_Key_list[$key2]) {
                        $returns[$key][$Char_Key_list[$key2]] = $value2;
                    } else {
                        unset($return[$key][$key2]);
                    }
                }
            }
            return $returns;
        };
        $Corp_Func = function ($Corp_Array) {
            $Corp_Key_list = array(
                "id" => "ID",
                "type" => "type",
                "px64x64" => "px64x64",
                "px128x128" => "px128x128",
                "alliance_id" => "alliance_id",
                "ceo_id" => "ceo_id",
                "creator_id" => "creator_id",
                "date_founded" => "creationDate",
                "description" => "description",
                "home_station_id" => "home_station_id",
                "member_count" => "member_count",
                "name" => "name",
                "shares" => false,
                "tax_rate" => false,
                "ticker" => "ticker",
                "url" => "url"
            );
            $return = array();
            foreach ($Corp_Array as $key => $value) {
                $return[$key] = $this->DATAPULLUNAUTH("corporations/$key");
                $return[$key]["id"] = $key;
                $return[$key]["type"] = "corporation";
                $return[$key]["px64x64"] = "http://image.eveonline.com/Corporation/" . $return[$key]["id"] . "_64.png";
                $return[$key]["px128x128"] = "http://image.eveonline.com/Corporation/" . $return[$key]["id"] . "_128.png";
            }
            foreach ($return as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    if ($Corp_Key_list[$key2]) {
                        $returns[$key][$Corp_Key_list[$key2]] = $value2;
                    } else {
                        unset($return[$key][$key2]);
                    }
                }
            }
            return $returns;
        };
        $Ally_Func = function ($Ally_Array) {
            $Ally_Key_list = array(
                "id" => "ID",
                "type" => "type",
                "px64x64" => "px64x64",
                "px128x128" => "px128x128",
                "creator_corporation_id" => "creator_id",
                "creator_id" => false,
                "date_founded" => "date_founded",
                "executor_corporation_id" => "corporation_id",
                "name" => "name",
                "ticker" => "ticker"
            );
            $return = array();
            foreach ($Ally_Array as $key => $value) {
                $return[$key] = $this->DATAPULLUNAUTH("alliances/$key");
                $return[$key]["id"] = $key;
                $return[$key]["type"] = "alliance";
                $return[$key]["px64x64"] = "http://image.eveonline.com/Alliance/" . $return[$key]["id"] . "_64.png";
                $return[$key]["px128x128"] = "http://image.eveonline.com/Alliance/" . $return[$key]["id"] . "_128.png";
            }
            foreach ($return as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    if ($Ally_Key_list[$key2]) {
                        $returns[$key][$Ally_Key_list[$key2]] = $value2;
                    } else {
                        unset($return[$key][$key2]);
                    }
                }
            }
            return $returns;
        };
        $Unknown_Func = function ($Unknown_Array, $Char_Func, $Corp_Func, $Ally_Func) {

            $returnarray = array();
            $knownarray = $this->DATAPOST("universe/names", "[".$this->ArraytoString($Unknown_Array, '1')."]");
            if ($knownarray["error"]) {
                return "Contact a WebMaster";
            }
            foreach ($knownarray as $value) {
                switch ($value["category"]) {
                    case "character":
                        $char[$value["id"]] = $value["id"];
                        break;

                    case "corporation":
                        $corp[$value["id"]] = $value["id"];
                        break;

                    case "alliance":
                        $ally[$value["id"]] = $value["id"];
                        break;
                }
            }

            if ($char) {
                $returnarray = $returnarray + $Char_Func($char);
            }
            if ($corp) {
                $returnarray = $returnarray + $Corp_Func($corp);
            }
            if ($ally) {
                $returnarray = $returnarray + $Ally_Func($ally);
            }
            return $returnarray;
        };
        $Struct_Func = function ($Struct_Array) {
            $Struct_Key_list = array(
                "id" => "StructureID",
                "name" => "StructureName",
                "solar_system_id" => "solarSystemID",
                "owner_id" => "corporation_id",
                "type_id" => "type_id"
            );
            $return = array();

            foreach ($Struct_Array as $key => $value) {
                $return[$key] = $this->DATAPULLAUTH($this->AccessToken, "universe/structures/$key");
                if ($return[$key]["error"]) {
                    $returns["error"][$key]["id"] = $key;
                    $returns["error"][$key] = $value;
                } else {
                    $return[$key]["id"] = $key;
                }

            }
            foreach ($return as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    if ($Struct_Key_list[$key2]) {
                        $returns[$key][$Struct_Key_list[$key2]] = $value2;
                    } else {
                        unset($return[$key][$key2]);
                    }
                }
            }

            return $returns;
        };

        if ($reason) {
            return $this->Cachecall->groupCache($array, $Char_Func, $Corp_Func, $Ally_Func, $Unknown_Func);
        } else {
            return $this->Cachecall->structCache($array, $Struct_Func, $Corp_Func, $Ally_Func, $Unknown_Func);
        }  // If reason == True, Group   ||    If reason == False, Structure
    }

    //__Support_for_ESI__\

    protected function Scopemaker($pulltype, $char, $pull, $ID)
    {
        if ((empty($char)) and (empty($ID))) {
            $char = $this->CharID;
            $scope = "$pulltype/$char/$pull";
        } elseif ((empty($char)) and (!empty($ID))) {
            $char = $this->CharID;
            $scope = "$pulltype/$char/$pull/$ID";
        } elseif ((!empty($char)) and (!empty($ID))) {
            $scope = "$pulltype/$char/$pull/$ID";
        } elseif (!empty($char) and (!empty($pull) and (empty($ID)))) {
            $scope = "characters/$char/$pull";
        } elseif ((!empty($char)) and (empty($pull))) {
            $scope = "$pulltype/$char";
        } elseif ((!empty($char)) and (empty($ID))) {
            $char = $this->CharID;
            $scope = "$pulltype/$char/$pull";;
        } else {
            $scope = "";
        }

        return $scope;
    }

    protected function ArraytoString($array, $yn)
    {
        $new = "";
        if ($yn) {
            foreach ($array as $key => $nothing) {
                $new = (string)$new . (string)$key . ", ";
            }
        } elseif ($yn or empty($yn)) {
            foreach ($array as $key => $value) {
                $new = (string)$new . (string)$value . ", ";
            }
        } else {
            echo "error";
        }
        $new = substr($new, 0, -2);
        return $new;
    }

}

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

class Mail extends ESI
{
    private function SendMail($refresh_token, $From, $mail)
    {
        $this->debug($From);
        $FromAccessToken = $this->AccesTokenDispencer($From);
        $fromCharID = $this->verify($FromAccessToken)["CharacterID"];
        $this->AccessToken = $this->AccesTokenDispencer($refresh_token);
        $CharID = $this->verify($this->AccessToken)["CharacterID"];
        $mail =  "{ \"approved_cost\": 0, \"body\": \"$mail[1]\", \"recipients\": [ { \"recipient_id\": $CharID, \"recipient_type\": \"character\" } ], \"subject\": \"$mail[0]\"}";
        $this->DATAPOST("characters/$fromCharID/mail",$mail,$FromAccessToken);
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
        $Replacearray = $this->DATAPOST("universe/names", "[".$this->ArraytoString($idArray)."]");
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

class Assets extends ESI
{
    private $placearray;
    private $DbCon;
    private $list;

    public function __construct()
    {
        ESI::__construct();                                 // call Grandpa's constructor
        $this->placearray = function ($key, $value, $returnarray, $keyarray, $array, $last, $counter) {
            $this->dprintr($array);
        };
    }

    private function Item_id($array)
    {
        $return = array();
        foreach ($array as $key => $value) {
            $return[$value['item_id']] = $value;
        }
        return $return;
    }

    private function NameArray($array)
    {
        $output = array();
        foreach ($array as $key => $value) {
            if ($value["name"] != "None") {
                $output[$value["item_id"]] = $value["name"];
            } else {
                $output[$value["item_id"]] = false;
            }
        }
        return $output;
    }

    public function Run($refresh_token)
    {
        //_Make_Key_Arrays_\\
        $Keyarray = array("location_flag" => array("Hangar", "location_id", "ItemLocation"));
        $Itemarray = array("item_id");
        $TypeArray = array("type_id");
        $returnarray = array();


        //_Getting_the_Token_and_ID_\\
        $this->AccessToken = $this->AccesTokenDispencer($refresh_token);
        $CharID = $this->verify($this->AccessToken)["CharacterID"];

        //_printing_usefull_information_\\
        $this->debug($refresh_token);
        //_Pulling_THE_asset_List_\\
        $array = $this->DATAPULLAUTH($this->AccessToken, $this->Scopemaker("characters", $CharID, "assets"));

        $return = $this->Item_id($array);

        //_getting_the_names_of_the_Items_\\
        $ItemArray = $this->_Foreach($array, $returnarray, $this->Pull_Func, $Itemarray);
        $itemString = $this->ArraytoString($ItemArray);
        $ReplaceItemarray = $this->DATAPOST("characters/$CharID/assets/names", "[$itemString]", $this->AccessToken);
        $ReplaceItemarray = $this->NameArray($ReplaceItemarray);

        //_Separating_the_Types_\\
        $typeArray = $this->_Foreach($array, $returnarray, $this->Pull_Func, $TypeArray);


        $placearray = $this->_Foreach($array, $returnarray, $this->Pull_Redirect_Func, $Keyarray);
        $this->dprintr($placearray);
        $Hangar = array_keys($placearray, "Hangar");
        $this->dprintr($Hangar);
        foreach ($Hangar as $key => $value) {
            if (in_array($value, $ItemArray)) {
                $test[$value] = "ItemLocation";
                unset($Hangar[$key]);
            }
        }
        $this->dprintr($test);

        $Datacheck = $Hangar + $typeArray;
        $DataOutput = $this->Datacall->data($Datacheck);

        $this->dprintr($return);

    }

}

class Skills extends ESI
{
    private $skillDB;
    private function Skillplace($MainArray,$SetArray){
        $temp = array();
        foreach ($MainArray as $value){
            $key = $value["skill_id"];
            $temp[$key] = $value;
            if (array_key_exists($temp[$key]["skill_id"], $this->Standinglist)) {
                $temp[$key]["skill_id"] = array("standing" => $this->Standinglist[$value]["standing"], "id" => $temp[$key]["skill_id"])+$SetArray[$temp[$key]["skill_id"]];
                if($this->Standinglist[$value]["standing"]<0){$temp["Blacklist"][$key]= $temp[$key];}
            } else {
                $temp[$key]["skill_id"] =  array("standing" => "0", "id" => $temp[$key]["skill_id"])+$SetArray[$temp[$key]["skill_id"]];
        }
    }
    return $temp;
    }
    private function Queplace($MainArray,$SetArray){
        $temp = array();
        foreach ($MainArray as $key => $value){
            $temp[$key] = $value;
            if (array_key_exists($temp[$key]["skill_id"], $this->Standinglist)) {
                $temp[$key]["skill_id"] = array("standing" => $this->Standinglist[$value]["standing"], "id" => $temp[$key]["skill_id"])+$SetArray[$temp[$key]["skill_id"]];
                if($this->Standinglist[$value]["standing"]<0){$temp["Blacklist"][$key]= $temp[$key];}
            } else {
                $temp[$key]["skill_id"] =  array("standing" => "0", "id" => $temp[$key]["skill_id"])+$SetArray[$temp[$key]["skill_id"]];
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
        $finalskillarray = $this->Skillplace($skillarray["skills"],$replacearrayskill);
        if($finalskillarray["Blacklist"]){$blacklist = $finalskillarray["Blacklist"];unset($finalskillarray["Blacklist"]);}


        //_Skill_Queue_\\
        $Queuearray = $this->DATAPULLAUTH($this->AccessToken, "characters/$CharID/skillqueue");
        $skillIDQue = $this->_Foreach($Queuearray, $returnarray, $this->Pull_Func, array("skill_id"));
        $replacearrayqueue = $this->skillDB->skillrun($skillIDQue);
        $finalQuearray = $this->Queplace($Queuearray,$replacearrayqueue);
        if($finalQuearray["Blacklist"]){if($blacklist){$blacklist += $finalskillarray["Blacklist"];}else {$blacklist = $finalQuearray["Blacklist"];}unset($finalQuearray["Blacklist"]);}


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

class Debug extends ESI
{
    public function Run($refresh, $array, $reason)
    {
        $Skillreturn = array();
        $groups = $this->DATAPULLUNAUTH("universe/categories/16");
        foreach ($groups['groups'] as $value) {
            $temp = $this->DATAPULLUNAUTH("universe/groups/$value");
            $Skill[$temp['name']] = $temp['types'];
        }
        foreach ($Skill as $key => $value) {
            foreach ($value as $key2 => $skillId) {
                $Skill[$key][$key2] = $this->DATAPULLUNAUTH("universe/types/$skillId");
            }
        }
        $change = array("164" => "Charisma", "165" => "Intelligence", "166" => "Memory", "167" => "Perception", "168" => "Willpower");
        foreach ($Skill as $value1) {
            foreach ($value1 as $key => $value) {
                $Skillreturn[$value['name']]['name'] = $value['name'];
                $Skillreturn[$value['name']]['description'] = $value['description'];
                $Skillreturn[$value['name']]['type_id'] = $value['type_id'];
                $Skillreturn[$value['name']]['group_id'] = $value['group_id'];
                foreach ($value['dogma_attributes'] as $value2) {
                    if ($value2['attribute_id'] == 275) {
                        $Skillreturn[$value['name']]['multiplier'] = $value2['value'];
                    }
                    if ($value2['attribute_id'] == 180) {
                        $Skillreturn[$value['name']]['primaryAttribute'] = $change[$value2['value']];
                    }
                    if ($value2['attribute_id'] == 181) {
                        $Skillreturn[$value['name']]['secondaryAttribute'] = $change[$value2['value']];
                    }
                }
            }
        }
        $this->dprintr($Skillreturn);
        return $Skillreturn;
    }
}

class pullclass
{
    private $Obj;

    public function __construct($scope)
    {
        switch ($scope) {
            //_Extra_\\
            case "titles":
                $value = $this->Puller("characters", '', "titles");
                break;
            case "blueprints":
                if (!empty($_POST['number'])) {

                    $value = $this->Puller("characters", '', "blueprints", $_POST['number']);
                    $_POST['number'] = '';
                } else {
                    $value = $this->Puller("characters", '', "blueprints");
                    $keyarray = array('type_id');
                    $value = $this->CharCorpAllyconverter($keyarray, $value);
                }
                break;
            case "bookmarks":
                $value = $this->Puller("characters", '', "bookmarks");
                $keyarray = array('creator_id', 'location_id');
                $value = $this->CharCorpAllyconverter($keyarray, $value);
                break;
            case "pi":
                $value = $this->Puller("characters", '', "planets");
                break;

            //_ON_THE_PULL_PAGE__\\
            case "wallet":

                $this->Obj = new Wallet();
                break;              //| Input: refresh_token              |Output:      Array

            case "mail":
                $this->Obj = new Mail();
                break;              //| Input: refresh_token              |Output:      Array

            case "skills":
                $this->Obj = new Skills();
                break;

            case "assets":
                $this->Obj = new Assets();
                break;

            case "login":
                $this->Obj = new Login();
                break;

            //__NOT_ON_THE_PULL_PAGE__\\

            case "debug":
                $this->Obj = new Debug();
                break;
            case "Oauth":
                $this->Obj = new EveOauth();
                break;               //| Input: accesscode                |Output:      Array
            case "Portrait":
                $this->Obj = new Portrait();
                break;          //| Input: characterID              |Output:      Array
            default:
                echo "Wrong Scope Mate";
                break;
        }
    }

    Public function _Echo($var1, $var2, $var3, $var4)
    {
        echo $this->Obj->Run($var1, $var2, $var3, $var4);
    }

    Public function _Return($var1, $var2, $var3, $var4)
    {
        return $this->Obj->Run($var1, $var2, $var3, $var4);
    }

}
