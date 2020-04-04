<?php

class Common
{
    public static function sortBySequenceIndex($array)
    {
        for ($i = 0; $i < count($array); $i ++) {
            for ($j = $i; $j < count($array); $j ++) {
                if ($array[$i]["seqIdx"] > $array[$j]["seqIdx"]) {
                    $temp = $array[$i];
                    $array[$i] = $array[$j];
                    $array[$j] = $temp;
                }
            }
        }
        return $array;
    }
    
    public static function convertArrayOfArrayToSingleStringByKey($in_array, $key)
    {
        $outString = "";
        foreach ($in_array as $item) {
            $outString .= $item[$key].",";
        }
        $lastCharacter = substr($outString, - 1);
        if ($lastCharacter == ",") {
            $outString = substr($outString, 0, - 1);
        }
        return $outString;
    }
    public static function removeLastComma($string){
        $lastCharacter = substr($string, - 1);
        if ($lastCharacter == ",") {
            $output = substr($string, 0, - 1);
        }else{
            $output = $string;
        }
        return $output;
    }
    public static function concatArray($sourceArray,$newArray){
        foreach($newArray as $item){
            array_push($sourceArray,$item);
        }
        return $sourceArray;
    }
}

