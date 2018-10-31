<?php
function namecheck($string)
{
    $array = explode(".", $string);
    return $array;
}

function Mapbuilder($currentposition)
{
    $files = scandir($currentposition);
    unset($files[0]);
    unset($files[1]);
    $counter = 0;
    foreach ($files as $value) {
        $return[$counter] = namecheck($value);
        if (!$return[$counter][1]) {
            $return[$counter][$return[$counter][0]] = Mapbuilder($currentposition . "/" . $return[$counter][0]);
            unset($return[$counter][0]);
        }
        $counter++;
    }
    return $return;
}

function folderarray($Maparray,$return,$base){
    $return = $return ?: array();
    $base = $base ?: "";
    foreach($Maparray as $key=>$file){
        reset($file);
        $fistkey = key($file);
        if (is_array($file[$fistkey])) {
            $return = folderarray($file[$fistkey],$return,"$base" . "/$fistkey");
        }else{
            if($file[1] == "php") {
                $return["$base/" . "$file[0].$file[1]"] = $file[0];
            }
        }
    }
    return $return;
}

$currentposition = getcwd();
$files = Mapbuilder($currentposition);
echo "<pre>";
print_r($files);
print_r(folderarray($files));

echo "<br>" . Mapreader(folderarray($files));

function namegiver($string, $name, $counter)
{
    $counter = $counter ?: 1;
    if (strpos($string, "$name ")!== False) {
        echo "1";
        if (strpos($string, ($name."_".$counter." "))!== False) {
            $counter++;
            return namegiver($string,$name,$counter);
        }
        else {
            return ($name."_".$counter);
        }
    }
    else return $name;
}

function Mapreader($maparray, $base, $string)
{
    $string = $string ?: "";
    $base = $base ?: "";
    foreach ($maparray as $path=>$file) {
            $string .= namegiver($string,$file)." | ".$path.PHP_EOL;
        }
    return $string;
}


