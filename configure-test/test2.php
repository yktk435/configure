<?php


$str = 'service timestamps debug datetime msec localtime show-timezone';
print "---------------------------------\n";
print $str . PHP_EOL;
print "---------------------------------\n";
print_r(loop($str));
function loop($val)
{
    $newArr = [];
    if (gettype($val) == 'string') { //文字列なら
        print "---------------------------------\n";
        print "文字列なら\n";
        print "---------------------------------\n";
        print $val.PHP_EOL;
                
        if (strpos($val, ' ') !== false) { //空白があるなら
            return loop(explode(' ', $val)); //配列にしてもう一回関数へ
        } else { //空白がない文字列なら終わり
            return $val;
        }
    } else { //配列なら
        print "---------------------------------\n";
        print "配列なら\n";
        print "---------------------------------\n";
        var_dump($val);
        
        if (count($val) == 2) { //配列要素が2つしか無いなら
            $newArr[$val[0]] = $val[1];
            return $newArr;
        }


        $tempKey = $val[0];
        $tempVal = $val;

        unset($val[0]);
        $tempValues = array_values($tempVal); //インデックスを詰める
        $temp[$tempKey] = implode(' ', $tempValues);
        
        print "---------------------------------\n";
        print "完成したもの\n";
        print "---------------------------------\n";
        print_r($temp);
        print "---------------------------------\n";
        print "完成したものここまで\n";
        print "---------------------------------\n";
        if (count($tempValues) > 1) { //まだ分割できるならもう一回ループ
        var_dump($newArr[$tempKey]);
            // $newArr = loop(implode(' ', $newArr[$tempKey]));
            return  loop($temp[$tempKey]);    
        }
        $newArr = $temp;
        return $newArr;
    }
}
