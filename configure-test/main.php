<?php

$file = fopen('conf.cfg', "r");
$array = [];
$key = '';
/* URL部分を抽出して配列にする */
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
$s=[];
$s['p']['p']['p']['p']=1;
print_r($s);


$arr=[];
foreach ($array as $key => $value) {
    print $key.PHP_EOL;
    $exp = explode(' ', $key);
    $arr[$exp[count($exp) - 2]] = end($exp);
    for ($i = count($exp)-3; $i > 0; $i--) {
        $temp=[];
        $temp[$exp[$i]]= $arr;
        $arr=$temp;    
    }
}
print_r($arr);