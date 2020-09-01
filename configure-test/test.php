<?php

$str = 'service timestamps debug datetime msec localtime show-timezone';
$arr = explode(' ', $str);
$newArr = [];
$newArr = createArr($arr, $newArr);
for ($i=0; $i < 7; $i++) { 
    

print "\n-------------------------\n";
print "newArr\n";
print "\n-------------------------\n";


print_r($newArr);
foreach ($newArr as $key => &$value) {
    $value=eachLoop($key,$value);
    // $value＝1;
}
print "\n-------------------------\n";
print "newArr\n";
print "\n-------------------------\n";
print_r($newArr);
}

$str = 'service timestamps log datetime msec localtime show-timezone';
$arr = explode(' ', $str);
$newArr2 = [];
$newArr2 = createArr($arr, $newArr2);
for ($i=0; $i < 7; $i++) { 
    

print "\n-------------------------\n";
print "newArr\n";
print "\n-------------------------\n";


print_r($newArr2);
foreach ($newArr2 as $key => &$value) {
    $value=eachLoop($key,$value);
    // $value＝1;
}
print "\n-------------------------\n";
print "newArr2\n";
print "\n-------------------------\n";
print_r($newArr2);
}

print_r($newArr);
print_r($newArr2);
print_r(array_merge($newArr,$newArr2));

function createArr(array $arr)
{
    $newArr=[];
    if (count($arr) == 2) { //配列要素が2つしか無いなら
        $newArr[$arr[0]] = $arr[1];
        return $newArr;
    }
    $tempExp = $arr[0];
    unset($arr[0]);
    $arr = array_values($arr); //インデックスを詰める
    $temp[$tempExp] = implode(' ', $arr);
    $newArr = $temp;
    return $newArr;
}
function eachLoop($key,$value){
    if(gettype($value)=='string' && strpos($value,' ')!==false){//空白が含まれているなら
        print "\n-------------------------\n";
        print "通常\n";
        print "\n-------------------------\n";

        $temp=explode(' ',$value);
        $temp=createArr($temp);
        print_r($temp);
        print_r($value);
        
        $value=$temp;
        return $value;
    }else{//配列なら深く巡ってみる
        print "\n-------------------------\n";
        print "めぐる\n";
        print "\n-------------------------\n";
        print "value\n";
        print_r($value);
        foreach ($value as $key2 => &$value2) {
            print "value2\n";
            print_r($value2);
            $value2=eachLoop($key2,$value2);
        }
        
    }
    return $value;
    
}
