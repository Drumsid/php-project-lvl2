<?php

// $json1 = file_get_contents('after.json');

// // echo $json1;

// $json2 = file_get_contents('before.json');

// echo $json2;

function afterFistBeforLast($str, $del)
{
  $search = "";
  for($i = 0; $i < strlen($str); $i++){
    $search .= $str[$i];
      if($i == 0){
        $search .= $del;
      }
      if($i == strlen($str) - 2){
        $search .= $del;
      } 
  }
  return $search;
}


function compareJson($json1, $json2)
{
  // $json1 = json_decode($json1, true);
  // $json2 = json_decode($json2, true);
  $json1 = file_get_contents($json1);
  $json2 = file_get_contents($json2);

  $compareJson1InJson2 = [];
  foreach ($json1 as $key1 => $vol1){
    foreach ($json2 as $key2 => $vol2){
      if(array_key_exists($key1, $json2)){
        if($key1 == $key2 && $vol1 == $vol2){
          $compareJson1InJson2["    " . $key1] = " " . $vol1;
        }
        if($key1 == $key2 && $vol1 != $vol2){
          $compareJson1InJson2["  + " . $key2] = " " . $vol2;
          $compareJson1InJson2["  - " . $key1] = " " . $vol1;
        }
      } else {
        $compareJson1InJson2["  - " . $key1] = " " . $vol1;
      }
    }
  }
  
  $searchNewDataInJson2 = [];
  foreach($json2 as $key2 => $vol2){
    if(!array_key_exists("    " .$key2, $compareJson1InJson2)){
      $searchNewDataInJson2["  + " . $key2] = " " . $vol2;
    }
  }

  $strJson = json_encode(array_merge($compareJson1InJson2, $searchNewDataInJson2));

  $tmp = afterFistBeforLast(str_replace(',', PHP_EOL, $strJson), PHP_EOL);
  return str_replace('"', "", $tmp);
}

// var_dump(json_decode(file_get_contents('after.json'), true));
// var_dump($json2);
// print_r(compareJson($json1, $json2));
print_r(__DIR__);
var_dump(file_exists(__DIR__ . '/after.json')); // проверка пути
var_dump(file_exists('C:/Users/d_solodukhin/Desktop/hexlet/php-project-lvl2/after.json')); // проверка пути