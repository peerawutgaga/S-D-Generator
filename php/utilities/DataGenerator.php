<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once $root . "/php/utilities/Constant.php";

class DataGenerator
{
    public static function getDecimalDigit($decimal){
       $dotPos = strpos($decimal, ".");
       if($dotPos == -1){
           return 0;
       }
       $strlength = strlen($decimal);
       return $strlength-$dotPos-1;
       
    }

    public static function getRandomData($returnType)
    {
        if ($returnType == Constant::STRING_TYPE||$returnType == Constant::STRING_TYPE_UPPER) {
            return self::getRandomString();
        } else if ($returnType == Constant::INT_TYPE) {
            return self::getRandomInt();
        } else if ($returnType == Constant::DOUBLE_TYPE) {
            return self::getRandomDouble();
        } else if ($returnType == Constant::BOOLEAN_TYPE) {
            return self::getRandomBoolean();
        }
        return "null";
    }
    
    

    public static function getRandomString()
    {
        $length = mt_rand(1, 10); // Random length between 1-10
        $randomString = '';
        for ($i = 0; $i < $length; $i ++) {
            $randomString .= Constant::CHAR_SET[mt_rand(0, Constant::CHAR_SET_LENGTH - 1)];
        }
        return '"' . $randomString . '"';
    }

    public static function getRandomInt()
    {
        return mt_rand();
    }
    public static function getRandomIntWithBound($lowerBound, $upperBound){
        return mt_rand($lowerBound,$upperBound);
    }

    public static function getRandomDouble()
    {
        return mt_rand() / mt_rand();
    }

    public static function getRandomDoubleWithBound($lowerBound, $upperBound, $decimal)
    {
        $decimal = pow(10, $decimal);
        return (float) (mt_rand($lowerBound * $decimal, $upperBound * $decimal)) / $decimal;
    }

    public static function getRandomBoolean()
    {
        if (rand(0, 1)) {
            return "true";
        } else {
            return "false";
        }
    }
}

