<?php

use Symfony\Component\Translation\Extractor\PhpExtractor;

$str = 'service timestamps debug datetime msec localtime show-timezone';
print "---------------------------------\n";
print $str.PHP_EOL;
print "---------------------------------\n";
print_r(loop($str));
function loop($val)
{
    $newArr = [];
    if (gettype($val) == 'string') { //文字列なら
        if (strpos($val, ' ') !== false) { //空白があるなら
            return loop(explode(' ', $val)); //配列にしてもう一回関数へ
        } else { //空白がない文字列なら終わり
            return $val;
        }
    } else { //配列なら
        if (count($val) == 2) { //配列要素が2つしか無いなら
            $newArr[$val[0]] = $val[1];
            return $newArr;
        }

        
            $tempKey = $val[0];
            $tempVal = $val;
        
            unset($val[0]);
            $tempValues = array_values($tempVal); //インデックスを詰める
            $temp[$tempKey] = implode(' ', $tempValues);
            $newArr = $temp;
            print_r($newArr);
            // if (count($tempValues) > 1) { //まだ分割できるならもう一回ループ
            //     print $newArr[$tempKey];
                
            //     $newArr[$tempKey]=loop($newArr[$tempKey]);
            //     exit;
            // }
            
            return $newArr;
        
    }
}
