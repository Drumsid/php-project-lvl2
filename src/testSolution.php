<?php

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

$json1 = '{
  "host": "hexlet.io",
  "timeout": 50,
  "proxy": "123.234.53.22"
}';
$json2 = '{
  "timeout": 20,
  "verbose": true,
  "host": "hexlet.io"
}';

function compareJson($json1, $json2)
{
  $json1 = json_decode($json1, true);
  $json2 = json_decode($json2, true);

  $compareJson1InJson2 = [];
  foreach ($json1 as $key1 => $vol1){
    foreach ($json2 as $key2 => $vol2){
      if(array_key_exists($key1, $json2)){
        if($key1 == $key2 && $vol1 == $vol2){
          $compareJson1InJson2[$key1] = $vol1;
        }
        if($key1 == $key2 && $vol1 != $vol2){
          $compareJson1InJson2["+ " . $key2] = $vol2;
          $compareJson1InJson2["- " . $key1] = $vol1;
        }
      } else {
        $compareJson1InJson2["- " . $key1] = $vol1;
      }
    }
  }
  
  $searchNewDataInJson2 = [];
  foreach($json2 as $key2 => $vol2){
    if(!array_key_exists($key2, $compareJson1InJson2)){
      $searchNewDataInJson2["+ " . $key2] = $vol2;
    }
  }

  $strJson = json_encode(array_merge($compareJson1InJson2, $searchNewDataInJson2));

  return afterFistBeforLast(str_replace(',', PHP_EOL, $strJson), PHP_EOL);
}

print_r(compareJson($json1, $json2));