<?php
class ESI
{
    private $Client_BasicTEST;                                   //| The Codes for Testing the login
    private $Client_BasicOauth;                                  //| The Codes for logging in
    private $Client_Basic;                                       //| The Codes
    private $RefreshToken;                                       //| Refresh tokens
    private $AccessToken;                                        //| AccessToken - always use Tokenchecker
    Private $CharID;                                             //| Without a database this cashes the CharID for now.
    public function __construct()
    {
        include 'Config.php';
        $this->Client_Basic = $Client_Basic;
        $this->Client_BasicOauth = $Client_BasicOauth;
        $this->Client_BasicTEST = $Client_BasicTEST;
    }                           //| Loading in the Logincodes
//________________PRIVATE FUNCTION HOUSE_______________________________________________________________________________\\
                                                                 //|
    private function rToken($AccessCode,$Login)
    {
        if(empty($Login)){$temp=$this->Client_Basic;} else{$temp=$this->Client_BasicTEST;}
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://login.eveonline.com/oauth/token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"grant_type\":\"authorization_code\", \"code\":\"$AccessCode\"}");
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = "Content-Type: application/json";
        $headers[] = ("Authorization: Basic " . $temp );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        //echo $result;


        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }             //| if you feed it a AccessCode in it shits out a fresh Access Token
                                                                 //|
    private function Tokenchecker()
    {
        $string = $this->refreshToken();
        $array = json_decode($string, true);
        $this->RefreshToken = $array[refresh_token];
        $this->AccessToken = $array[access_token];
        return $array[access_token];
    }                         //| Always returns a working Access_Token




    private function TokencheckerZ($refreshtoken)
    {
        $string = $this->refreshTokenZ($refreshtoken);
        $array = json_decode($string, true);
        $this->RefreshToken = $array[refresh_token];
        $this->AccessToken = $array[access_token];
        return $array[access_token];
    }
    //|
    private function refreshTokenZ($refreshtoken)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://login.eveonline.com/oauth/token");                                           //Host Site
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);                                                                        //
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"grant_type\":\"refresh_token\", \"refresh_token\":\"$refreshtoken\"}");         //Making the post
        curl_setopt($ch, CURLOPT_POST, 1);                                                                                  //

        $headers = array();                                                                                                                          //
        $headers[] = "Content-Type: application/json";                                                                                               //
        $headers[] = ("Authorization: Basic " . $this->Client_Basic);    //
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);                                                                                       //

        $result = curl_exec($ch);
        $temp = json_decode($result, true);
        return $result;

        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
    }




    private function refreshToken()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://login.eveonline.com/oauth/token");                                           //Host Site
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);                                                                        //
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"grant_type\":\"refresh_token\", \"refresh_token\":\"$this->RefreshToken\"}");         //Making the post
        curl_setopt($ch, CURLOPT_POST, 1);                                                                                  //

        $headers = array();                                                                                                                          //
        $headers[] = "Content-Type: application/json";                                                                                               //
        $headers[] = ("Authorization: Basic " . $this->Client_Basic);    //
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);                                                                                       //

        $result = curl_exec($ch);
        $temp = json_decode($result, true);
        return $result;

        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
    }                         //| internal class gibbirish that isn't useful at all but make things work
                                                                 //|
    public function charID($accessToken)
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
        return $array;
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
    }                   //| For Registering and logging in (Verify'er)
                                                                 //|
    private function pullrequest($pull, $pulltype, $char)
    {
        $this->Tokenchecker();
        if (empty($char)) {
            $char = $this->CharID;
        }           //automatically makes it $char      Char ID if left empty
        if (empty($pulltype)) {
            $pulltype = "characters";
        }       //automatically makes it $Pulltype  Characters if left empty
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://esi.evetech.net/latest/$pulltype/$char/$pull/?datasource=tranquility&token=$this->AccessToken");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = "Accept: application/json";
        $headers[] = ("Authorization: Basic " . $this->Client_Basic);    //
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        return $result;
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
    }   //| Does what it says
                                                                 //|
    private function debug($refreshtoken,$scope)
    {
        $accessToken= $this->TokencheckerZ($refreshtoken);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://esi.evetech.net/latest$scope?datasource=tranquility&token=$accessToken)");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = "Accept: application/json";
        $headers[] = ("Authorization: Basic " . $this->Client_Basic);    //
        echo "<br>\"Authorization: Basic \" . $this->Client_Basic<br>";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        return $result;
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
    }
//________________PUBLIC FUNCTION HOUSE________________________________________________________________________________\\
    public function Login($AccessCode)
    {
        if (empty($AccessCode))
            {
                $temparray = $this->charID($this->Tokenchecker());
                $array = array("CharacterOwnerHash"=>$temparray[CharacterOwnerHash],"CharacterID"=>$temparray[CharacterID],"refresh_token"=>$this->RefreshToken,"CharacterName"=>$temparray[CharacterName]);
                return ($array);
            }
        else
            {
                $TokenArray = json_decode($this->rToken($AccessCode, true), true);
                $temparray = $this->charID($TokenArray[access_token]);
                return (array("CharacterID"=>$temparray[CharacterID],"CharacterOwnerHash"=>$temparray[CharacterOwnerHash]));
            }

    }                      //| For Oauth Account logging in
                                                                 //|
    public function Puller($pulltype,$char,$pull)
    {
        $this->Tokenchecker();
        $stringarray = $this->pullrequest($pull, $pulltype, $char);
        $decodedarray = json_decode($stringarray, true);
        return $decodedarray;


    }           //| The allpuller!
                                                                 //|
    public function Config($AccessCode,$Hash)
    {
        if(empty($AccessCode)and (empty($Hash)))
        {
         $DB = new DBconn();
         $Tokenarray = $DB->getCharacterInfo($_SESSION['characterOwnerHash']);
         $this->RefreshToken =$Tokenarray["refresh_token"];
         $this->charID($this->Tokenchecker());
        }
        elseif (empty($AccessCode)and (!empty($Hash)))
        {

            $DB = new DBconn();
            $Tokenarray = $DB->getCharacterInfo($Hash);
            $scope = "/characters/$Tokenarray[characterID]/mail/";
            echo $scope;
            $this->RefreshToken =$Tokenarray["refresh_token"];
            $this->charID($this->Tokenchecker());
            echo "here<br>";
            dprintr($this->debug($Tokenarray["refresh_token"],$scope));
            echo "here<br>";

        }
        else
        {
         $Tokenarray = json_decode($this->rToken($AccessCode), true);
         $this->RefreshToken = $Tokenarray["refresh_token"];
         $this->AccessToken = $Tokenarray["access_token"];
         $this->Tokenchecker();
         $this->charID($this->Tokenchecker());
        }
    }                     //| Register the first steps
                                                                 //|
    public function EvidencdeConfig($Refresh_token, $CharacterID)
    {
        $this->RefreshToken = $Refresh_token;
        $this->CharID = $CharacterID;
        $this->AccessToken=$this->Tokenchecker();
    }

    public function EchoAll()
    {
        echo $this->RefreshToken;
        echo $this->CharID;

    }
//_____________________________________________________________________________________________________________________\\
}

class Suspect
{
    private $Client_BasicTEST;                                   //| The Codes for Testing the login
    private $Client_BasicOauth;                                  //| The Codes for logging in
    private $Client_Basic;                                       //| The Codes
    private $RefreshToken;                                       //| Refresh token
    Private $CharID;                                             //| Without a database this cashes the CharID for now.

    public function __construct()
    {
//        include_once 'Functions.php';
        include 'Config.php';
        $this->Client_Basic = $Client_Basic;
        $this->Client_BasicOauth = $Client_BasicOauth;
        $this->Client_BasicTEST = $Client_BasicTEST;
    }

    private function Standings()
    {
        $Puller = new standingList();

        print_r($Puller->allStandingPuller());

    }

    private function keys($array,$arrayorsting)
    {
        $namearray= array();
        $diffrences = array();
        if(!(is_array($array))){
            echo "No array given";
            return false;
        }else
        {

            foreach ($array as $key=>$value){

                if(is_array($value))
                {
                    $diffrences=$this->keys($value,1);
                }
                else

                {
                    if(!(in_array($value, $namearray))){
                        $namearray[]=$key;
                    }
                }

            }

        }

        $namearray = array_unique(array_merge($namearray,$diffrences));
        return $namearray;
    }

    public function charID($accessToken)
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
        return $array;
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
    }

    private function AccesTokenDispencer()
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://login.eveonline.com/oauth/token");                                           //Host Site
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);                                                                        //
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"grant_type\":\"refresh_token\", \"refresh_token\":\"$this->RefreshToken\"}");         //Making the post
        curl_setopt($ch, CURLOPT_POST, 1);                                                                                  //

        $headers = array();                                                                                                                          //
        $headers[] = "Content-Type: application/json";                                                                                               //
        $headers[] = ("Authorization: Basic " . $this->Client_Basic);    //
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);                                                                                       //

        $result = curl_exec($ch);
        $result = json_decode($result, true);
//    echo "<pre>";
//    print_r($result);
//    echo "</pre>";
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return $result[access_token];
    }

    private function IDreplacer($keyarray,$array,$idarray)
    {
        foreach ($keyarray as $key=>$test){

            foreach ($array as $key1 => $array2) {
                if (is_array($array2)) {
                    foreach ($array2 as $key2 => $array3) {
                        if (is_array($array3)) {
                            foreach ($array3 as $key3 => $array4) {
                                if (is_array($array4)) {
                                    foreach ($array4 as $key4 => $array5) {
                                        if ($key4 == $key) {
                                            if(!empty($idarray[$array5])){$array[$key1][$key2][$key3][$key4] = $idarray[$array5];}
                                        }
                                    }
                                } else {
                                    if ($key3 == $key) {

                                        if(!empty($idarray[$array4])){$array[$key1][$key2][$key3] = $idarray[$array4];}
                                    }
                                }
                            }
                        } else {
                            if ($key2 == $key) {

                                if(!empty($idarray[$array3])){$array[$key1][$key2] = $idarray[$array3];}
                            }
                        }

                    }
                } else {
                    if ($key1 == $key) {

                        if(!empty($idarray[$array2])){$array[$key1] = $idarray[$array2];}
                    }
                }
            }

        }

        return $array;
    }

    private function LocaltypeIDreplacer($array,$idarray,$keyarray)
    {

        foreach ($keyarray as $ReplaceKey => $ReplaceValue) {
            foreach ($array as $key1 => $array1) {
                if (is_array($array1)) {
                    foreach ($array1 as $key2 => $array2) {
                        if (is_array($array2)) {
                            foreach ($array1 as $key3 => $array3) {
                                if (is_array($array3)) {
                                    foreach ($array1 as $key4 => $array4) {
                                        if(!empty($idarray[$array4][$ReplaceKey])){ if ($key4 == $ReplaceValue){$array[$key1][$key2][$key3][$key4]= $idarray[$array4][$ReplaceKey];}}}

                                } else{if(!empty($idarray[$array3][$ReplaceKey])){if ($key3 == $ReplaceValue){$array[$key1][$key2][$key3]= $idarray[$array3][$ReplaceKey];}}}}

                        } else{if(!empty($idarray[$array2][$ReplaceKey])){if ($key2 == $ReplaceValue){$array[$key1][$key2]= $idarray[$array2][$ReplaceKey];}}}}

                } else{if(!empty($idarray[$array1][$ReplaceKey])){if($key1 == $ReplaceValue){$array[$key1]= $idarray[$array1][$ReplaceKey];}}}}
        }
        return $array;
    }

    private function CCACsupport($key,$array,$keyid)
    {

        foreach ($array as $key1=>$array2)
        {
            if(is_array($array2))
            {
                foreach ($array2 as $key2=>$array3)
                {
                    if(is_array($array3))
                    {
                        foreach ($array3 as $key3=>$array4)
                        {
                            if(is_array($array4))
                            {
                                foreach ($array4 as $key4=>$array5)
                                {
                                    if ($key4 == $key) {
                                        $namearray[] = $array5;
                                    }
                                }
                            }else {
                                if ($key3 == $key) {
                                    $namearray[] = $array4;
                                }
                            }
                        }
                    }else{
                        if($key2==$key)
                        {
                            $namearray[] = $array3;
                        }
                    }

                }
            }else
            {
                if($key1==$key)
                {
                    $namearray[] = $array2;
                }
            }
        }
        return $namearray;
    }

    private function assetplacer($array,$finishedarray,$locationID)
    {

        if(empty($array)){return $finishedarray;}

        foreach ($array as $key=>$value)
        {
            echo "<br>";
            print_r($value["location_id"]);
            if (array_key_exists($value["location_id"],$finishedarray))
            {
                echo "  yes   | "; echo $value["item_id"]."  |  "; echo $finishedarray[$value["location_id"]]["item_id"];
//                $finishedarray[$value["location_id"]] = ;
            }
            elseif (array_key_exists($value["location_id"],$array))
            {
                echo "  yesish  |";
            }
            else{echo "  No     | "; echo $value["location_id"];}

        }
        if(empty($array)){return $finishedarray;}
        echo "done";
    }

    private function AssetsHandler($array)
    {

//        print_r($array);
        $temparray = $array;
        $finishedarray = array();
        $locationArray = array();


        foreach ($temparray as $key => $value) {
            if ($key==1000){echo "HECK YEAH!"."<br>";}
            if ($value["location_type"] == "station") {
//            echo "YES ".$key."<br>";
                $finishedarray[$value["item_id"]] = $value;
                $finishedarray[$value["number"]] = $key;
                unset($temparray[$key]);
            }
            elseif (($value["location_type"] == "other") and ($value["location_flag"] == "Hangar") )
            {
//                echo "YES ".$key."<br>";
                $finishedarray[$value["item_id"]] = $value;
                $finishedarray[$value["item_id"]]["number"] = $key;
                unset($temparray[$key]);
            }
            else {

                $temparray[$value["item_id"]] = $value;
                unset($temparray[$key]);
            }

        }


        $this->assetplacer($temparray,$finishedarray);
        echo "<br> Zip";

    }

    private function CharCorpAllyconverter($keyarray,$array)
    {
        $thisisaclassname = new localEveDB();
        foreach($keyarray as $key)
        {
            $value[] = $this->CCACsupport($key,$array);
        }

        $result = array();
        foreach ($value as $darray)
        {
            foreach ($darray as $charID) {
                if(!(in_array($charID, $result))){
                    if($charID <= 20 ){}
                    else {if(!in_array($charID,$result)){$result[]=$charID;}}
                }
            }
        }

        $Return =  $thisisaclassname->data($result);


        $keyarray = array("typeName"=>"type_id");
        $final2 = $this->LocaltypeIDreplacer($array,$Return[1],$keyarray);


        if(!(empty($Return[2]))) {

//            array_shift($Return[0]);
            foreach ($Return[2] as $charid)
            {
                $stringarray = $stringarray .", ".$charid;
            }
            $stringarray = substr($stringarray,2);
            $result = $this->DATAPOST("universe/names", $stringarray);

            $final = array();

            foreach ($result as $ID) {
                $final[$ID['id']] = $ID['name'];
            }

            $keyarray2= array("type_id");
            $final3 = $this->IDreplacer($keyarray2, $final2, $final);
            return $final3;
        }
        else {return $final2;}
    }

    private function DATAPOST ($place,$scope){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://esi.evetech.net/latest/$place/?datasource=tranquility");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "[$scope]");
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = "Accept: application/json";
        $headers[] = "Content-Type: application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);
        return json_decode($result,true);

    }

    private function DATAPULLPAGE($accessToken,$scope,$page){
        if(is_numeric($page)){$page = "&page=$page";} else {$page="";}
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://esi.evetech.net/latest/$scope/?datasource=tranquility$page&token=$accessToken");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");


        $headers[] = "Accept: application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);

        return json_decode($response,true);
    }

    public function DATAPULL ($accessToken,$scope)
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
        $body = json_decode($body,true);
        if (array_key_exists("x-pages", $headers)) {
            if ($headers["x-pages"][0] < 2) {
                return $body;
            } else {
                for ($x=0;$x<$headers["x-pages"][0];$x++)
                {
                    $temp = $this->DATAPULLPAGE($accessToken,$scope,($x+1));
                    $body=array_merge($body,$temp);
                }
            }

            return $body;
        }
        return $body;
    }

    public function Setdata($Refreshtoken)
    {
        $this->RefreshToken=$Refreshtoken;
        $this->charID($this->AccesTokenDispencer());
    }

    public function Puller($pulltype,$char,$pull,$ID)
    {
        if ((empty($char)) and (empty($ID))){$char=$this->CharID;$scope = "$pulltype/$char/$pull";}
        elseif((empty($char)) and (!empty($ID))){$char=$this->CharID;$scope = "$pulltype/$char/$pull/$ID";}
        elseif((!empty($char)) and (!empty($ID))) {$scope = "$pulltype/$char/$pull/$ID";}
        elseif((!empty($char)) and (empty($pull))){$scope = "$pulltype/$char";}
        elseif ((!empty($char)) and (empty($ID))) {$char=$this->CharID;$scope = "$pulltype/$char/$pull";;}

        return $this->DATAPULL($this->AccesTokenDispencer(),$scope);
    }

    public function Scopinator($scope,$id)
    {
        switch ($scope){
            case "wallet":

                $value = $this->Puller("characters",'',"wallet","journal");
                $keyarray = array('second_party_id','tax_receiver_id','first_party_id');
                $value = $this->CharCorpAllyconverter($keyarray,$value);
                $value = array("Current"=>$this->Puller("characters",'',"wallet")) + $value;

                break;
            case "assets":
                $value = $this->Puller("characters",'',"assets");
                $keyarray = array('type_id');
                $this->AssetsHandler($value);
                break;
            case "mail":
                if(empty($id)){$value = $this->Puller("characters",'',"mail");
                    $keyarray = array('from','recipient_id');
                    $value = $this->CharCorpAllyconverter($keyarray,$value);}
                elseif (!empty($id)){$value = $this->Puller("characters",'',"mail",$id);}

                break;
            case "titles":
                $value = $this->Puller("characters",'',"titles");
                break;
            case "blueprints":
                if(!empty($_POST['number']))
                {

                    $value = $this->Puller("characters",'',"blueprints",$_POST['number']);
                    $_POST['number'] = '';
                }
                else
                {
                    $value = $this->Puller("characters",'',"blueprints");
                    $keyarray = array('type_id');
                    $value = $this->CharCorpAllyconverter($keyarray,$value);
                }
                break;
            case "bookmarks":
                $value = $this->Puller("characters",'',"bookmarks");
                $keyarray = array('creator_id','location_id');
                $value = $this->CharCorpAllyconverter($keyarray,$value);
                break;
            case "login":
                $value = $this->Puller("characters",'',"online");
                break;
            case "pi":
                $value = $this->Puller("characters",'',"planets");
                break;
            default:
                $value = "error";
        }
        if(empty($value)){$value = array($scope=>"empty");}
        return $value;
    }

    public function DEBUG()
    {
        $this->Standings();
        echo "<br> my id " . $this->CharID;
//        echo "<br> my login ". $this->Client_Basic;
//        echo "<br> OR my login ". base64_decode($this->Client_Basic);
        echo "<br>";
        echo  $this->AccesTokenDispencer();
    }
}
;