<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once $root . "/php/utilities/Script.php";
require_once $root . "/php/utilities/DataGenerator.php";

class GuardConditionProcessor
{

    public static function parseGuardConditionString($statement)
    {
        $guardCondition = array();
        preg_match('/(\w*\s)([<>!=]{1,2})(\s\d*.\d*)/', $statement, $guardCondition);
        $variable = trim($guardCondition[1]);
        $operator = $guardCondition[2];
        $constant = trim($guardCondition[3]);
        return array(
            "variable" => $variable,
            "operator" => $operator,
            "constant" => $constant
        );
    }

    public static function getValueByCondition($param, $guardConditionObj)
    {
        $dataType = $param["dataType"];
        if ($dataType == Constant::BOOLEAN_TYPE) {
            return $guardConditionObj["constant"];
        } else if ($dataType == Constant::FLOAT_TYPE || $dataType == Constant::DOUBLE_TYPE) {
            if ($guardConditionObj["operator"] == Constant::EQUAL) {
                return $guardConditionObj["constant"];
            } else if ($guardConditionObj["operator"] == Constant::NOT_EQUAL) {
                return (float) $guardConditionObj["constant"] + 1;
            } else if ($guardConditionObj["operator"] == Constant::GREATER || $guardConditionObj["operator"] == Constant::GREATER_OR_EQUAL) {
                $decimal = DataGenerator::getDecimalDigit($guardConditionObj["constant"]);
                return DataGenerator::getRandomDoubleWithBound($guardConditionObj["constant"], $guardConditionObj["constant"]*2, $decimal);
            } else if ($guardConditionObj["operator"] == Constant::LESS || $guardConditionObj["operator"] == Constant::LESS_OR_EQUAL) {
                $decimal = DataGenerator::getDecimalDigit($guardConditionObj["constant"]);
                return DataGenerator::getRandomDoubleWithBound($guardConditionObj["constant"]/2, $guardConditionObj["constant"], $decimal);
            } else {
                return $guardConditionObj["constant"];
            }
        } else if ($dataType == Constant::INT_TYPE) {
            if ($guardConditionObj["operator"] == Constant::EQUAL) {
                return $guardConditionObj["constant"];
            } else if ($guardConditionObj["operator"] == Constant::NOT_EQUAL) {
                return $guardConditionObj["constant"] + 1;
            } else if ($guardConditionObj["operator"] == Constant::GREATER) {
                return DataGenerator::getRandomIntWithBound($guardConditionObj["constant"] + 1, PHP_INT_MAX);
            } else if ($guardConditionObj["operator"] == Constant::GREATER_OR_EQUAL) {
                return DataGenerator::getRandomIntWithBound($guardConditionObj["constant"], PHP_INT_MAX);
            } else if ($guardConditionObj["operator"] == Constant::LESS) {
                return DataGenerator::getRandomIntWithBound(PHP_INT_MIN, $guardConditionObj["constant"] - 1);
            } else if ($guardConditionObj["operator"] == Constant::LESS_OR_EQUAL) {
                return DataGenerator::getRandomIntWithBound(PHP_INT_MIN, $guardConditionObj["constant"]);
            } else {
                return $guardConditionObj["constant"];
            }
        } else {
            return DataGenerator::getRandomData($dataType);
        }
    }
}

