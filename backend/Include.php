<?php

/*
USE THIS CLASS TO INCLUDE!
Include "_PATH_/Include.php

Call _include(Param1*)
Param1 = String(file name) OR Array(String(file name),String(file name))

File names can be found in include.txt
*/

class including
{
    private $path; //

    public function __construct()
    {
        $this->path = "/home/public/sites/ascee.droeftoeters.com/backend/include.txt";
    }

    //_INCLUDE_\\
    public function run($param)
    {
        if (is_array($param)) {
            foreach ($param as $value) {
                $this->nameORpath($value);
            }
        } elseif (is_string($param)) {
            $this->nameORpath($param);
        }
    }

    private function nameORpath($param)
    {

        if (strpos($param, "/") and strpos($param, ".")) {
            $this->_path($param);
        } else {
            $this->_include($param);
        }
    }

    private function explode($string, $folder)
    {
        if ($folder) {
            return array_slice(explode("/", $string), 1, -1);
        }
        return array_slice(explode("/", $string), 1);
    }

    private function buildpath($patharray, $count)
    {
        $string = "";
        for ($count; $count > 0; $count--) {
            $string .= "/..";
        }
        foreach ($patharray as $part) {
            $string .= "/$part";
        }
        return substr($string, 1);
    }

    private function CorrectPath($path)
    {
        $thispathArray = $this->explode($this->path, 1);
        $getcwdArray = $this->explode(getcwd());
        $pathArray = $this->explode($path);
        $difference = array_diff($getcwdArray, $thispathArray);
        $correctpath = $this->buildpath($pathArray, count($difference));
        return $correctpath;
    }

    private function _include($include)
    {
        $array = $this->Arrayifier($this->import());
        $array = array_flip($array);
        if ($array[$include]) {
            include_once $this->CorrectPath($array[$include]);
        } else {
            return true;
        }
    }

    private function _path($include)
    {
        include $_SERVER['DOCUMENT_ROOT'].$include;
    }

    //_FILE_READER_WRITER_\\
    private function import()
    {
        $myfile = fopen("$this->path", "r") or die("Unable to open file!");
        $TextFile = fread($myfile, filesize("$this->path"));
        fclose($myfile);
        return $TextFile;
    }

    private function Arrayifier($string)
    {
        $raw = explode(PHP_EOL, $string);
        foreach ($raw as $item) {
            $patharray = explode("|", $item);
            $return[str_replace(array("\n", "\r"), '', substr($patharray[1], 1))] = str_replace(array("\n", "\r"), '', substr($patharray[0], 0, -1));
        }
        if (key(end($return)) === null) {
            array_pop($return);
        }
        return $return;
    }

    private function export($string)
    {
        if (!is_string($string)) {
            return false;
        }
        $myfile = fopen("include.txt", "w") or die("Unable to open file!");
        fwrite($myfile, $string);
        fclose($myfile);
    }

    //_UPDATE_\\
    public function update()
    {
        if(getcwd() == str_replace("/include.txt","",$this->path)){
        $old = $this->Arrayifier($this->import());
        $new = $this->Arrayflatner($this->DirMapper());
        foreach ($new as $path => $name) {
            if ($old[$path]) {
                $new[$path] = $old[$path];
            }
        }
        $string = $this->Stringifier($new);
        echo "Updating <pre>".$string;
        $this->export($string);
        }
    }

    private function DirMapper($currentposition)
    {
        $currentposition = $currentposition ?: getcwd();
        $files = scandir($currentposition);
        unset($files[0]);
        unset($files[1]);
        $counter = 0;
        foreach ($files as $value) {
            $return[$counter] = $this->stringexploder($value);
            if (!$return[$counter][1]) {
                $return[$counter][$return[$counter][0]] = $this->DirMapper($currentposition . "/" . $return[$counter][0]);
                unset($return[$counter][0]);
            }
            $counter++;
        }
        return $return;
    }

    private function stringexploder($string)
    {
        $array = explode(".", $string);
        return $array;
    }

    private function Arrayflatner($Maparray, $return, $base)
    {
        $return = $return ?: array();
        $base = $base ?: "";
        foreach ($Maparray as $key => $file) {
            reset($file);
            $fistkey = key($file);
            if (is_array($file[$fistkey])) {
                $return = $this->Arrayflatner($file[$fistkey], $return, "$base" . "/$fistkey");
            } else {
                if ($file[1] == "php") {
                    $return["$base/" . "$file[0].$file[1]"] = $file[0];
                }
            }
        }
        return $return;
    }

    private function Stringifier($maparray, $base, $string)
    {
        $string = $string ?: "";
        $base = $base ?: "";
        foreach ($maparray as $path => $file) {
            $string .= $this->namegiver($string, $file) . " | " . $path . PHP_EOL;
        }
        return $string;
    }

    private function namegiver($string, $name, $counter)
    {
        $counter = $counter ?: 1;
        if (strpos($string, "$name ") !== False) {
            if (strpos($string, ($name . "_" . $counter . " ")) !== False) {
                $counter++;
                return $this->namegiver($string, $name, $counter);
            } else {
                return ($name . "_" . $counter);
            }
        } else return $name;
    }

}

function _include($param)
{
    $obj = new including();
    $obj->run($param);
    unset($including);
}
if (isset($classupdate)) {
    $including = new including;
    $including->update();
}