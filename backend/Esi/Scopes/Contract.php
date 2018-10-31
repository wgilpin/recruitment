<?php
include_once $_SERVER['DOCUMENT_ROOT']."/backend/Include.php";
_include("ESI");
class Contract extends ESI{
//    private $CharID;

    private function Standing($array)
    {
        foreach ($array as $key => $value) {
            if ($this->Standinglist[$key]) {
                $array[$key]["standing"] = $this->Standinglist[$key];
            }
            else{
                $array[$key]["standing"] = "0";
            }
        }
        return $array;
    }

    private function write($array,$keyarray,$replace){

        foreach ($array as $key=>$value){
            if(is_array($value)){$array[$key]=$this->write($value,$keyarray,$replace);}
            else{
                if(in_array($key,$keyarray)){
                    $array[$key] = $replace[$array[$key]];
                }
            }
        }
        return $array;
    }

    private function shortwrite($array,$keyarray,$replace){
        foreach ($array as $key=>$value){
            foreach ($value as $key2=>$value2){
                if(in_array($key2,$keyarray)){
                    $array[$key][$key2] = array("standing"=>$replace[$array[$key][$key2]]['standing'],"id"=>$replace[$array[$key][$key2]]['ID'],"name"=>$replace[$array[$key][$key2]]['name']);
                }
            }
        }
        return $array;
    }

    private function Contract_ID($data){
        foreach ($data as $value){
            $items = $this->DATAPULLAUTH($this->AccessToken, "characters/$this->CharID/contracts/$value/items");
            foreach ($items as $value2) {
                $item[$value][$value2['type_id']] = $value2;
            }
        }
        $data = $this->_Foreach($item,array(),$this->Pull_Func,array("type_id"));
        $data = $this->write($item,array("type_id"), $this->Standing($this->Datacall->data($data)[1]));
        return $data;
    }

    public function run($refresh_token){
        $structures = array();
        $keyarray = array("contract_id");
        $this->AccessToken = $this->AccesTokenDispencer($refresh_token);
        $this->CharID = $this->verify($this->AccessToken)["CharacterID"];
        $contracts = $this->DATAPULLAUTH($this->AccessToken, "characters/$this->CharID/contracts");
        $data = $this->_Foreach($contracts,array(),$this->Pull_Func,$keyarray);
        $data = $this->Contract_ID($data);
        $contracts = $this->write($contracts,$keyarray,$data);
        $keyarray = array("start_location_id","end_location_id");
        $stations = $this->Datacall->data($this->_Foreach($contracts,array(),$this->Pull_Func,$keyarray));

        $structures += $stations[1];
        $structures += $this->Cachepull($stations[2]);




        $keyarray = array("issuer_id","acceptor_id","assignee_id","issuer_corporation_id");
        $characters = $this->Cachepull($this->_Foreach($contracts,array(),$this->Pull_Func,$keyarray),true);
        $temp = $this->write($contracts,array("start_location_id","end_location_id"), $structures);
        foreach ($temp as $key=>$value){
            if($value[end_location_id] == $value[start_location_id]){}
            unset($temp[$key][start_location_id]);
        }
        $keyarray = array("issuer_id","acceptor_id","assignee_id","issuer_corporation_id");
        $characters =$this->Standing($characters);
        $return['info'] = $this->shortwrite($temp,$keyarray,$characters);
        $return['list'] = $characters;
        return $return;
    }

}