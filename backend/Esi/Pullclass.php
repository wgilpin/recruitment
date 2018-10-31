<?php
class pullclass
{
    private $Obj;

    public function __construct($scope)
    {
        include_once $_SERVER['DOCUMENT_ROOT']."/backend/Include.php";
        switch ($scope) {
            /* Old
            case "titles":
                $value = $this->Puller("characters", '', "titles");
                break;
            case "blueprints":
                if (!empty($_POST['number'])) {

                    $value = $this->Puller("characters", '', "blueprints", $_POST['number']);
                    $_POST['number'] = '';
                } else {
                    $value = $this->Puller("characters", '', "blueprints");
                    $keyarray = array('type_id');
                    $value = $this->CharCorpAllyconverter($keyarray, $value);
                }
                break;
            case "pi":
                $value = $this->Puller("characters", '', "planets");
                break;
            */// Old
            //_ON_THE_PULL_PAGE__\\
            case "wallet":
                _include ("Wallet");
                $this->Obj = new Wallet();
                break;                //| Input: refresh_token              |Output:      Array

            case "mail":
                _include ("Mail");
                $this->Obj = new Mail();
                break;                  //| Input: refresh_token              |Output:      Array

            case "skill":
                _include ("Skills");
                $this->Obj = new Skills();
                break;                 //| Input: refresh_token              |Output:      Array

            case "bookmarks":
                _include ("Bookmarks");
                $this->Obj = new Bookmarks();
                break;             //| Input: refresh_token              |Output:      Array

            case "contacts":
                _include ("Contacts");
                $this->Obj = new Contacts();
                break;              //| Input: refresh_token              |Output:      Array

            case "market":
                _include ("Market");
                $this->Obj = new Market();
                break;                //| Input: refresh_token              |Output:      Array

            case "contract":
                _include ("Contract");
                $this->Obj = new Contract();
                break;              //| Input: refresh_token              |Output:      Array

            case "linkID":
                _include ("LinkID");
                $this->Obj = new LinkID();
                break;                //| Input: refresh_token              |Output:      Array

            case "assets":
                _include ("Assets");
                $this->Obj = new Assets();
                break;

            case "login":
                _include ("Login");
                $this->Obj = new Login();
                break;

            //__NOT_ON_THE_PULL_PAGE__\\

            case "debug":
                _include ("Debug");
                $this->Obj = new Debug();
                break;
            case "Oauth":
                _include ("Oauth");
                $this->Obj = new EveOauth();
                break;               //| Input: accesscode                |Output:      Array
            case "Portrait":
                _include ("Portrait");
                $this->Obj = new Portrait();
                break;          //| Input: characterID              |Output:      Array
            default:
                echo "Wrong Scope";
                break;
        }
    }

    Public function _Echo($var1, $var2, $var3, $var4)
    {
        echo $this->Obj->Run($var1, $var2, $var3, $var4);
    }

    Public function _Return($var1, $var2, $var3, $var4)
    {
        return $this->Obj->Run($var1, $var2, $var3, $var4);
    }

}