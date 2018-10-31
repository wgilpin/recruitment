<?php

class ESI
{
//_Usually_Used_Variables__\\
    protected $Client_BasicLogin;                                  //| The Codes for logging in
    protected $Client_Basic;                                       //| The Codes for Pulling information
    protected $RefreshToken;                                       //| Refresh tokens
    protected $AccessToken;                                        //| AccessToken
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

        include_once $_SERVER['DOCUMENT_ROOT']."/backend/Include.php";
        _include(array("Config","Functions"));

        $array = ConfigReturn();
        $this->Client_Basic = $array[Client_Basic];
        $this->Client_BasicLogin = $array[Client_BasicLogin];

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
        foreach ($array as $key => $value) {
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
            $knownarray = $this->DATAPOST("universe/names", "[" . $this->ArraytoString($Unknown_Array, '1') . "]");
            if ($knownarray["error"]) {
                return false;
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
                    default:
                        $extra[$value["id"]] = $value;
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
            if ($extra) {
                $returnarray['extra'] = $extra;
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
        }
    } // If reason == True, Group   ||    If reason == False, Structure

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