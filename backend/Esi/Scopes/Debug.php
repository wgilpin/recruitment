<?php
include_once $_SERVER['DOCUMENT_ROOT']."/backend/Include.php";
_include("ESI");
class Debug extends ESI
{
    public function Run($refresh, $array, $reason)
    {
        echo "test";
        return  $this->Cachepull($refresh, $array, $reason);
    }
}
