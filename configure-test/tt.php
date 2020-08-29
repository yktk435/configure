<?php
$arr = [1, 2,3,4,5];
$newArr=[];
$newArr=createArr($arr,$newArr);

function createArr($arr,$newArr){
    $arr=[1=>[2=>[3=>[4=>5]]]];
    $arr=[1=>[2=>[[6=>[4=>5]]]]];
    print_r($arr);
}