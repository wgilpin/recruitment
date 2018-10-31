<?php
include_once $_SERVER['DOCUMENT_ROOT']."/backend/Include.php";
_include("ESI");
class Bookmarks extends ESI
{
    private function standing($array)
    {
        foreach ($array as $key => $value) {
            if (array_key_exists($key, $this->Standinglist)) {
                $done[$key] = $value + array("standing" => $this->Standinglist[$key]["standing"]);
                if ($this->Standinglist[$key]["standing"] < 0) {
                    $done["Blacklist"][$key] = $value;
                }
            } else {
                $done[$key] = $value + array("standing" => 0);
            }
        }
        return $done;
    }

    public function run($refresh_token)
    {
        $this->AccessToken = $this->AccesTokenDispencer($refresh_token);
        $CharID = $this->verify($this->AccessToken)["CharacterID"];
        $folders = $this->DATAPULLAUTH($this->AccessToken, "characters/$CharID/bookmarks/folders");
        $bookmarks = $this->DATAPULLAUTH($this->AccessToken, "characters/$CharID/bookmarks");

        //_Type_Location_Creator_\\
        $Type_id = $this->standing($this->Datacall->data($this->_Foreach($bookmarks, array(), $this->Pull_Func, array('type_id')))[1]);
        $location_id = $this->standing($this->Cachecall->solarsystemSearcher($this->_Foreach($bookmarks, array(), $this->Pull_Func, array('location_id'))));
        $Creator_id = $this->standing($this->Cachepull($this->_Foreach($bookmarks, array(), $this->Pull_Func, array('creator_id')), true));
        foreach ($folders as $key => $value) {
            $folders[$value['folder_id']] = $value;
            unset($folders[$key]);
        }
        foreach ($bookmarks as $key => $value) {
            if ($value["creator_id"]) {
                $value["creator_id"] = array("name" => $Creator_id[$value["creator_id"]]['name'], "ID" => $Creator_id[$value["creator_id"]]['ID'], "Standing" => $Creator_id[$value["creator_id"]]['standing']);
            }                                     //_Filling_in_the_Creator_id_\\
            $value["location_id"] = $location_id[$value["location_id"]];        //_Filling_in_the_Locations_\\
            if ($value['item']) {
                $value['item'] += $Type_id[$value['item']['type_id']];
            }                                           //_Filling_in_the_Item_id's_\\
            if ($value['folder_id']) {
                $folders[$value['folder_id']]['name'] = $folders[$value['folder_id']]['name'];
                $folders[$value['folder_id']]['inside'][$value['bookmark_id']] = $value;
            } else {
                $folders[$value['bookmark_id']] = $value;
            }                           //_Placing_the_BM's_in_their_folder_\\
        }

        if ($Type_id["Blacklist"]) {
            $Blacklist = $Type_id["Blacklist"];
        }
        if ($location_id["Blacklist"]) {
            $Blacklist = $location_id["Blacklist"];
        }
        if ($Creator_id["Blacklist"]) {
            $Blacklist = $Creator_id["Blacklist"];
        }

        $returnarray["Blacklist"] = $Blacklist ?: array();
        $returnarray["info"] = $folders;
        $returnarray["list"] = $Creator_id;

        return $returnarray;
    }
}