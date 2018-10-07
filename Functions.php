-<?php

function keycheck($array, $returnarray, $keycheck)
{
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $temp = keycheck($value, $returnarray, $keycheck);
            if ($temp) {
                $returnarray = $returnarray + $temp;
            }
        } else {
            if ($key == $keycheck) {
                $returnarray[$value] = $value;
            }
        }

    };
    return $returnarray;
}

function safedata($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function decho($echos)
{
    $x = 1;
    if ($x == 1) {
        echo "$echos";
    };
}

function dprintr($printer)
{
    echo "<pre>";
    print_r($printer);
    echo "</pre>";
}

function loggedin($str, $yn)
{
    if ($_SESSION['loggedin'] == $yn) {
        echo $str;
    }
}

class localEveDB
{
    private $Host;
    private $dbName;
    private $dbPass;
    private $Charset;
    private $inventoryType;
    private $stations;

    public function __construct()
    {
        include 'Config.php';

        $this->Host = $EVEDBHost;
        $this->dbName = $EVEDBdbName;
        $this->dbPass = $EVEDBdbPass;
        $this->Charset = $EVEDBCharset;
        $this->inventoryType = $EVEDBinventoryType;
        $this->stations = $EVEDBstations;
    }

//________________PRIVATE FUNCTION HOUSE_______________________________________________________________________________\\

    private function getScoop($data)
    {
        //creates a array where
        // array 0: all id's of typeID
        // array 1: all id's of stations
        // array 2: all id's which are not found between the range's of id's
        $typeID = array();
        $stationID = array();
        $notFound = array();
        foreach ($data as $key => $value) {
            if ($value >= 0 && $value <= 400000) {
                $typeID[$key] = $value;
            } elseif ($value >= 60000000 && $value <= 60020000) {
                $stationID[$key] = $value;
            } else {
                $notFound[$key] = $value;
            }
        }
        $returnArray = array('type_id' => $typeID, 'stationID' => $stationID, 'bad' => $notFound);
        return $returnArray;
    }

    protected function Connect()
    {
        $connect = new PDO("mysql:host=$this->Host;dbname=$this->dbName;charset=$this->Charset", $this->dbName, $this->dbPass);
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connect;
    }

    private function queryHandler($query)
    {
        try {
            $conn = $this->Connect();
            $stmt = $conn->query($query);
            if ($stmt == false) {
                return false;
            }
            while ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
                return $row;
            }
        } catch (PDOException $e) {
            die($e);
        }
    }

    private function queryWriter($arrays, $select, $from, $where)
    {
        $query = '';
        foreach ($arrays["$where"] as $key => $value) {
            $query = $query . " SELECT $select FROM $from WHERE $where = '$value' UNION ALL";
        }
        $query = substr($query, 0, -9); //removes UNION ALL
        return $query;
    }

    private function keyReplacer($row, $IDname)
    {
        //fills array with typeID data.
        $returnData = array();
        foreach ($row as $key => $value) {
            $returnData[$value[$IDname]] = $row[$key];
        }
        return $returnData;
    }

    private function dataChecker($returnData, $checkData, $IDname)
    {
        $badID = array();
        foreach ($returnData as $key => $value) {
            $data5 = $checkData[$value][$IDname];
            if (empty($data5)) {
                array_push($badID, $value);
            }
        }
        return $badID;
    }

//________________PUBLIC FUNCTION HOUSE________________________________________________________________________________\\

    public function data($data)
        //input is a array with array(0=>"2314", 1=>"2345",...etc
    {
        $notFoundID = array();
        $keyCheck = array();
        // input is an array with all the data in it
        $data2 = $this->getScoop($data);
        // creates a array where
        // array 0: all id's of typeID
        // array 1: all id's of stations
        // array 2: all id's which are not found between the range's of id's
        if (!empty($data2['type_id'])) {
            //input is the data array, select names, from table, where
            $select = "type_id, typeName, description";
            $from = $this->inventoryType;
            $where = "type_id";
            $data3 = $this->queryWriter($data2, $select, $from, $where);
            //create a query for type_id

            $row = $this->queryHandler($data3);
            //execute query

            $keyCheck = $this->keyReplacer($row, 'type_id');
            //replace keys

            $notFoundID = $this->dataChecker($data2['type_id'], $keyCheck, 'type_id');
            //fills a array with failed type_id
        }

        //input is the data array, select names, from table, where
        if (!empty($data2['stationID'])) {
            $select = "staStations.stationID, staStations.stationName, mapSolarSystems.solarSystemName,  mapConstellations.constellationName, mapRegions.regionName";
            $from = $this->stations . " INNER JOIN mapSolarSystems ON staStations.solarSystemID = mapSolarSystems.solarSystemID
            INNER JOIN mapConstellations ON staStations.constellationID = mapConstellations.constellationID
            INNER JOIN mapRegions ON staStations.regionID =  mapRegions.regionID";
            $where = "stationID";
            $data3 = $this->queryWriter($data2, $select, $from, $where);
            //create a query for stationID

            $row = $this->queryHandler($data3);
            //executes query

            $keyCheck2 = $this->keyReplacer($row, 'stationID');
            foreach ($keyCheck2 as $key => $value) {
                $keyCheck[$key] = $value;
            }
            //replace keys and add data to data array in returnarray.

            $notFoundID2 = $this->dataChecker($data2['stationID'], $keyCheck, 'stationID');
            foreach ($notFoundID2 as $key => $value) {
                array_push($notFoundID, $value);
            }
        }
        //fills a array with failed stationID


        $returnArray = array($notFoundID, $keyCheck, $data2['bad']);

        return $returnArray;
        // returns a array with 4 arrays in it
        // array '0' contains all ID keys which were in the scope but not found in the database.
        // array '1' contains all data with typeID or stationID as key and as data typeID, typeName and description or stationID and stationName.
        // array '2' contains all ID's which were not inside the scope and not found in the database.
    }

//_____________________________________________________________________________________________________________________\\
}

class localEveCache extends localEveDB
{
    private $connect;
    private $daysCharacter;
    private $daysCorporation;
    private $daysAlliance;
    private $daysStructure;

    public function __construct()
    {
        localEveDB::__construct();
        include 'Config.php';
        $this->connect = $this->Connect();
        $this->daysCharacter = date("Y-m-d H:i:s", strtotime($daysCharacter));
        $this->daysCorporation = date("Y-m-d H:i:s", strtotime($daysCorporation));
        $this->daysAlliance = date("Y-m-d H:i:s", strtotime($daysAlliance));
    }

    private function selectQueryMaker($array, $input)
    {
        $query = "";
        if (empty($input)) {
            foreach ($array as $key => $value) {
                $query = $query . " SELECT * FROM groupCache WHERE ID = '$value' UNION ALL";
            }
            $query = substr($query, 0, -9);
            return $query;
        } elseif ($input == "1") {
            $query = "";
            foreach ($array as $key => $value) {
                $query = $query . " SELECT ID FROM groupCache WHERE ID = '$value' UNION ALL";
            }
            $query = substr($query, 0, -9);
            return $query;
        } elseif ($input == "3") {
            foreach ($array as $key => $value) {
                $query = $query . " SELECT * FROM structureCache WHERE structureID = '$value' UNION ALL";
            }
            $query = substr($query, 0, -9);
            return $query;
        }
    }

    private function selectQuery($array, $input)
    {
        $query = $this->selectQueryMaker($array, $input);
        $stmt = $this->connect->query($query);
        while ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            $data = $this->dateChecker($row, $input);
            foreach ($data["validID"] as $key => $value) {
                if (in_array($key, $array)) {
                    unset($array[$key]);
                }
                foreach ($value as $key2 => $value2) {
                    if (!($value2)) {
                        unset($data["validID"][$key][$key2]);
                    }
                }
            }
            if (!empty($array)) {
                foreach ($array as $key => $value) {
                    if (array_key_exists($key, $data["expiredID"])) {
                        unset($array[$key]);
                    } elseif (array_key_exists($key, $data["validID"])) {
                        unset($array[$key]);
                    }
                }
                $data["unknown"] = $array;
            }
        }
        if (empty($row)) {
            $data["unknown"] = $array;
        }
        if (empty($data["unknown"])) {
            unset($data["unknown"]);
        }
        return $data;
    }

    private function solarsystemSearcher($array)
    {
        $query = "";
        foreach ($array as $key => $value) {
            $query = $query . " SELECT mapSolarSystems.solarSystemName, mapConstellations.constellationName, mapRegions.regionName FROM mapSolarSystems INNER JOIN mapConstellations ON mapSolarSystems.constellationID = mapConstellations.constellationID INNER JOIN mapRegions ON mapSolarSystems.regionID = mapRegions.regionID WHERE mapSolarSystems.solarSystemID = '30004759' UNION ALL";
        }
        $query = substr($query, 0, -9);
        $stmt = $this->connect->query($query);
        while ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            return $row;
        }
    }

    private function dateChecker($data, $input)
    {
        if (empty($input)) {
            foreach ($data as $key => $value) {
                switch ($value["type"]) {
                    case "character":
                        $datetimeUpload = $data[$key]["datetimeUpload"];
                        $datetime = date("Y-m-d H:i:s", strtotime($datetimeUpload));
                        if ($datetime < $this->daysCharacter) {
                            $data["expiredID"] = array($data[$key]["ID"] => array("ID" => $data[$key]["ID"], "type" => $data[$key]["type"]));
                            unset($data[$key]);
                        } else {
                            $data["validID"][$data[$key]["ID"]] = $value;
                            unset($data[$key]);
                        }
                        break;
                    case "corporation":
                        $datetimeUpload = $data[$key]["datetimeUpload"];
                        $datetime = date("Y-m-d H:i:s", strtotime($datetimeUpload));
                        if ($datetime < $this->daysCorporation) {
                            $data["expiredID"] = array($data[$key]["ID"] => array("ID" => $data[$key]["ID"], "type" => $data[$key]["type"]));
                            unset($data[$key]);
                        } else {
                            $data["validID"][$data[$key]["ID"]] = $value;
                            unset($data[$key]);
                        }
                        break;
                    case "alliance":
                        $datetimeUpload = $data[$key]["datetimeUpload"];
                        $datetime = date("Y-m-d H:i:s", strtotime($datetimeUpload));
                        if ($datetime < $this->daysAlliance) {
                            $data["expiredID"] = array($data[$key]["ID"] => array("ID" => $data[$key]["ID"], "type" => $data[$key]["type"]));
                            unset($data[$key]);
                        } else {
                            $data["validID"][$data[$key]["ID"]] = $value;
                            unset($data[$key]);
                        }
                        break;
                    default:
                        echo "you broke the code.";
                        break;
                }
            }
        } elseif ($input == "3") {
            foreach ($data as $key => $value) {
                $datetimeUpload = $data[$key]["datetimeUpload"];
                $datetime = date("Y-m-d H:i:s", strtotime($datetimeUpload));
                if ($datetime < $this->daysStructure) {
                    $data["expiredID"] = array($data[$key]["StructureID"] => $data[$key]["StructureID"]);
                    unset($data[$key]);
                } else {
                    echo "hoi";
                    $data["validID"][$data[$key]["StructureID"]] = $value;
                    unset($data[$key]);
                }
            }
        }
        return $data;
    }

    private function notFoundIDFixer($array, $charFunc, $corpFunc, $allyFunc, $unknownFunc)
    {
        //expiredID
        foreach ($array["expiredID"] as $key => $value) {
            if ($value["type"] == "character") {
                $charInfo = $charFunc(array($key => $key));
                $charInfo[$key]["type"] = "character";
                $array["upload"][$key] = $charInfo[$key];
                $array["validID"][$key] = $charInfo[$key];
                unset($array["expiredID"][$key]);
            } elseif ($value["type"] == "corporation") {
                $corpInfo = $corpFunc(array($key => $key));
                $corpInfo[$key]["type"] = "corporation";
                $array["upload"][$key] = $corpInfo[$key];
                $array["validID"][$key] = $corpInfo[$key];
                unset($array["expiredID"][$key]);
            } elseif ($value["type"] == "alliance") {
                $allyInfo = $allyFunc(array($key => $key));
                $allyInfo[$key]["type"] = "alliance";
                $array["upload"][$key] = $allyInfo[$key];
                $array["validID"][$key] = $allyInfo[$key];
                unset($array["expiredID"][$key]);
            }
        }
        if (empty($array["expiredID"])) {
            unset($array["expiredID"]);
        }

        //unknown
        foreach ($array["unknown"] as $key => $value) {
            $unknownInfo = $unknownFunc(array($key => $key), $charFunc, $corpFunc, $allyFunc);
            $array["validID"][$key] = $unknownInfo[$key];
            $array["upload"][$key] = $unknownInfo[$key];
            unset($array["unknown"][$key]);
        }
        if (empty($array["unknown"])) {
            unset($array["unknown"]);
        }
        $returnArray = $this->IDsplitter($array, $charFunc, $corpFunc, $allyFunc, $unknownFunc);
        return $returnArray;
    }

    private function structNotFoundFixer($array, $structFunc)
    {
        $temp2 = array();
        if (!empty($array["expiredID"])) {
            $temp = $structFunc($array["expiredID"]);
            foreach ($temp as $key => $value) {
                $temp2[$value["corporation_id"]] = $value["corporation_id"];
            }
            $alliance_id = $this->groupCache($temp2);
            dprintr($temp2);
        }

        //            foreach ($temp as $key => $value){
//                $temp2[$key] = $value["solarSystemID"];
//            }
//            $solarSystemNames = $this->solarsystemSearcher(array("30004759"));
//            dprintr($solarSystemNames);
//            $temp[$key]["solarSystemName"] = ;
//            $temp[$key]["constellationName"] = ;
//            $temp[$key]["regionName"] = ;
//            dprintr($temp2);
    }

    private function IDsplitter($array, $charFunc, $corpFunc, $allyFunc, $unknownFunc)
    {
        $searchID = array();
        foreach ($array["validID"] as $key => $value) {
            if (array_key_exists("corporation_id", $value)) {
                if (!array_key_exists($value["corporation_id"], $searchID)) {
                    $searchID[$value["corporation_id"]] = $value["corporation_id"];
                }
            }
            if (array_key_exists("alliance_id", $value)) {
                if (!array_key_exists($value["alliance_id"], $searchID)) {
                    $searchID[$value["alliance_id"]] = $value["alliance_id"];
                }
            }
        }
        $result = $this->selectQuery($searchID);
        if ($result["unknown"]) {
            $temp = $unknownFunc($result["unknown"], $charFunc, $corpFunc, $allyFunc);
            if ($array["upload"]) {
                $array["upload"] = $array["upload"] + $temp;
            } else {
                $array["upload"] = $temp;
            }
            if ($result["validID"]) {
                $result["validID"] = $result["validID"] + $temp;
            } else {
                $result["validID"] = $temp;
            }
        }
        if ($result["expiredID"]) {
            $corp = array();
            $ally = array();
            $returnarray = array();
            foreach ($result["expiredID"] as $key => $value) {
                switch ($value["type"]) {
                    case "corporation":
                        $corp[$key] = $key;
                        break;
                    case "alliance":
                        $ally[$key] = $key;
                        break;
                }
            }

            $corp = $corpFunc($corp);
            $ally = $allyFunc($ally);
            if ($corp) {
                $returnarray = $returnarray + $corp;
            }
            if ($ally) {
                $returnarray = $returnarray + $ally;
            }
            if ($returnarray) {
                $result["validID"] = $result["validID"] + $returnarray;
                $array["upload"] = $array["upload"] + $returnarray;
            }
        }
        foreach ($array["validID"] as $key => $value) {
            $array["validID"][$key]["corporation_id"] = $result["validID"][$array["validID"][$key]["corporation_id"]];
            $array["validID"][$key]["alliance_id"] = $result["validID"][$array["validID"][$key]["alliance_id"]];
        }
        return $array;
    }

    public function insertUpdate($array)
    {
        $query1 = "";
        $query2 = "";
        date_default_timezone_set('Atlantic/Reykjavik');
        $time = date("Y-m-d H:i:s");
        $temp = array();
        $insert = array();
        $update = array();
        foreach ($array as $key => $value) {
            array_push($temp, $value["ID"]);
        }
        $temp = $this->selectQueryMaker($temp, "1");
        $stmt = $this->connect()->query($temp)->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($stmt)) {

            foreach ($stmt as $value) {
                if ($array[$value["ID"]]) {
                    $update[$value["ID"]] = $array[$value["ID"]];
                    unset($array[$value["ID"]]);
                }
            }
        }
        if ($array) {
            $insert = $array;
        }
        $keyArray = array("ID", "type", "name", "description", "creationDate", "SecStatus", "corporation_id", "alliance_id", "home_station_id", "ticker", "px64x64", "px128x128", "ceo_id", "creator_id", "member_count", "url");
        if ($update) {
            foreach ($update as $key2 => $value2) {
                $string = "";
                foreach ($keyArray as $value3) {
                    $temp = "";
                    if ($value3 == "description") {
                        $temp = ", $value3 = '" . addslashes($value2['description']) . "'";
                    } elseif ($value2[$value3]) {
                        $temp = ", $value3 = '" . addslashes($value2[$value3]) . "'";
                    }
                    if ($temp) {
                        $string = "$string$temp";
                    }
                }
                if ($string) {
                    $string = substr($string, 2);
                    $query1 .= " UPDATE groupCache SET $string, datetimeUpload = '$time' WHERE ID = $value2[ID];";
                }
            }
        }
        if ($insert) {
            foreach ($insert as $key => $value) {
                $string = array();
                foreach ($keyArray as $keys) {
                    if ($value[$keys]) {
                        $string['keys'] = "$string[keys], $keys";
                        $string['values'] = "$string[values], '" . addslashes($value[$keys]) . "'";
                    } elseif ($keys == "description") {
                        $string['keys'] = "$string[keys], $keys";
                        $string['values'] = "$string[values], ''";
                    }
                }

                $string['keys'] = substr($string['keys'], 2);
                $string['values'] = substr($string['values'], 2);
                $query2 = $query2 . " INSERT INTO groupCache (" . $string['keys'] . ", datetimeUpload) VALUES (" . $string['values'] . ", '$time');";
            }
        }
        $totalQuery = $query1 . $query2;
        $stmt = $this->connect()->query($totalQuery);
        if ($stmt && $totalQuery) {
            return true;
        } else {
            return false;
        }
    }

    public function groupCache($array, $charFunc, $corpFunc, $allyFunc, $unknownFunc)
    {
        $data1 = $this->selectQuery($array);                                        //data1 is initial ID's
        $data2 = $this->notFoundIDFixer($data1, $charFunc, $corpFunc, $allyFunc, $unknownFunc);
        if ($data2["upload"]) {
            $data3 = $this->insertUpdate($data2["upload"]);
        }
        return $data2["validID"];
    }

    public function structCache($array, $structFunc)
    {
        dprintr($array);
        $data1 = $structFunc($array);
        dprintr($data1);
//        foreach ($data1 as $key => $value) {
//            foreach ($value as $key2 => $value2) {
//                if ($key2 == "solar_system_id") {
//
//                }
//            }
//        }
//        $var = "SELECT mapSolarSystems.solarSystemName, mapConstellations.constellationName, mapRegions.regionName FROM mapSolarSystems INNER JOIN mapConstellations ON mapSolarSystems.constellationID = mapConstellations.constellationID INNER JOIN mapRegions ON mapSolarSystems.regionID = mapRegions.regionID WHERE mapSolarSystems.solarSystemID = '30000001' ";
//        echo $array;
    }

}

class DBconn
{
    private $Host;
    private $dbName;
    private $dbPass;
    private $Charset;

    public function __construct()
    {
        include 'Config.php';
        $this->Host = $Host;
        $this->dbName = $dbName;
        $this->dbPass = $dbPass;
        $this->Charset = $Charset;
    }

//________________PRIVATE FUNCTION HOUSE_______________________________________________________________________________\\

    public function ListShitter($yn)
    {
        $connect = $this->Connect();
        if ($yn == true) {
            $query = "SELECT users.characterOwnerHash FROM users INNER JOIN qanswers ON users.main_ID = qanswers.main_ID WHERE users.user_level = '2' AND qanswers.status = '0'";
        } elseif ($yn == false or empty($yn)) {
            $query = "SELECT users.characterOwnerHash FROM users WHERE users.user_level = '2'";
        }
        $stmt = $connect->query($query);
        while ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            $temparray = $this->arrayReader($row, 'characterOwnerHash');
        }
        return $temparray;

    }

    private function arrayReader($row, $checker)
    {
        if (!empty($checker)) {
            $temp = $this->arrayChecker($row);
            $len = $temp[0];
            $inhoudArray = array("delete" => 1);
            for ($x = 0; $x < $len; $x++) {
                array_unshift($inhoudArray, $row[$x][$checker]);
            }
            array_pop($inhoudArray);
        } else {


            $temp = $this->arrayChecker($row);
            $len = $temp[0];
            $inhoudArray = array("delete" => 1);
            for ($x = 0; $x < $len; $x++) {
                array_unshift($inhoudArray, $row[$x][TABLE_NAME]);
            }
            array_pop($inhoudArray);
            array_unshift($inhoudArray, $len);
        }
        return $inhoudArray;
    }  //3 = nowhere 2 = alts 1 = users

    private function arrayChecker($row)
    {
        $totalarray = ((count($row, 0)) - 1);
        for ($x = 0; $x <= $totalarray; $x++) {
            $inhoudarray[$x] = count($row[$x], 0);
        }
        array_unshift($inhoudarray, ($totalarray + 1));
        return $inhoudarray;
    }

    public function advancedListDispenser($characterOwnerHash)
    {
        $returnarray = array();
        $conn = $this->Connect();
        $query = "SELECT characterOwnerHashApplyer FROM recruitment WHERE recruiterID = '$characterOwnerHash' and status = 0";
        $stmt = $conn->query($query);
        while ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            foreach ($row as $hash) {
                $returnarray[] = $this->evidenceDispenser($hash['characterOwnerHashApplyer']);
            }
            return $returnarray;
        }
    }

    public function evidenceDispenser($characterOwnerHashApplyer) //return array(array('character_name' => $mainInfo['character_name'], 'characterID' => $mainInfo['characterID'], 'refresh_token' => $mainInfo['refresh_token'], 'altArray' => array('username' =>, 'charID' =>, 'refreshtoken' =>, 'characterOwnerHash' =>), 'questions =>' array('0' =>, '1' =>...)
    {
        $questions = new questions();
        $mainInfo = $this->getCharacterInfo($characterOwnerHashApplyer);
        $questionsArray = $questions->questionHandler($characterOwnerHashApplyer, '');
        $altArray = $this->altListDispenser($characterOwnerHashApplyer);
        return array('character_name' => $mainInfo['character_name'], 'characterID' => $mainInfo['characterID'], 'refresh_token' => $mainInfo['refresh_token'], 'characterOwnerHash' => $characterOwnerHashApplyer, 'altArray' => $altArray, 'questions' => $questionsArray);
    }

    public function getCharacterInfo($characterOwnerHash)
    {
        $conn = $this->Connect();
        switch ($this->whereIs($characterOwnerHash, $conn)) {
            case 1:  //users
                $query = "SELECT users.username, users.main_ID, main.charID, main.refreshtoken FROM users INNER JOIN main ON users.main_ID = main.ID WHERE users.characterOwnerHash = '$characterOwnerHash'";
                break;
            case 2:  //alts
                $query = "SELECT alts.charID, alts.refreshtoken, alts.username, alts.main_ID FROM alts WHERE alts.characterOwnerHash = '$characterOwnerHash'";
                break;
            case 3:
                return false;
                echo 'error';
                break;
        }
//        echo $query;
        $stmt = $conn->query($query);
        while ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
// Array ( [0] => Array ( [username] => test [main_ID] => 91 [charID] => 123 [refreshtoken] => ) )
            $CharID = $row[0][charID];
            $RefreshToken = $row[0][refreshtoken];
            $charName = $row[0][username];
            $mainid = $row[0][main_ID];

        }
        Return array("characterID" => $CharID, "refresh_token" => $RefreshToken, "character_name" => $charName, "main_ID" => $mainid);
    }

    private function whereIs($characterOwnerHash, $conn)
    {
        if ($this->registerChecker('users', 'characterOwnerHash', $characterOwnerHash, $conn) == false) {
            if ($this->registerChecker('alts', 'characterOwnerHash', $characterOwnerHash, $conn) == false) {
                return 3;
            } else {
                return 2;
            }
        } else {
            return 1;
        }
    }

    private function registerChecker($tableName, $ColumnName, $data, $conn)
    {
        try {
            $query = "SELECT $tableName.$ColumnName FROM $tableName WHERE $ColumnName = '$data'";
            $stmt = $conn->query($query);
            while ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {

                $temp = $this->arrayReader($row, $ColumnName);
                $temp = $temp[0];
                if ($temp !== $data) {
                    return false;
                } elseif ($temp == $data) {
                    return true;
                } else {
                    echo 'went wrong<BR>';
                    echo $temp;
                    return 2;
                }
            }
        } catch (PDOException $e) {
            die($e);
        }
    }

    public function altListDispenser($mainCharacterOwnerHash)
    {
        $conn = $this->Connect();
        $query = "SELECT alts.username, alts.charID, alts.refreshtoken, alts.characterOwnerHash FROM alts INNER JOIN users ON alts.main_ID = users.main_ID WHERE users.characterOwnerHash = '$mainCharacterOwnerHash'";
        $stmt = $conn->query($query);
        while ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            foreach ($row as $key => $value) {
                $row[$key]['refresh_token'] = $row[$key]['refreshtoken'];
                $row[$key]['character_name'] = $row[$key]['username'];
                $row[$key]['characterID'] = $row[$key]['charID'];
                unset($row[$key]['refreshtoken']);
                unset($row[$key]['username']);
                unset($row[$key]['charID']);
            }
            return $row;
        }
    }

    public function Connect()
    {
        $connect = new PDO("mysql:host=$this->Host;dbname=$this->dbName;charset=$this->Charset", $this->dbName, $this->dbPass);
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connect;
    }

//________________PUBLIC FUNCTION HOUSE________________________________________________________________________________\\

    public function userLevelDispenser($characterOwnerHash)
    {
        $connect = $this->Connect();
        $query = "SELECT users.user_level FROM users WHERE characterOwnerHash = '$characterOwnerHash'";
        $stmt = $connect->query($query);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $user_level = $row['user_level'];
        }
        return $user_level;
    }  //"characterID"=>$CharID,"refresh_token"=>$RefreshToken,"character_name" => $charName, "main_ID" => $mainid

    public function Echoall()
    {
        echo "<pre>";
        echo "$this->Host <Br>";
        echo "$this->dbName <Br>";
        echo "$this->dbPass<br>";
        echo "$this->Charset<br>";
        echo "</pre>";
    }

    public function registerAlt($charOwnHash, $charID, $refreshToken, $charName)
    {
        if (empty($charOwnHash) or empty($charID) or empty($refreshToken) or empty($charName)) {
            return 5;
        }
        $yn = $this->databaseDuplicateFinder("characterOwnerHash", $charOwnHash);
        if ($yn == true) { //Hash already found in the Database.   You already have a account
            return 3;
        } elseif ($yn == false) {
            $charInfo = $this->getCharacterInfo($_SESSION['characterOwnerHash']);
            $main_ID = $charInfo['main_ID'];
            $this->insertAltData($charOwnHash, $refreshToken, $charID, $charName, $main_ID);
            return 4;   //You now have a account.
        } else {
            echo 'something went terribly wrong';
        }
    }

    public function databaseDuplicateFinder($ColumnName, $data, $scope)
    {
        if (empty($scope)) {
            $conn = $this->Connect();
            $query = "SELECT DISTINCT TABLE_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME IN ('$ColumnName') AND TABLE_SCHEMA='$this->dbName' ";
            $stmt = $conn->query($query);
            while ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
                $temp = $this->arrayReader($row);
                $len = $temp[0];
                array_shift($temp);
                for ($z = 0; $z < $len; $z++) {
                    $yesno = $this->registerChecker($temp[$z], $ColumnName, $data, $conn);
                    if ($yesno == true) {
                        return true;
                    } elseif ($yesno == 2) {
                        echo ' really wrong';
                    }
                }
                return false;
            }
        } else {
            $query = "SELECT $ColumnName FROM $scope WHERE $ColumnName = '$data'";
            $conn = $this->Connect();
            $stmt = $conn->query($query);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($row["$ColumnName"] == $data) {
                    return 1;
                }
            }
        }
    }

    private function insertAltData($characterOwnerHash, $refreshtoken, $charID, $username, $main_ID)
    {
        $conn = $this->Connect();
        $query = "INSERT INTO alts(main_ID, username, charID, refreshtoken, characterOwnerHash) VALUES ('$main_ID', '$username', '$charID', '$refreshtoken' , '$characterOwnerHash')";
        $conn->query($query);
    }

    public function register($charOwnHash, $charID, $refreshToken, $charName)
    {
        if (empty($charOwnHash) or empty($charID) or empty($refreshToken) or empty($charName)) {
            return '';
        }
        $yn = $this->databaseDuplicateFinder("characterOwnerHash", $charOwnHash);
        if ($yn == true) { //Hash already found in the Database.   You already have a account
            return 1;
        } elseif ($yn == false) {

            $this->insertdbdata($charOwnHash, $charID, $refreshToken, $charName);
            return 2;   //You now have a account.

        } else {
            return 5;
        }
    }

    private function insertdbdata($characterOwnerHash, $charID, $refreshToken, $username, $mainid)
    {
        try {
            $connect = $this->connect();
            $query = "INSERT INTO main(charID, refreshtoken, portraitLink) VALUES ('$charID', '$refreshToken', '0')";
            $connect->query($query);
            sleep(0.5);
            $query = "SELECT main.ID FROM main WHERE charID = '$charID';";
            $stmt = $connect->query($query);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $mainid = $row['ID'];
            }
            $time = date("Y-m-d H:i:s");
            $query = "INSERT INTO users(username, date, characterOwnerHash, main_ID) VALUES ('$username', '$time', '$characterOwnerHash', '$mainid')";
            $connect->query($query);
            return true;
        } catch (PDOException $e) {
            die($e);
        }
    }

    public function Login($charID, $CharOwnHash)
    {
        if ($_SESSION['loggedin'] == true) {
            return false;
        } elseif ($_SESSION['loggedin'] == false) {
            $login = $this->getdbdata($charID, $CharOwnHash);
            if ($login == true) {
                Echo "You're logged in";
                $_SESSION['loggedin'] = true;
                return true;
            } elseif ($login == false) {
                return false;
            } else {
                echo 'error';
            }
            if (empty($_SESSION['characterOwnerHash'])) {
                echo 'leeg';
            } else {
                echo '<br>' . $_SESSION['characterOwnerHash'];
            }
            if (empty($_SESSION['char_ID'])) {
                echo 'leeg';
            } else {
                echo '<br>' . $_SESSION['char_ID'];
            }
        } else {
            die('hacker');
        }
    }

    private function getdbdata($charID, $characterOwnerHash)
    {
        try {
            $_SESSION['characterOwnerHash'] = '';
            $_SESSION['char_ID'] = '';
            $connect = $this->connect();
            $query = "SELECT users.characterOwnerHash, main.charID, main.refreshtoken FROM users INNER JOIN main ON users.main_ID = main.ID where users.characterOwnerHash = '$characterOwnerHash' AND main.charID = '$charID'";
            $stmt = $connect->query($query);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $hash = $row['characterOwnerHash'];
                $char_id = $row['charID'];
                $_SESSION['characterOwnerHash'] = $hash;
                $_SESSION['char_ID'] = $char_id;
                $_SESSION['refresh_token'] = $row["refreshtoken"];
                if ($_SESSION['characterOwnerHash'] == $hash && $_SESSION['char_ID'] == $char_id) {
                    return true;
                }
            }
            if ($_SESSION['characterOwnerHash'] == '' && $_SESSION['char_ID'] == '') {
                return false;
            }
        } catch (PDOException $e) {
            die($e);
        }
    }

    public function arrayKeyChanger($array)
    {
        $returnArray = array();
        $returnArray[0] = "";
        foreach ($array as $key => $value) {
            array_push($returnArray, $value);
        }
        return $returnArray;
    }

//_____________________________________________________________________________________________________________________\\
}

class questions extends DBconn
{
    public function questionSupport($choice, $var1, $var2)
    {
        if ($choice == 1) {
            $row = $var1;
            $checker = $var2;
            $len = count($row, 1);
            $inhoudArray = array("delete" => 1);
            for ($x = 0; $x < $len; $x++) {
                $tempChecker = $checker . ($x + 1.) . ' ';
                echo $tempChecker;
                array_unshift($inhoudArray, $row[0][$tempChecker]);
            }
            array_pop($inhoudArray);
            $return = $this->questionSupport(2, $inhoudArray);
            return $return;
        } elseif ($choice == 2) {
            $inhoudArray = array("delete" => 1);
            foreach ($var1 as $value) {
                if ($value !== '0' and $value !== false and !empty($value)) {
                    array_unshift($inhoudArray, $value);
                }
            }
            array_pop($inhoudArray);
            print_r($inhoudArray);
            return $inhoudArray;
        } elseif ($choice == 3) {
            $questions = $var1;
            $answers = $var2;
            $newarray = array("delete" => 1);
            $len = count($questions, 1);
            for ($x = 0; $x < $len; $x++) {
                $tempValue = "question" . ($x + 1);
                $value = $questions[$x] . ' : ' . $answers[$tempValue];
                array_unshift($newarray, $value);
            }
            array_pop($newarray);
            return $this->questionSupport(2, $newarray);
        }
    }

    public function questionHandler($characterOwnerHash, $questionArray)
    {
        $conn = DBconn::Connect();
        if (!empty($characterOwnerHash) and empty($questionArray)) {
            if (strlen($characterOwnerHash) < 50) {
                $mainid = DBconn::getCharacterInfo($characterOwnerHash)['main_ID'];
                $query = "SELECT * FROM qanswers WHERE main_ID = '$mainid'";
                $stmt = $conn->query($query);
                while ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
                    return $row;
                }
            } else {
                $query = "SELECT ID FROM main WHERE refreshtoken = '$characterOwnerHash'";
                $stmt = $conn->query($query);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $query = "SELECT * FROM qanswers WHERE main_ID = '$row[ID]'";
                    $stmt = $conn->query($query);
                    $temparray = array();
                    while ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
                        foreach ($row[0] as $key => $value) {
                            if (!empty($value)) {
                                $temparray[$key] = $value;
                            }
                        }
                        return $temparray;
                    }
                }
            }
        } else {
            echo 'error';
        }
    }

    public function questionInserter($data)
    {
        $conn = DBconn::Connect();
        $query2 = "";
        $query3 = "";
        $count = count($data);
        foreach ($data as $key => $value) {
            if ($key == "$count") {
                $query2 = $query2 . "question" . $key;
                $query3 = $query3 . "'$value'";
            } else {
                $query2 = $query2 . "question$key, ";
                $query3 = $query3 . "'$value', ";
            }
        }
        $query = "INSERT INTO questions($query2) VALUES ($query3)";
        $stmt = $conn->query($query);
        return $stmt;
    }

    public function qanswerInserter($data, $main_ID)
    {
        $conn = DBconn::Connect();
        $query2 = "";
        $query3 = "";
        $dbInsertData = array();
        $questions = $this->currentQuestion();
        foreach ($questions as $key => $value) {
            $combinedData = $value . " : " . $data[$key];
            $dbInsertData[$key] = $combinedData;
        }
        dprintr($dbInsertData);
        $count = count($dbInsertData);
        echo $count;
        foreach ($dbInsertData as $key => $value) {
            if ($key == "question$count") {
                $query2 = $query2 . $key;
                $query3 = $query3 . "'$value'";
            } else {
                $query2 = $query2 . "$key, ";
                $query3 = $query3 . "'$value', ";
            }
        }
        $query = "INSERT INTO qanswers($query2, main_ID, status, edit) VALUES ($query3, '$main_ID', '0', '0')";
        $stmt = $conn->query($query);
        return $stmt;
    }

    public function currentQuestion()
    {
        $max = "";
        $conn = DBconn::Connect();
        $query = "SELECT ID FROM questions";
        $stmt = $conn->query($query);
        while ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            $max = max($row);
            $max = $max['ID'];
        }
        $query = "SELECT * FROM questions WHERE questions.ID = '$max'";
        $stmt = $conn->query($query);
        while ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            $returnData = $this->currentQuestionSupport($row);
            return $returnData;
        }
    }

    private function currentQuestionSupport($data)
    {
        $returnArray = array();
        $data = array_reverse($data[0]);
        array_pop($data);
        foreach ($data as $key => $value) {
            if ($value !== "0") {
                $returnArray[$key] = $value;
            }
        }
        $returnArray = array_reverse($returnArray);
        return $returnArray;
    }

    public function questionPuller($refreshToken)
    {
        $conn = DBconn::Connect();
        $query = "SELECT * FROM main WHERE refreshtoken = '$refreshToken'";
        $stmt = $conn->query($query);
        while ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            return $row;
        }
    }
}

class standingList extends DBconn
{
    public function standingPuller($select, $where, $value)
    {
        $conn = DBconn::Connect();
        $query = "SELECT $select FROM standingList WHERE $where = '$value'";
        $stmt = $conn->query($query);
        while ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            foreach ($row as $key => $value) {
                unset($value['ID']);
                $row2[$value['objectID']] = $value;
            }
            return $row2;
        }
    }

    public function standingInserter($ID, $type, $standing, $byWho, $reason, $automatic)
    {
        $conn = DBconn::Connect();
        $yn = is_array($ID);
        $updater = array();
        $query2 = "";
        $allStanding = $this->allStandingPuller();
        $return = 0;
        if ($yn == true) {
            foreach ($allStanding as $key => $value) {
                if (array_key_exists($key, $ID) == true) {
                    $updater[$key] = $ID[$key];
                    unset($ID[$key]);
                }
            }
            if (!empty($updater)) {
                foreach ($updater as $key => $value) {
                    $query2 = $query2 . "UPDATE standingList SET standing='$value[standing]', byWho='$value[byWho]', reason='$value[reason]', automatic='$value[automatic]' WHERE objectID = '$value[objectID]'; ";
                }
                $stmt2 = $conn->exec($query2);
            } else {
                $stmt2 = true;
            }
            $query3 = "";
            if (!empty($ID)) {
                foreach ($ID as $key => $value) {
                    $query3 = $query3 . "('$key', '$value[objectType]', '$value[standing]', '$value[byWho]', '$value[reason]', '$value[automatic]'), ";
                }
                $query = "INSERT INTO standingList(objectID, objectType, standing, byWho, reason, automatic) VALUES $query3";
                $query = substr($query, 0, -2);
                $stmt = $conn->exec($query);
            } else {
                $stmt = true;
            }
            if ($stmt == 0 && $stmt2 == 0) {
                $return = $return + 3;
            } elseif ($stmt == 0) {
                $return = $return + 1;
            } elseif ($stmt2 == 0) {
                $return = $return + 2;
            }
            return $return;
        } elseif ($yn == false) {
            $block = false;
            $stmt3 = "";
            $stmt4 = "";
            foreach ($allStanding as $key => $value) {
                if ($key == $ID) {
                    $block = true;
                }
            }
            if ($block == false) {
                $query = "INSERT INTO standingList(objectID, objectType, standing, byWho, reason, automatic) VALUES ('$ID', '$type', '$standing', '$byWho', '$reason', '$automatic')";
                $stmt4 = $conn->exec($query);
            } else {
                $stmt4 = true;
            }
            if ($block == true) {
                $query = "UPDATE standingList SET standing='$standing', byWho='$byWho', reason='$reason', automatic='$automatic' WHERE objectID = '$ID'";
                $stmt3 = $conn->exec($query);
            } else {
                $stmt3 = true;
            }
            if ($stmt3 == 0 && $stmt4 == 0) {
                $return = $return + 3;
            } elseif ($stmt4 == 0) {
                $return = $return + 1;
            } elseif ($stmt3 == 0) {
                $return = $return + 2;
            }
            return $return;
        }
    }

    public function allStandingPuller()
    {
        $conn = DBconn::Connect();
        $query = "SELECT * FROM standingList";
        $stmt = $conn->query($query);
        while ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            foreach ($row as $key => $value) {
                unset($value['ID']);
                $row2[$value['objectID']] = $value;
            }
            return $row2;
        }
    }

    public function standingRemover($objectID)
    {
        $conn = DBconn::Connect();
        $yn = is_array($objectID);
        echo $yn;
        $query = "";
        if ($yn == true) {
            foreach ($objectID as $key => $value) {
                $query = $query . "DELETE FROM standingList WHERE objectID='$objectID'; ";
            }
            $return = $conn->exec($query);
            return $return;
        } elseif ($yn == false) {
            $query = "DELETE FROM standingList WHERE objectID = '$objectID';";
            $return = $conn->exec($query);
            return $return;
        }
    }
}

class recruitment extends DBconn
{

}

class recruitmentComments extends recruitment
{

    public function insert($comment, $where, $by)
    {
        $conn = DBconn::Connect();
        date_default_timezone_set('Atlantic/Reykjavik');
        $time = date('Y-m-d H:i:s', time());
        $query = "INSERT INTO recruitmentComments (recruitmentID, comment, date, byWho) VALUES ('$where', '$comment', '$time', '$by')";
        $stmt = $conn->exec($query);
        return $stmt;
    }

    public function selectComments($ID)
    {
        $conn = DBconn::Connect();
        $query = "SELECT * FROM recruitmentComments WHERE recruitmentID = '$ID'";
        $stmt = $conn->query($query);
        while ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            return $row;
        }
    }

    //inserter

    public function deleteComments($ID)
    {
        $conn = DBconn::Connect();
        $query = "DELETE FROM recruitmentComments WHERE ID = '$ID';";
        $stmt = $conn->exec($query);
        return $stmt;
    }

    //select

    public function pullAllComments($date, $date2)
    {
        if (empty($date) && empty($date2)) {
            $conn = DBconn::Connect();
            $query = "SELECT * FROM recruitmentComments";
            $stmt = $conn->query($query);
            while ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
                return $row;
            }
        } elseif (empty($date2) && isset($date)) {
            $return = $this->dateSelector("date > '$date'");
            return $return;
        } else {
            $return = $this->dateSelector("date BETWEEN '$date' AND '$date2'");
            return $return;
        }
    }

    //delete

    private function dateSelector($where)
    {
        $conn = DBconn::Connect();
        $query = "SELECT * FROM recruitmentComments WHERE $where";
        $stmt = $conn->query($query);
        while ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            return $row;
        }
    }

    //pull all?

    private function recruitmentIDfinder($conn, $where, $where2)
    {
        $query = "SELECT ID FROM recruitment WHERE recruiterID = '$where' AND CharacterOwnerHashApplyer = '$where2'";
        $stmt = $conn->query($query);
        while ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            return $row;
        }
    }
}

class recruitmentEscalations extends recruitment
{

    //________________PRIVATE FUNCTION HOUSE_______________________________________________________________________________\\
    public function getEscalationData($recruiterID, $seniorCharacterOwnerHash)
    {
        $conn = DBconn::Connect();
        if (empty($recruiterID) and empty($seniorCharacterOwnerHash)) {
            $query = "SELECT recruiterID, seniorCharacterOwnerHash FROM recruitmentEscalations";
        } else if (!empty($recruiterID) and !empty($seniorCharacterOwnerHash)) {
            $query = "SELECT recruiterID, seniorCharacterOwnerHash FROM recruitmentEscalations WHERE recruiterID = '$recruiterID' and seniorCharacterOwnerHash = '$seniorCharacterOwnerHash'";
        } else {
            echo "you broke it";
        }
        $stmt = $conn->query($query);
        while ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            return $row;
        }
    }

    public function insertEscalationData($recruiterID, $CharacterOwnerHashApplyer, $seniorCharacterOwnerHash)
    {
        $conn = DBconn::Connect();
        $helper = $this->insertHelper($recruiterID, $CharacterOwnerHashApplyer, $conn);
        if ($helper[0]['yn'] == 0) {
            // insert recruitmentEscalations
            $helperID = $helper[0]['ID'];
            $query = "INSERT INTO recruitmentEscalations (recruitmentID, seniorCharacterOwnerHash) VALUES ('$helperID', '$seniorCharacterOwnerHash')";
            $stmt = $conn->exec($query);
            if ($stmt == true) {
                //search for escalationID
                $query = "SELECT ID FROM recruitmentEscalations WHERE recruitmentID = '$helperID' and seniorCharacterOwnerHash = '$seniorCharacterOwnerHash'";
                $stmt = $conn->query($query);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // update recruitment
                    $ID = $row["ID"];
                    $query = "UPDATE recruitment SET escalationID='$ID' WHERE ID = '$helperID'";
                    $stmt = $conn->exec($query);
                    if ($stmt == true) {
                        echo "Succesfully claimed";
                    } else {
                        echo "something went wrong while claiming";
                    }
                }
            } else {
                echo "something terribly wrong.";
            }
        } elseif ($helper[0]['yn'] == 1) {
            // show info senior recruiter.
            $name = $this->nameSearcher($seniorCharacterOwnerHash, $conn);
            if ($name == true) {
                echo "<b>" . $name["username"] . "</b>" . " claimed the escalation of this application.";
            }
        } else {
            echo 'you broke it.';
        }
    }

    //________________PUBLIC FUNCTION HOUSE________________________________________________________________________________\\

    //retrieve escalations by user or all

    private function insertHelper($recruiterID, $CharacterOwnerHashApplyer, $conn)
    {
        //search the ID in recruiter so we know what to update.
        $query = "SELECT * FROM recruitment WHERE recruiterID = '$recruiterID' AND CharacterOwnerHashApplyer = '$CharacterOwnerHashApplyer'";
        $stmt = $conn->query($query);
        while ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            if ($row[0]["escalationID"] == 0) {
                $row[0]["yn"] = "0";
            } elseif ($row["escalationID"] > 0) {
                $row[0]["yn"] = "1";
            } else {
                $row[0]["yn"] = "1";
            }
            return $row;
        }
    }

    //insert and update escalations

    private function nameSearcher($seniorCharacterOwnerHash, $conn)
    {
        $query = "SELECT username FROM users WHERE characterOwnerHash = '$seniorCharacterOwnerHash'";
        $stmt = $conn->query($query);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row;
        }
    }

}

//class ESI
//{
//    private $Client_BasicTEST;                                   //| The Codes for Testing the login
//    private $Client_BasicOauth;                                  //| The Codes for logging in
//    private $Client_Basic;                                       //| The Codes
//    private $RefreshToken;                                       //| Refresh tokens
//    private $AccessToken;                                        //| AccessToken - always use Tokenchecker
//    Private $CharID;                                             //| Without a database this cashes the CharID for now.
//    public function __construct()
//    {
//        include 'Config.php';
//        $this->Client_Basic = $Client_Basic;
//        $this->Client_BasicOauth = $Client_BasicOauth;
//        $this->Client_BasicTEST = $Client_BasicTEST;
//    }                           //| Loading in the Logincodes
////________________PRIVATE FUNCTION HOUSE_______________________________________________________________________________\\
//                                                                 //|
//    private function rToken($AccessCode,$Login)
//    {
//        if(empty($Login)){$temp=$this->Client_Basic;} else{$temp=$this->Client_BasicTEST;}
//        $ch = curl_init();
//
//        curl_setopt($ch, CURLOPT_URL, "https://login.eveonline.com/oauth/token");
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"grant_type\":\"authorization_code\", \"code\":\"$AccessCode\"}");
//        curl_setopt($ch, CURLOPT_POST, 1);
//
//        $headers = array();
//        $headers[] = "Content-Type: application/json";
//        $headers[] = ("Authorization: Basic " . $temp );
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//
//        $result = curl_exec($ch);
//        //echo $result;
//
//
//        if (curl_errno($ch)) {
//            echo 'Error:' . curl_error($ch);
//        }
//        curl_close($ch);
//        return $result;
//    }             //| if you feed it a AccessCode in it shits out a fresh Access Token
//                                                                 //|
//    private function Tokenchecker()
//    {
//        $string = $this->refreshToken();
//        $array = json_decode($string, true);
//        $this->RefreshToken = $array[refresh_token];
//        $this->AccessToken = $array[access_token];
//        return $array[access_token];
//    }                         //| Always returns a working Access_Token
//
//
//
//
//    private function TokencheckerZ($refreshtoken)
//    {
//        $string = $this->refreshTokenZ($refreshtoken);
//        $array = json_decode($string, true);
//        $this->RefreshToken = $array[refresh_token];
//        $this->AccessToken = $array[access_token];
//        return $array[access_token];
//    }
//    //|
//    private function refreshTokenZ($refreshtoken)
//    {
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, "https://login.eveonline.com/oauth/token");                                           //Host Site
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);                                                                        //
//        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"grant_type\":\"refresh_token\", \"refresh_token\":\"$refreshtoken\"}");         //Making the post
//        curl_setopt($ch, CURLOPT_POST, 1);                                                                                  //
//
//        $headers = array();                                                                                                                          //
//        $headers[] = "Content-Type: application/json";                                                                                               //
//        $headers[] = ("Authorization: Basic " . $this->Client_Basic);    //
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);                                                                                       //
//
//        $result = curl_exec($ch);
//        $temp = json_decode($result, true);
//        return $result;
//
//        if (curl_errno($ch)) {
//            echo 'Error:' . curl_error($ch);
//        }
//        curl_close($ch);
//    }
//
//
//
//
//    private function refreshToken()
//    {
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, "https://login.eveonline.com/oauth/token");                                           //Host Site
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);                                                                        //
//        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"grant_type\":\"refresh_token\", \"refresh_token\":\"$this->RefreshToken\"}");         //Making the post
//        curl_setopt($ch, CURLOPT_POST, 1);                                                                                  //
//
//        $headers = array();                                                                                                                          //
//        $headers[] = "Content-Type: application/json";                                                                                               //
//        $headers[] = ("Authorization: Basic " . $this->Client_Basic);    //
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);                                                                                       //
//
//        $result = curl_exec($ch);
//        $temp = json_decode($result, true);
//        return $result;
//
//        if (curl_errno($ch)) {
//            echo 'Error:' . curl_error($ch);
//        }
//        curl_close($ch);
//    }                         //| internal class gibbirish that isn't useful at all but make things work
//                                                                 //|
//    public function charID($accessToken)
//    {
//        $ch = curl_init();
//
//        curl_setopt($ch, CURLOPT_URL, "https://esi.tech.ccp.is/verify/");
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
//
//        $headers = array();
//        $headers[] = "Authorization: Bearer $accessToken";
//        $headers[] = "Host: esi.tech.ccp.is";
//        $headers[] = "Content-Type: application/json";
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//
//        $result = curl_exec($ch);
//        $array = json_decode($result, true);
//        $this->CharID = $array[CharacterID];
//        return $array;
//        if (curl_errno($ch)) {
//            echo 'Error:' . curl_error($ch);
//        }
//        curl_close($ch);
//    }                   //| For Registering and logging in (Verify'er)
//                                                                 //|
//    private function pullrequest($pull, $pulltype, $char)
//    {
//        $this->Tokenchecker();
//        if (empty($char)) {
//            $char = $this->CharID;
//        }           //automatically makes it $char      Char ID if left empty
//        if (empty($pulltype)) {
//            $pulltype = "characters";
//        }       //automatically makes it $Pulltype  Characters if left empty
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, "https://esi.evetech.net/latest/$pulltype/$char/$pull/?datasource=tranquility&token=$this->AccessToken");
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
//        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
//
//        $headers = array();
//        $headers[] = "Accept: application/json";
//        $headers[] = ("Authorization: Basic " . $this->Client_Basic);    //
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//
//        $result = curl_exec($ch);
//        return $result;
//        if (curl_errno($ch)) {
//            echo 'Error:' . curl_error($ch);
//        }
//        curl_close($ch);
//    }   //| Does what it says
//                                                                 //|
//    private function debug($refreshtoken,$scope)
//    {
//        $accessToken= $this->TokencheckerZ($refreshtoken);
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, "https://esi.evetech.net/latest$scope?datasource=tranquility&token=$accessToken)");
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
//        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
//
//        $headers = array();
//        $headers[] = "Accept: application/json";
//        $headers[] = ("Authorization: Basic " . $this->Client_Basic);    //
//        echo "<br>\"Authorization: Basic \" . $this->Client_Basic<br>";
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//
//        $result = curl_exec($ch);
//        return $result;
//        if (curl_errno($ch)) {
//            echo 'Error:' . curl_error($ch);
//        }
//        curl_close($ch);
//    }
////________________PUBLIC FUNCTION HOUSE________________________________________________________________________________\\
//    public function Login($AccessCode)
//    {
//        if (empty($AccessCode))
//            {
//                $temparray = $this->charID($this->Tokenchecker());
//                $array = array("CharacterOwnerHash"=>$temparray[CharacterOwnerHash],"CharacterID"=>$temparray[CharacterID],"refresh_token"=>$this->RefreshToken,"CharacterName"=>$temparray[CharacterName]);
//                return ($array);
//            }
//        else
//            {
//                $TokenArray = json_decode($this->rToken($AccessCode, true), true);
//                $temparray = $this->charID($TokenArray[access_token]);
//                return (array("CharacterID"=>$temparray[CharacterID],"CharacterOwnerHash"=>$temparray[CharacterOwnerHash]));
//            }
//
//    }                      //| For Oauth Account logging in
//                                                                 //|
//    public function Puller($pulltype,$char,$pull)
//    {
//        $this->Tokenchecker();
//        $stringarray = $this->pullrequest($pull, $pulltype, $char);
//        $decodedarray = json_decode($stringarray, true);
//        return $decodedarray;
//
//
//    }           //| The allpuller!
//                                                                 //|
//    public function Config($AccessCode,$Hash)
//    {
//        if(empty($AccessCode)and (empty($Hash)))
//        {
//         $DB = new DBconn();
//         $Tokenarray = $DB->getCharacterInfo($_SESSION['characterOwnerHash']);
//         $this->RefreshToken =$Tokenarray["refresh_token"];
//         $this->charID($this->Tokenchecker());
//        }
//        elseif (empty($AccessCode)and (!empty($Hash)))
//        {
//
//            $DB = new DBconn();
//            $Tokenarray = $DB->getCharacterInfo($Hash);
//            $scope = "/characters/$Tokenarray[characterID]/mail/";
//            echo $scope;
//            $this->RefreshToken =$Tokenarray["refresh_token"];
//            $this->charID($this->Tokenchecker());
//            echo "here<br>";
//            dprintr($this->debug($Tokenarray["refresh_token"],$scope));
//            echo "here<br>";
//
//        }
//        else
//        {
//         $Tokenarray = json_decode($this->rToken($AccessCode), true);
//         $this->RefreshToken = $Tokenarray["refresh_token"];
//         $this->AccessToken = $Tokenarray["access_token"];
//         $this->Tokenchecker();
//         $this->charID($this->Tokenchecker());
//        }
//    }                     //| Register the first steps
//                                                                 //|
//    public function EvidencdeConfig($Refresh_token, $CharacterID)
//    {
//        $this->RefreshToken = $Refresh_token;
//        $this->CharID = $CharacterID;
//        $this->AccessToken=$this->Tokenchecker();
//    }
//
//    public function EchoAll()
//    {
//        echo $this->RefreshToken;
//        echo $this->CharID;
//
//    }
////_____________________________________________________________________________________________________________________\\
//}