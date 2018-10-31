<?php
include_once $_SERVER['DOCUMENT_ROOT']."/backend/Include.php";
_include("ESI");
class Market extends ESI{

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

private function buyORsell($array){
foreach($array as $value)
{
if($value['is_buy_order']){
$return['buy'][$value['order_id']] = $value;
}else{
$return['sell'][$value['order_id']] = $value;
}
}
return $return;
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

private function set($array,$keyarray){
$data = $this->_Foreach($array,array(),$this->Pull_Func,$keyarray);
$data = $this->Datacall->data($data);
$done = $data[1];
if($data[2]){
if($done){
$done += $this->Cachepull($data[2]);
}else{
$done = $this->Cachepull($data[2]);
}
}
$done = $this->Standing($done);
return $this->buyORsell($this->write($array,$keyarray,$done));
}

public function run($refresh_token){
$keyarray = array("location_id","type_id");
$this->AccessToken = $this->AccesTokenDispencer($refresh_token);
$CharID = $this->verify($this->AccessToken)["CharacterID"];
$active = $this->DATAPULLAUTH($this->AccessToken, "characters/$CharID/orders");
$history = $this->DATAPULLAUTH($this->AccessToken, "characters/$CharID/orders/history");
return array("active"=>$this->set($active,$keyarray),"history"=>$this->set($history,$keyarray));
}

}