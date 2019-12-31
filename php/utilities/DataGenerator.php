<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once $root . "/php/utilities/Constant.php";

class DataGenerator
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

    public static function getRandomData($returnType)
    {
        if ($returnType == Constant::STRING_TYPE) {
            return self::getRandomString();
        }else if ($returnType == Constant::INT_TYPE) {
            return self::getRandomInt();
        }else if ($returnType == Constant::DOUBLE_TYPE) {
            return self::getRandomDouble();
        }else if ($returnType == Constant::BOOLEAN_TYPE) {
            return self::getRandomBoolean();
        }
        return "null";
    }

    private static function getRandomString()
    {
        $length = rand(1,10);//Random length between 1-10
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= Constant::CHAR_SET[rand(0, Constant::CHAR_SET_LENGTH - 1)];
        }
        return $randomString;
    }
    private static function getRandomInt(){
        return rand();
    }
    private static function getRandomDouble(){
        return rand()/rand();
    }
    private static function getRandomBoolean(){
        if(rand(0,1)){
            return "true";
        }else{
            return "false";
        }
    }
}

