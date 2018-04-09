function randomInt(from,to){
    return Math.floor(Math.random() * (to - from + 1) ) + from;
}
function randomFloat(from,to){
    return Math.random() * (to - from) + from;
}
function randomChar(){
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    return possible.charAt(Math.floor(Math.random() * possible.length));
}
function randomString(length){
    var rndString = "";
    for(var i =0;i<length;i++){
        rndString += randomChar();
    }
    return rndString;
}
function randomBoolean(){
    var rndVal = Math.floor(Math.random() * 2 ) + 1;
    console.log(rndVal);
    if(rndVal == 1){
        return true;
    }
    return false;
}