<?php

//this is a security check
include '../Functions.php';

class ap extends DBconn
{
    public function output()
    {

    }

    public function input($var1, $var2, $var3)
    {

    }
}

class apMail extends DBconn
{
    public function output()
    {
        include '../Config.php';
        $query = "SELECT mailID FROM config WHERE allw_one = 1";
        $array = $this->Connect()->query($query)->fetch(PDO::FETCH_ASSOC);
        $array['URL'] = 'https://login.eveonline.com/oauth/authorize?response_type=code&redirect_uri=http://'.$Url.'/test2.php&client_id='.$Client_ID.'&scope='.$scopes.'';
        return $array;
    }

    public function input($var1, $var2, $var3)
    {
        if (is_numeric($var1)){
            $query = "UPDATE config SET mailID = '$var1', mailrefreshtoken = '$var2' WHERE allw_one = '1'";
            return $this->Connect()->exec($query);
        }
    }
}

class apQuestion extends questions
{
    public function output()
    {
        $query = "SELECT * FROM questions WHERE ID=(SELECT max(ID) FROM questions)";
        $stmt = $this->Connect()->query($query);
        $temp = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($temp[0] as $key => $value){
            if ($value){
                $returnArray[$key] = $value;
            }
        }
        unset($returnArray["ID"]);
        return $returnArray;
    }

    public function input($var1, $var2)
    {
        if (is_numeric($var1)){
            $var1 = "question$var1";
        }
        return $this->questionInserter($var1, $var2);
    }
}

class apRanks extends DBconn
{
    public function output()
    {
        $query = "SELECT username, user_level FROM users WHERE user_level > 2";
        $stmt = $this->Connect()->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function input($var1, $var2)
    {
        return $this->changeRanks($var1, $var2);
    }
}

class apCacheTime extends DBconn
{
    public function output()
    {
        include '../Config.php';
        $data = array("Char" => $cnf[cacheTimeChar], "Corp" => $cnf[cacheTimeCorp], "Ally" => $cnf[cacheTimeAlly], "Struct" => $cnf[cacheTimeStruct]);
        return $data;
    }

    public function input($var1, $var2)
    {
        $query = "UPDATE config SET cacheTime$var1 = $var2 WHERE allw_one = '1'";
        $stmt = $this->Connect()->exec($query);
        return $stmt;
    }
}

class adminPanel
{
    private $obj;

    public function __construct($scope)
    {
        switch ($scope) {
            case "cachetime":
                $this->obj = new apCacheTime();
                break;
            case "ranks":
                $this->obj = new apRanks();
                break;
            case "question":
                $this->obj = new apQuestion();
                break;
            case "apMail":
                $this->obj = new apMail();
                break;
        }
    }

    public function output()
    {
        return $this->obj->output();
    }

    public function input($var1, $var2, $var3)
    {
        return $this->obj->input($var1, $var2, $var3);
    }
}
if ($_GET) {
    $temp = new adminPanel($_GET["scope"]);
    if ($_GET["var1"]){
        echo json_encode($temp->input($_GET["var1"], $_GET["var2"], $_GET["var3"]));
    }else{
        echo json_encode($temp->output());
    }
}