function getDefault(type){
    switch(type){
        case "float" : return "0.0";
        case "int" : return "0";
        case "double" : return "0.0";
        case "char" : return "'\\u0000'";
        case "boolean" : return "false";
        case "long" : return "0";
        case "short" : return "0";
        case "byte" : return "0";
        default : return "null";
    }
}
function getMin(type){
    switch(type){
        case "float" : return "(float)1.4E-45";
        case "int" : return "-2147483648";
        case "double" : return "4.9E-324";
        case "long" : return "-9223372036854775808";
        case "short" : return "-32768";
        case "byte" : return "-128";
        default : return "null";
    }
}
function getMax(type){
    switch(type){
        case "float" : return "(float)3.4028235E38";
        case "int" : return "2147483647";
        case "double" : return "1.7976931348623157E308";
        case "long" : return "9223372036854775807";
        case "short" : return "3276";
        case "byte" : return "127";
        default : return "null";
    }
}
function getJavaMin(type){
    switch(type){
        case "byte" : return "-128";
        case "short" : return "-32768";
        case "int" : return "Integer.MIN_VALUE";
        case "long" : return "Long.MIN_VALUE";
        case "float" : return "Float.MIN_VALUE";
        case "double" : return "Double.MIN_VALUE";
        case "char" : return "'\\u0000'";
        default : return "null";
    }
}
function getJavaMax(type){
    switch(type){
        case "byte" : return "127";
        case "short" : return "32767";
        case "int" : return "Integer.MAX_VALUE";
        case "long" : return "Long.MAX_VALUE";
        case "float" : return "Float.MAX_VALUE";
        case "double" : return "Double.MAX_VALUE";
        case "char" : return "'\\uffff'";
        default : return "null";
    }
}
function getPHPMin(type){
    switch(type){
        case "int" : return "PHP_INT_MIN";
        case "float" : return "PHP_FLOAT_MIN";
        default : return "null";
    }
}
function getPHPMax(type){
    switch(type){
        case "int" : return "PHP_INT_MAX";
        case "float" : return "PHP_FLOAT_MAX";
        default : return "null";
    }
}