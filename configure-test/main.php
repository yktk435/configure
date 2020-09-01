<?php

$file = fopen('conf.cfg', "r");
$array = [];
$key = '';

if ($file) {
    while ($line = fgets($file)) {

        if (preg_match('/^[^\s!]/', rtrim($line), $match) && !strpos($line,'#') !== false) { //文字の先頭に空白がないなら
            $key = trim($line);
            $array[$key][] = '';
        }
        if (preg_match('/^\s/', rtrim($line), $match)) { //文字の先頭に空白があるなら
            $array[$key][] = trim($line);
        }
    }
}
print_r($array);

foreach ($array as $key => $value) {
    print $key.PHP_EOL;
}
function toArr(string $str){
    $arr=explode(' ',$str);
    if(count($arr)==1)return $str;
    $seri='a:1:{';
    for ($i=0; $i < count($arr); $i++) { 
        if($i==count($arr)-2){
            $seri.='s:'.strlen($arr[$i]).':"'.$arr[$i].'";s:'.strlen($arr[$i+1]).':"'.$arr[$i+1].'";';
        break;
            print "\n\n\n";
            print $i."\n";
            print "\n\n\n";
        }else{
            $seri.='s:'.strlen($arr[$i]).':"'.$arr[$i].'";a:1:{';
        }
        
    }
    $seri.=str_repeat('}', count($arr)-1); 
    print_r(unserialize($seri));
}
