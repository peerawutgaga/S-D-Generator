function getDefault(type){
    switch(type){
        case "float" : return "0.0";
        case "int" : return "0";
        case "double" : return "0.0";
        case "char" : return "''";
        case "string" : return "\"\"";
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