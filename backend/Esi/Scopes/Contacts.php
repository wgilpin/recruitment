<?php
include_once $_SERVER['DOCUMENT_ROOT']."/backend/Include.php";
_include("ESI");
class Contacts extends ESI
{
    private function standing($array)
    {
        foreach ($array as $key => $value) {

            if (array_key_exists($key, $this->Standinglist)) {
                $done[$key] = $value + array("Personal_standing" => $array[$key]["standing"], "Blacklist_standing" => $this->Standinglist[$key]["standing"]);
                unset($done[$key]['standing']);
                if ($this->Standinglist[$key]["standing"] < 0) {
                    $done["Blacklist"][$key] = $value;
                }
            } else {
                $done[$key] = $value + array("Personal_standing" => $array[$key]["standing"],"Blacklist_standing" => 0);
                unset($done[$key]['standing']);
            }
        }
        return $done;
    }

    public function run($refresh_token)
    {
        $this->AccessToken = $this->AccesTokenDispencer($refresh_token);
        $CharID = $this->verify($this->AccessToken)["CharacterID"];
        $label = $this->DATAPULLAUTH($this->AccessToken, "characters/$CharID/contacts/labels");
        foreach ($label as $key=>$labelid){
            $label[$labelid["label_id"]] = $labelid;
            unset($label[$key]);
        }
        $contacts = $this->DATAPULLAUTH($this->AccessToken, "characters/$CharID/contacts");
        foreach ($contacts as $key => $value){
            if($value['label_ids']){
                foreach ($value['label_ids'] as $label_key=>$label_id){
                    $contacts[$key]['label_ids'][$label_key] = $label[$label_id];
                }
            }
            $contacts[$value['contact_id']] = $contacts[$key];
            unset($contacts[$key]);
        }
        $contacts = $this->standing($contacts);


        $id=$this->Cachepull($this->_Foreach($contacts,array(),$this->Pull_Func,array("contact_id")),true);
        foreach ($contacts as $key=>$value){
            $contacts[$key]["contact_id"] = $id[$contacts[$key]["contact_id"]];
        }
        return $contacts;
    }
}