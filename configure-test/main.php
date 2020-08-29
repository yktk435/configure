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
