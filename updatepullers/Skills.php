<?php

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
        $change = array("164" => "Charisma", "165"=>"Intelligence", "166"=>"Memory", "167"=>"Perception", "168"=>"Willpower");
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
    ?>