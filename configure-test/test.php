<?php

$arr = [1, 2, 3, 4, 5];
$newArr=[];
// $newArr = [1 => [2 => [3 => [4 => 5]]]];
$newArr = createArr($arr, $newArr);

print_r($newArr);

function createArr(array $arr, array $newArr,int $int=0)
{
    for ($i = count($arr) - 1; $int!=0 ? $int:$i >= 0; $i--) {
        
        if (count($arr) == 2) { //配列要素が2つしか無いなら
            $newArr[$arr[0]] = $arr[1];
            break;
        }
        if (!array_key_exists($arr[0], $newArr)) {
            $temp = [];
            $temp[$arr[$i]] = $newArr;
            $newArr = $temp;
        } 
        // else { //すでに作成済み項目がるなら別処理
        //     $newArr= addVal($arr, $newArr, $i);
        // break;
        // }
        if ($i == count($arr) - 3) {
            $temp = [];
            $temp[$arr[$i]] = array($arr[count($arr) - 2] => end($arr));
            $newArr = $temp;
        }
    }
    return $newArr;
    
}

function addVal(array $arr, array $newArr, int $i)
{
    for ($i=0;$i<count($arr) ;$i++) {
        foreach ($newArr as $key => &$newVal) {
            if($key!=$arr[$i]){
                print $i.PHP_EOL;
                
                createArr($arr,$newVal,$i);
            break 2;
            }
        }
    }
    return $newArr;
}
