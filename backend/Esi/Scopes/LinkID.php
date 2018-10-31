<?php

include_once $_SERVER['DOCUMENT_ROOT']."/backend/Include.php";
_include("ESI");
class LinkID extends ESI{
    public function run($refresh_token, $encodedstring){

        $IDarray= json_decode(urldecode(urldecode($encodedstring)),true);


        if(!$IDarray){return false;}

        $this->AccessToken = $this->AccesTokenDispencer($refresh_token);
        foreach ($IDarray as $value){
            $idArray[$value['param2']] = $value['param2'];
        }

        $return = $this->Cachepull($idArray,true);

        foreach ($return['extra'] as $key=>$value){
            switch ($value['category']){
                case "solar_system":
                    $solar[$value['id']]=$value['id'];
                    break;
                case "station":
                case "inventory_type":
                    $data[$value['id']]=$value['id'];
                    break;
                case "":
                    break;
            }
        }
        unset($return['extra']);
        $final = array();
        if($data){$final += $this->Datacall->data($data)[1];}
        if($solar){$final += $this->Cachecall->solarsystemSearcher($solar);}
        if($final and $return){$return += $final;}
        elseif($final and !$return){$return = $final;}
        return $return;
    }

}