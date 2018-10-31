<?php
include_once $_SERVER['DOCUMENT_ROOT']."/backend/Include.php";
_include("ESI");
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

    private function Keyplacement($array)
    {
        $return = array();
        foreach ($array as $key => $value) {
            $return[$value['item_id']] = $value;
        }
        return $return;
    }
    private function Standing($array)
    {
        foreach ($array as $key => $value) {
            if ($this->Standinglist[$key]) {
                $array[$key]["standing"] = $this->Standinglist[$key];
            }
        }
        return $array;
    }

    private function MapDrawer($Stations, $Items)
    {
        foreach ($Items as $key => $value) {
            if ($Stations[$value["location_id"]]) {
                $map[$key] = array("Container" =>true,"Insideof" =>$value["location_id"]);
            }elseif($Items[$value["location_id"]]){
                $map[$key] = array("Container" =>false,"Insideof" =>$value["location_id"]);
            }
            elseif ($Stations['error'][$value["location_id"]]){
                $map['error'][$key] = array("Container" =>false,"Insideof" =>$value["location_id"]);
            }
        }
        return $map;
    }
    private function Errorcheck($Error){
        foreach ($Error as $key => $value){
            if($Error[$value['Insideof']]){
                $Error[$key]['Container'] = false;
            }
            else{
                $Error[$key]['Container'] = true;
            }
        }
        return $Error;
    }


//    private function Pathfinder($map, $key){
//        $x = true;
//        $place = $map;
//        while ($x){
//            if($place[$key]['Container']){
//                return $return;
//            }
//            else{
//                $return[] = $key;
//                $key = $place[$key]['Insideof'];
//            }
//        }
//    }
    private function Mapbuilder($Map, $Items){
        foreach ($Map as $key=>$Item){
            if ($Item['Container']){
                $return[$key] = $Items[$key];
            }
            else{
                $Path = $this->Pathfinder($Map,$key);
                echo"<br> $key";
                $this->Dprintr($Path);
            }
        }
    }

    private function NameArrayExtracter($array)
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
    private function NameArrayInserter($array, $names)
    {
        foreach ($names as $key => $name) {
            if ($name) {
                $array[$key]['name'] = $name;
            }

        }
        return $array;
    }
    private function TypeSetter($array, $type)
    {
        foreach ($array as $key => $value) {
            $array[$key]['type_id'] = $type[$array[$key]['type_id']];
        }
        return $array;
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
        $return = $this->Keyplacement($array); //Sets the key to Item_ID


        //_getting_the_names_of_the_Items_\\
        $ItemArray = $this->_Foreach($array, $returnarray, $this->Pull_Func, $Itemarray);
        $itemString = $this->ArraytoString($ItemArray);
        $return = $this->NameArrayInserter($return, $this->NameArrayExtracter($this->DATAPOST("characters/$CharID/assets/names", "[$itemString]", $this->AccessToken)));

        //_Separating_the_Types_\\
        $typeArray = $this->_Foreach($array, $returnarray, $this->Pull_Func, $TypeArray);
        $Types = $this->Datacall->data($typeArray);
        $Types = $this->Standing($Types[1]);
        $return = $this->TypeSetter($return, $Types);
//        $this->Dprintr($return);

        //_Building_the_Structure/Station_Arrays_\\
        foreach ($return as $value) {
//            echo"<br>";
//            echo "$value[location_flag]     || This is ||   $value[location_type]";
            if ($value[location_flag] == "Hangar")
                $hangararray[$value[location_id]] = $value[location_id];
        }
        $temp = $this->Datacall->data($hangararray);
        $hangar = $temp[1];
        $stationsID = $temp[2];
        unset($temp);
        $Structures = $this->Cachepull($stationsID);
        if ($hangar && $Structures) {
            $hangar += $Structures;
        } elseif (!$hangar && $Structures) {
            $hangar = $Structures;
        }                                                            //Set Hangar!

        //_Building_The_Map_Array_\\
        $Map = $this->MapDrawer($hangar, $return);

        if($Map["error"]) {
            $error = $Map["error"];
            unset($Map['error']);
            $error = $this->Errorcheck($error);
            $this->Dprintr($error);
        }
        //_Road_Builder_\\
        $this->Mapbuilder($error,$return);

    }

}