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
        case "float" : return "Float_MAX_VALUE";
        case "double" : return "Double.MAX_VALUE";
        case "char" : return "'\\uffff'";
        default : return "null";
    }
}
function getPHPMin(type){
    switch(type){
        case "int" : return "Integer.MIN_VALUE";
        case "float" : return "Float.MIN_VALUE";
        default : return "null";
    }
}
function getPHPMax(type){
    switch(type){
        case "int" : return "Integer.MIN_VALUE";
        case "float" : return "Float.MIN_VALUE";
        default : return "null";
    }
}